import { defineStore } from "pinia";
import { PostAPIData } from "../types/post";
import { useUserStore } from "./userStore";

export interface DefineExploreStore
{

}


export const useExploreStore = defineStore({
    id: "explore-store",
    state: () : DefineExploreStore => ({

    }),
    actions: {
        async searchTags(tagsStr : string)
        {
            let tags : RegExpMatchArray | null = tagsStr.match(/\b(\w+)\b/g);

            try
            {
                let pp = await $fetch(
                    "http://localhost:8000/api/posts/filter", {
                        method: "POST",
                        headers: {
                            accept: "application/json"
                        },
                        body: JSON.stringify({
                            tags: tags
                        })
                    }
                );
                console.log(pp);
            }
            catch (error)
            {
                console.error(error);
            }

        }
    }
});