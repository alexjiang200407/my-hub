<template>
    <form action="" class="box" id="login-box" onsubmit="return false" ref="form">
        <div class="field">
            <label for="name-input" class="label">Name</label>
            <div class="control has-icons-left">
                <input id="name-input" type="string" placeholder="Harambe420" class="input" v-model="nameInput" required>
                <span class="icon is-small is-left">
                    <font-awesome-icon :icon="['fas', 'user']" />
                </span>
            </div>
        </div>
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
                <input id="password-input" type="password" class="input" v-model="passwordInput" required minlength=8>
                <span class="icon is-small is-left">
                    <font-awesome-icon :icon="['fa', 'lock']" />
                </span>
            </div>
        </div>
        <button class="button is-primary" @click="register" ref="submitButton">
            Register
        </button>
    </form>
</template>


<script lang="ts">
import { Vue, Component, Ref } from 'vue-facing-decorator';
import { useUserStore } from '../store/userStore';

@Component({})
export default class RegisterForm extends Vue
{
    emailInput : string = '';
    nameInput : string = '';
    passwordInput : string = '';

    @Ref("submitButton")
    submit! : HTMLButtonElement;

    @Ref("form")
    form! : HTMLFormElement;

    async register()
    {
        if (!this.form.checkValidity())
        {
            return;
        }

        // We don't know if the password username is valid yet
        this.submit.setCustomValidity("");

        console.log(this.emailInput, this.passwordInput);
        let success : boolean = await useUserStore().register(this.emailInput, this.nameInput, this.passwordInput);
        if (!success)
        {
            // Prompt that it is invalid
            this.submit.setCustomValidity(`Could not register account for ${this.emailInput}`);
            this.form.reportValidity();
        }
        else
        {
            alert("Successfully created account");
        }
    }

}
</script>

<style>

</style>