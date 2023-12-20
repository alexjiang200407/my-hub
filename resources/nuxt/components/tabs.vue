<template>
    <div class="tabs is-boxed is-centered">
        <ul>
            <li 
                v-for="(tab, index) in data.tabs" 
                :class="{ 'is-active': (index === data.selected) }"
                @click="tabClick(index)"
            >
                <a>{{ tab.txt }}</a>
            </li>
        </ul>
    </div>
</template>


<script lang="ts">
import { Component, Emit, Prop, Vue } from 'vue-facing-decorator';


export interface Tab
{
    txt : String
}

export interface TabProps
{
    tabs: Tab[];
    selected: number;
}


@Component({})
export default class Tabs extends Vue
{
    @Prop() data! : TabProps;

    @Emit("onTabClick")
    tabClick(index: number)
    {
        if (index === this.data.selected)
        {
            return;
        }

        return this.changeTab(index);
    }

    @Emit("onTabChanged")
    changeTab(index: number) : number
    {
        this.data.selected = index;
        return this.data.selected;
    }
}


</script>

<style>
</style>