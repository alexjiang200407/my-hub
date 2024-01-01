
<template>
    <Navbar />
    <section id="profile">
        <h1 class="title">My Profile</h1>
        <Loader v-if="!loaded" />
        <UserPost v-else-if="postStore.posts.length" v-for="data in postStore.posts" :data ="data" />
        <div id="no-posts-prompt" v-else>You do not have any posts.</div>

        <button class="is-icon-button is-large is-rounded" id="add-post-button" @click="addPost">
            <span class="icon">
                <font-awesome-icon :icon="['fas', 'plus']" />
            </span>
        </button>
    </section>
</template>

<script lang="ts">

import { Vue, Component } from "vue-facing-decorator";
import Navbar from "../components/navbar.vue"
import { usePostStore } from "../store/postStore"
import { useUserStore } from "../store/userStore";
import Loader from "../components/loader.vue";
import UserPost from "../components/userPost.vue";


@Component({ components: { UserPost, Navbar } })
export default class PostManager extends Vue 
{
    postStore = usePostStore();
    userStore = useUserStore();
    loaded = false;

    async created()
    {
        if (!this.userStore.isStoreInit)
        {
            await this.userStore.init();
        }

        // If logged in get user posts 
        if (this.userStore.isLoggedIn)
        {
            if (!this.postStore.isStoreInit)
            {
                await this.postStore.init();
            }
            this.loaded = true;
        }
        // If not logged in go to login page
        else
        {
            this.$router.push("/login");
        }
    }
    
    addPost() 
    {
        if (!this.loaded)
        {
            return;
        }

        // id of "" indicates edit
        this.postStore.pushPost({
            isEdit: true,
            id: "",
            tags: []
        });
    }
}
</script>


<style>
@import 'bulma/css/bulma.css';

#add-post-button 
{
    position: fixed;
    bottom: 5em;
    right: 5vh;
    border: none;
}

#profile 
{
    width: 40em;
    height: calc(100% - 2em);
}

#no-posts-prompt
{
    animation: fade-in 0.4s ease;
}

</style>