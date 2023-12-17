<template>
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation" id="navbar">
        <div class="navbar-brand">
            <a class="navbar-item" href="/">
                <span class="icon" id="myhub-brand">
                    <font-awesome-icon :icon="['fab', 'hubspot']" />
                </span>
            </a>
            <a 
                role="button" 
                class="navbar-burger" 
                :class="{ 'is-active': burgerIsOpen }"
                aria-label="menu" 
                aria-expanded="false" 
                data-target="navbar-menu" 
                @click="burgerToggle"
            >
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>
        <div 
            id="navbar-menu" 
            class="navbar-menu"
            :class="{ 'is-active': burgerIsOpen }"
        >
            <div class="navbar-start">
                <a class="navbar-item" href="/">
                    Profile
                </a>
                <a class="navbar-item">
                    Explore
                </a>
                <a class="navbar-item">
                    Feed
                </a>
                <a class="navbar-item">
                    Login / Register
                </a>
            </div>
            <div class="navbar-end"></div>
        </div>

    </nav>
</template>

<script lang="ts">
import { Vue, Component } from 'vue-facing-decorator';

@Component({})
export default class Navbar extends Vue
{
    burgerIsOpen : boolean = false;

    mounted()
    {
        // Add necessary bulma class to html tag
        document.getElementsByTagName("html").item(0)?.classList.add("has-navbar-fixed-top");
    }

    burgerToggle()
    {
        this.burgerIsOpen = !this.burgerIsOpen;
    }
}
</script>

<style>
#navbar {
    text-align: center;
    padding: 0.5em 0.5em;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

#myhub-brand {
    font-size: 3em;
    width: 1.2em;
}
#navbar-menu.is-active 
{
    position: absolute;
    right: 0.5em;
}
</style>