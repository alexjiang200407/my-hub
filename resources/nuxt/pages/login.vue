<template>
    <Navbar />
    <section id="login" class="hero">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column">
                        <Tabs 
                            :data = "tabProps"
                            @onTabChanged = "tabChanged($event)"
                        />
                        <LoginForm v-if="tab === 0"/>
                        <RegisterForm v-else />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script lang="ts">
import { Vue, Component } from 'vue-facing-decorator';
import Navbar from '../components/navbar.vue';
import Tabs, { TabProps } from '../components/tabs.vue';
import LoginForm from '../components/loginForm.vue';
import RegisterForm from '../components/registerForm.vue';


// Tab indexes in order
enum tabState
{
    LOGIN = 0,
    REGISTER = 1
}


@Component({ components: { Navbar, Tabs, LoginForm, RegisterForm } })
export default class Login extends Vue
{
    tab : tabState = tabState.LOGIN;

    tabProps : TabProps = {
        tabs: [
            {
                txt: "Login"
            },
            {
                txt: "Register"
            }
        ],
        selected: 0
    };


    tabChanged(tabIndex : number)
    {
        this.tab = tabIndex;
    }

}
</script>

<style>
@import 'bulma/css/bulma.css';

#login 
{
    height: 100%;
}

#login.hero
{
    align-items: center;
    justify-content: center;
    flex-direction: row;
}

#login-box
{
    padding: 2em;
}

#login .tabs
{
    margin-bottom: 0em;
    border-color: transparent;
}

</style>