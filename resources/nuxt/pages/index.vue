
<template>
    <Navbar />
    <section id="profile">
        <h1 class="title">My Profile</h1>
        <post v-for="data in store.posts" :data ="data" />

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
import Cookies from "js-cookie"
import { useUserStore } from "../store/userStore";


// let loggedIn : boolean = useUserStore().isLoggedIn;

@Component({ components: { Post, Navbar } })
export default class PostManager extends Vue 
{
    store = usePostStore();

    async created()
    {
        let token : string = Cookies.get('accessToken') as string;
        if (token === undefined)
        {
            console.log("Not logged in");
            this.$router.push("/login");
        }
        else
        {
            console.log("Logged in ", token);
        }

        await this.store.refreshPosts();
    }
    
    addPost() 
    {
        // TODO
        // Get an id from the server
        this.store.pushPost({
            isEdit: true,
            id: "fuck me",
            collapseContent: false
        });
    }

    deletePost(toDelete : DefinePost)
    {
        // Remove post from client side
        this.store.removePost(toDelete);

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