import { defineStore } from "pinia";
import { DefinePost, PostAPIData } from "../types/post";
import { useUserStore } from "./userStore";


export interface DefinePostsStore
{
    data: DefinePost[],
    isInit: boolean
}

export interface SavePostJSON
{
    message : string,
    postId : BigInt
}

// Data store for posts
export const usePostStore = defineStore({
    id: 'post-store',
    state: () : DefinePostsStore => ({
        data: [],
        isInit: false
    }),
    actions: {
        pushPost(newPost : DefinePost) 
        {
            this.$state.data.push(newPost);
        },
        setPosts(posts : DefinePost[])
        {
            this.$state.data = posts;
        },
        async init()
        {
            let id : string | undefined = useUserStore().id;

            if (!id)
            {
                return;
            }

            try
            {
                // Get the posts from the server
                const data : PostAPIData = await $fetch("http://myhub.my.to/api/user/getposts", {
                    method: "GET",
                    headers: {
                        accept: "application/json",
                        authorization: `Bearer ${useUserStore().token}`
                    }
                });
                console.log(data.posts);
                this.setPosts(data.posts);
                this.$state.isInit = true;
            }
            catch (error)
            {
                console.log(error);
            }
        },
        async createPost(post : DefinePost) : Promise<BigInt>
        {
            let sendData = JSON.stringify({
                title: post.title,
                content: post.content,
                tags: post.tags,
            });

            console.log(sendData);

            try
            {
                let response : SavePostJSON = await $fetch("http://myhub.my.to/api/user/savepost", {
                    method: "POST",
                    headers: {
                        accept: "application/json",
                        authorization: `Bearer ${useUserStore().token}`
                    },
                    body: sendData
                });           

                return response.postId;
            }
            catch (error)
            {
                console.error(error);
                return BigInt(0);
            }
        },
        async deletePost(post: DefinePost)
        {
            // Remove post from client side
            const index : number = this.$state.data.indexOf(post);
            this.$state.data.splice(index, 1);

            // Hasn't added post to server yet do not need to delete
            if (post.id === "")
            {
                return;
            }

            // Delete post from server side
            try
            {
                await $fetch("http://myhub.my.to/api/user/deletepost", {
                    method: "POST",
                    headers: {
                        accept: "application/json",
                        authorization: `Bearer ${useUserStore().token}`
                    },
                    body: JSON.stringify({
                        postId: post.id.toString()
                    })
                });
            }
            catch (error)
            {
                console.error(error);
            }
        },
        async updatePost(post: DefinePost)
        {
            let sendData = JSON.stringify({
                title: post.title,
                content: post.content,
                tags: post.tags,
                postId: post.id.toString()
            });


            // Update post from server side
            try
            {
                console.log(sendData);
                await $fetch("http://myhub.my.to/user/updatepost", {
                    method: "POST",
                    headers: {  
                        accept: "application/json",
                        authorization: `Bearer ${useUserStore().token}`
                    },
                    body: sendData
                });
            }
            catch (error)
            {
                console.error(error);
            }
        }
    },
    getters: {
        posts: (state) => state.data,
        isStoreInit: (state) => state.isInit
    }
});