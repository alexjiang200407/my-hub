import { defineStore } from "pinia";
import { DefinePost } from "../types/post";


export interface DefinePostsStore
{
    data: DefinePost[]
}

export interface PostAPIData
{
    postC: number,
    posts: DefinePost[]
};


// Data store for posts
export const usePostStore = defineStore({
    id: 'post-store',
    state: () : DefinePostsStore => ({
        data: []
    }),
    actions: {
        pushPost(newPost : DefinePost) 
        {
            this.$state.data.push(newPost);
        },
        removePost(remove : DefinePost)
        {
            const index : number = this.$state.data.indexOf(remove);
            this.$state.data.splice(index, 1);
        },
        setPosts(posts : DefinePost[])
        {
            this.$state.data = posts;
        },
        async refreshPosts()
        {
            // Get the posts from the server
            const data : PostAPIData = await $fetch('http://localhost:8000/api/13458/posts');
            console.log(data)
            this.setPosts(data.posts);
        },
    },
    getters: {
        posts: (state) => state.data
    }
});