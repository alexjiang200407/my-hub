<template>
<section id="explore">
    <Navbar />
        <div id="searchbar" class="rows">
            <Tabs
                class="row is-full"
                :data = "tabProps"
                @onTabChanged="tabChanged($event)"
            />
            <div class="row is-full">
                <div class="field has-addons">
                    <div class="control">
                        <input 
                            class="input" 
                            type="text" 
                            v-model="searchInputStr"
                            ref="searchbar"
                            :placeholder="`${searchForStr[searchTab]}`"
                        >
                    </div>
                    <div class="control">
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
</section>
</template>

<script lang="ts">
import { Component, Ref, Vue } from "vue-facing-decorator";
import Navbar from "../components/navbar.vue"
import Tabs, { TabProps } from "../components/tabs.vue";
import { useExploreStore } from "../store/exploreStore"

enum searchFor
{
    TAG = 0,
    USER = 1
}


@Component({})
export default class ExplorePage extends Vue
{
    searchTab : searchFor = searchFor.TAG;
    searchForStr : string[] = ["Search tags", "Find a user"];
    searchInputStr : string = "";

    tabProps : TabProps = {
        tabs: [
            {
                txt: 'Tags'
            },
            {
                txt: 'Users'
            }
        ],
        selected: 0
    };

    @Ref("searchbar")
    searchBar!: HTMLInputElement;

    store = useExploreStore();

    tabChanged(tabIndex : number)
    {
        this.searchTab = tabIndex as searchFor;
    }

    async handleSearch()
    {
        switch (this.searchTab)
        {
            case searchFor.TAG: await this.store.searchTags(this.searchInputStr); break;
            case searchFor.USER: break;
        }

        // Clear search input
        this.searchBar.value = "";
        this.searchInputStr = "";
    }


}

</script>

<style>
@import 'bulma/css/bulma.css';


#explore .tabs
{
    margin: 0;
    border-bottom: 0;
}

#explore .tabs ul
{
    border-bottom: 0px;
}

#explore .field .has-addons
{
    width: 100%;
}


</style>