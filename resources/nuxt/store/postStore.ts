import { defineStore } from "pinia";
import { DefinePost } from "../types/post";
import { useUserStore } from "./userStore";


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
        async getUserPosts()
        {
            let id : string | undefined = useUserStore().id;

            if (!id)
            {
                return;
            }

            // Get the posts from the server
            const data : PostAPIData = await $fetch("http://localhost:8000/api/getposts", {
                method: "GET",
                headers: {
                    accept: "application/json",
                    authorization: `Bearer ${useUserStore().token}`
                }
            });
            this.setPosts(data.posts);
        },
        async savePost(post : DefinePost)
        {
            let sendData = JSON.stringify({
                title: post.title,
                content: post.content,
                tags: post.tags,
            });

            await $fetch("http://localhost:8000/api/savepost", {
                method: "POST",
                headers: {
                    accept: "application/json",
                    authorization: `Bearer ${useUserStore().token}`
                },
                body: sendData
            });
        }
    },
    getters: {
        posts: (state) => state.data
    }
});