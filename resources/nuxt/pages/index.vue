
<template>
    <Navbar />
    <section id="profile">
        <h1 class="title">My Profile</h1>
        <Loader v-if="!loaded" />
        <post v-else-if="postStore.posts.length" v-for="data in postStore.posts" :data ="data" />
        <div id="no-posts-prompt" v-else>You do not have any posts.</div>

        <button class="is-icon-button is-large is-rounded" id="add-post-button" @click="addPost">
            <span class="icon">
                <font-awesome-icon :icon="['fas', 'plus']" />
            </span>
        </button>
    </section>
</template>

<script lang="ts">

import { Vue, Component, Watch } from "vue-facing-decorator";
import Post from "../components/post.vue";
import Navbar from "../components/navbar.vue"
import { usePostStore } from "../store/postStore"
import { useUserStore } from "../store/userStore";
import Loader from "../components/loader.vue";


@Component({ components: { Post, Navbar } })
export default class PostManager extends Vue 
{
    postStore = usePostStore();
    userStore = useUserStore();
    loaded = false;

    async created()
    {
        await this.userStore.init();

        // If not logged in go to login page
        if (this.userStore.isLoggedIn)
        {
            await this.postStore.getUserPosts();
            this.loaded = true;
        }
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
    bottom: 3em;
    right: 5vh;
    border: none;
}

#profile 
{
    width: 40em;
    margin-top: 2em;
    height: calc(100% - 2em);
}

#no-posts-prompt
{
    animation: fade-in 0.4s ease;
}

</style>