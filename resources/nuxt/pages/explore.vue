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
                      <input class="input" type="text" :placeholder="`${searchForStr[searchTab]}`">
                    </div>
                    <div class="control">
                      <a class="button is-info">
                        Search
                      </a>
                    </div>
                  </div>
            </div>
    </div>
</section>
</template>

<script lang="ts">
import { Component, Vue } from "vue-facing-decorator";
import Navbar from "../components/navbar.vue"
import Tabs, { TabProps } from "../components/tabs.vue";

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

    tabChanged(tabIndex : number)
    {
        this.searchTab = tabIndex as searchFor;
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