<template>
    <nav 
        class="navbar" 
        :class="getNavbarPosition()" 
        role="navigation" 
        aria-label="main navigation" 
        :id="!isMobile? 'navbar' : ''"
    >
        <div v-if="isMobile">
            <div class="navbar-menu is-active" id="navbar-menu-mobile">
                <VueLink class="navbar-item" 
                    :to="'/'"
                >
                    <span class="icon">
                        <font-awesome-icon :icon="['fas', 'user']" />
                    </span>
                </VueLink>
                <VueLink 
                    class="navbar-item"
                    :to="'explore'"
                >
                    <span class="icon">
                        <font-awesome-icon :icon="['fas', 'compass']" />
                    </span>
                </VueLink>
                <VueLink 
                    class="navbar-item"
                    @click="logOut"
                >
                    <span class="icon">
                        <font-awesome-icon :icon="['fas', 'right-from-bracket']" />
                    </span>
                </VueLink>
            </div>
        </div>
        <div v-if="!isMobile" class="navbar-brand">
            <VueLink class="navbar-item" :to="loggedIn? '/' : '/login'">
                <span class="icon" id="myhub-brand">
                    <font-awesome-icon :icon="['fab', 'hubspot']" />
                </span>
            </VueLink>
        </div>
        <div 
            id="navbar-menu" 
            class="navbar-menu"
            v-if="!isMobile"
        >
            <!-- If logged in -->
            <div class="navbar-start" v-if="loggedIn">
                <VueLink class="navbar-item" :to="'/'" :txt="'Profile'" />
                <VueLink class="navbar-item" :to="'/explore'" :txt="'Explore'" />
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
import { storeToRefs } from 'pinia';
import { ComputedRef } from 'vue-demi';
import { isMobile } from "is-mobile";

@Component({ components: { VueLink } })
export default class Navbar extends Vue
{
    burgerIsOpen : boolean = false;
    isMobile : boolean = isMobile();

    // Reference to user login status
    loggedIn : ComputedRef<boolean>;

    constructor()
    {
        super({}, Vue);
        ({ isLoggedIn: this.loggedIn } = storeToRefs(useUserStore()));
    }

    mounted()
    {
        // Add necessary bulma class to html tag
        if (this.isMobile)
        {
            document.getElementsByTagName("html").item(0)?.classList.add("has-navbar-fixed-bottom");
        }
        else
        {
            document.getElementsByTagName("html").item(0)?.classList.add("has-navbar-fixed-top");
        }
    }

    burgerToggle()
    {
        this.burgerIsOpen = !this.burgerIsOpen;
    }


    async logOut()
    {
        let success : boolean = await useUserStore().logOut();
    }

    @Watch("loggedIn")
    loggedOut()
    {
        if (!this.loggedIn)
        {
            this.$router.push("/login");
        }
        else
        {
            this.$router.push("/");
        }
    }


    getNavbarPosition() : string
    {
        if (this.isMobile)
        {
            return "is-fixed-bottom";
        }

        return "is-fixed-top";
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

#navbar-menu-mobile
{
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.75em;
    gap: 0.75em;
    padding: 0;
}


</style>