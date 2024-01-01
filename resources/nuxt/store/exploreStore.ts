import { defineStore } from "pinia";
import { DefinePost, PostAPIData } from "../types/post";
import { useUserStore } from "./userStore";

export interface DefineExploreStore
{
    postDisplay: DefinePost[]
}


export const useExploreStore = defineStore({
    id: "explore-store",
    state: () : DefineExploreStore => ({
        postDisplay: []
    }),
    actions: {
        async searchTags(tagsStr : string) : Promise<string[] | null>
        {
            let tags : RegExpMatchArray | null = tagsStr.match(/\b(\w+)\b/g);

            if (!tags)
            {
                return [];
            }

            try
            {
                let response : PostAPIData = await $fetch(
                    "http://myhub.my.to/api/posts/filter", {
                        method: "POST",
                        headers: {
                            accept: "application/json"
                        },
                        body: JSON.stringify({
                            tags: tags
                        })
                    }
                );

                console.log(response);

                this.setPostDisplay(response.posts);
                console.log(this.display);

                return tags as string[];
            }
            catch (error)
            {
                console.error(error);
                return null;
            }
        },
        setPostDisplay(display : DefinePost[])
        {
            this.$state.postDisplay = display;
        }
    },
    getters: {
        display: (state) => state.postDisplay
    }
});