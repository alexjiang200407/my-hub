<template>
<section id="explore">
    <Navbar />
    <div class="rows">
        <div id="searchbar" class="row is-full rows">
            <Tabs
                class="row is-full"
                :data = "tabProps"
                @onTabChanged="tabChanged($event)"
            />
            <div class="row is-full">
                <div class="field has-addons columns is-mobile is-desktop">
                    <div class="control column is-four-fifths">
                        <input 
                            class="input" 
                            type="text" 
                            v-model="searchInputStr"
                            ref="searchbar"
                            :placeholder="`${searchForStr[searchTab]}`"
                        >
                    </div>
                    <div class="control column">
                        <button 
                        class="button is-info"
                        @click="handleSearch"
                        >
                        Search
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div id="explore-post-display" class="row is-full">
            <Post v-if="display.length !== 0 && lastSearchTags && lastSearchTags.length > 0" v-for="data in display" :data="data"/>
            <p v-else-if="display.length === 0 && lastSearchTags && lastSearchTags.length > 0" class="text">There are no posts with tags: <span v-for="(tag, i) in lastSearchTags">'{{ tag }}'{{ (i === lastSearchTags.length - 1)? "" : ", " }} </span></p>
            <p v-else-if="lastSearchTags && lastSearchTags.length <= 0">Invalid input</p>
            <p v-else class="text">Please enter a search</p>
        </div>
    </div>
</section>
</template>

<script lang="ts">
import { Component, Ref, Vue } from "vue-facing-decorator";
import Navbar from "../components/navbar.vue"
import Tabs, { TabProps } from "../components/tabs.vue";
import { useExploreStore } from "../store/exploreStore"
import Post from "../components/post.vue";
import { DefinePost } from "../types/post";

enum searchFor
{
    TAG = 0
}


@Component({ components: { Post } })
export default class ExplorePage extends Vue
{
    searchTab : searchFor = searchFor.TAG;
    searchForStr : string[] = ["Search tags", "Find a user"];
    searchInputStr : string = "";

    tabProps : TabProps = {
        tabs: [
            {
                txt: 'Tags'
            }
        ],
        selected: 0
    };

    @Ref("searchbar")
    searchBar!: HTMLInputElement;

    store = useExploreStore();
    display : DefinePost[] = [];
    lastSearchTags : string[] | null = null;


    tabChanged(tabIndex : number)
    {
        this.searchTab = tabIndex as searchFor;
    }

    async handleSearch()
    {
        let parsedTags : string[]  | null = null;
        switch (this.searchTab)
        {
            case searchFor.TAG:  parsedTags = await this.store.searchTags(this.searchInputStr); break;
        }
        this.display = this.store.postDisplay;

        // [] indicates error
        if (parsedTags)
        {
            this.lastSearchTags = parsedTags;
        }

        // Clear search input
        this.searchBar.value = "";
        this.searchInputStr = "";
    }


}

</script>

<style>
@import 'bulma/css/bulma.css';

#explore
{
    width: 40em;
}
#explore .tabs
{
    margin: 0;
    border-bottom: 0;
}

#explore .column.control
{
    padding-inline: 0;
}

#searchbar button
{
    width: 100%;
}

#explore .tabs ul
{
    border-bottom: 0px;
}

#explore .field .has-addons
{
    width: 100%;
}

#explore-post-display
{
    margin-top: 2em;
}


</style>