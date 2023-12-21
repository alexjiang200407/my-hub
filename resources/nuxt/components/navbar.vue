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
            <!-- If logged in -->
            <div class="navbar-start" v-if="loggedIn">
                <VueLink class="navbar-item" :to="'/'" :txt="'Profile'" />
                <VueLink class="navbar-item" :to="'/'" :txt="'Explore'" />
                <VueLink class="navbar-item" :txt="'Logout'" @click="logOut"/>
            </div>

            <!-- Not logged in -->
            <div class="navbar-start" v-else>
                <VueLink class="navbar-item" :to="'/login'" :txt="'Login / Register'" />
            </div>  
            <div class="navbar-end"></div>
        </div>

    </nav>
</template>

<script lang="ts">
import { Vue, Component, Watch } from 'vue-facing-decorator';
import VueLink from './link.vue';
import { useUserStore } from '../store/userStore';

@Component({ components: { VueLink } })
export default class Navbar extends Vue
{
    burgerIsOpen : boolean = false;
    loggedIn : boolean | undefined = useUserStore().isLoggedIn;

    mounted()
    {
        // Add necessary bulma class to html tag
        document.getElementsByTagName("html").item(0)?.classList.add("has-navbar-fixed-top");
    }

    burgerToggle()
    {
        this.burgerIsOpen = !this.burgerIsOpen;
    }


    async logOut()
    {
        let success : boolean = await useUserStore().logOut();

        if (success)
        {
            this.loggedIn = false;
        }
    }

    @Watch("loggedIn")
    loggedOut()
    {
        if (this.loggedIn === false)
        {
            console.log("logged out");
            this.$router.push("/login");
        }
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