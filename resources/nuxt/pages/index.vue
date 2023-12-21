
<template>
    <Navbar />
    <section id="profile">
        <h1 class="title">My Profile</h1>
        <post v-for="data in postStore.posts" :data ="data" />

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
import { DefinePost } from "../types/post"
import Navbar from "../components/navbar.vue"
import { usePostStore } from "../store/postStore"
import { useUserStore } from "../store/userStore";


@Component({ components: { Post, Navbar } })
export default class PostManager extends Vue 
{
    postStore = usePostStore();
    userStore = useUserStore();

    async created()
    {
        await this.userStore.init();
        await this.postStore.getUserPosts();
    }
    
    addPost() 
    {
        // TODO
        // Get an id from the server
        this.postStore.pushPost({
            isEdit: true,
            id: "",
            collapseContent: false
        });
    }

    deletePost(toDelete : DefinePost)
    {
        // Remove post from client side
        this.postStore.removePost(toDelete);

        // Tell database to remove the post as well
    }

}
</script>


<style>
@import 'bulma/css/bulma.css';

#add-post-button {
    position: fixed;
    bottom: 3em;
    right: 5vh;
    border: none;
}

#profile {
    width: 40em;
    margin-top: 2em;
}

</style>