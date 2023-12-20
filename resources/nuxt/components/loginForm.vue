<template>
    <form action="" class="box" id="login-box" onsubmit="return false" ref="form">
        <div class="field">
            <label for="email-input" class="label">Email</label>
            <div class="control has-icons-left">
                <input id="email-input" type="email" placeholder="e.g. johndoe@gmail.com" class="input" required v-model="emailInput">
                <span class="icon is-small is-left">
                    <font-awesome-icon :icon="['fa', 'envelope']" />
                </span>
            </div>
        </div>
        <div class="field">
            <label for="password-input" class="label">Password</label>
            <div class="control has-icons-left">
                <input id="password-input" type="password" class="input" v-model="passwordInput" required>
                <span class="icon is-small is-left">
                    <font-awesome-icon :icon="['fa', 'lock']" />
                </span>
            </div>
        </div>
        <div class="field">
            <label for="remember-me" class="checkbox">
                <input type="checkbox" id="remember-me" v-model="rememberMe">
                Remember me
            </label>
            </div>
            <div class="field">
            <button class="button is-primary" @click="login" ref="submitButton">
                Login
            </button>
        </div>
    </form>
</template>


<script lang="ts">
import { Vue, Component, Ref } from 'vue-facing-decorator';
import { useUserStore } from '../store/userStore';
import Cookies from "js-cookie"

@Component({})
export default class LoginForm extends Vue
{
    emailInput : string = '';
    passwordInput : string = '';
    rememberMe : boolean = false;

    @Ref("submitButton")
    submit! : HTMLButtonElement;

    @Ref("form")
    form! : HTMLFormElement;

    async login()
    {
        if (!this.form.checkValidity())
        {
            return;
        }

        // We don't know if the password username is valid yet
        this.submit.setCustomValidity("");

        let token : string | null = await useUserStore().auth(this.emailInput, this.passwordInput);
        if (token)
        {
            // Go home
            this.$router.push("/");
            Cookies.set("accessToken", token);
        }
        else
        {
            // Prompt that it is invalid
            this.submit.setCustomValidity("Invalid username password combination");
            this.form.reportValidity();
        }
    }

}
</script>

<style>

</style>