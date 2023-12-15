
<template>
    <section>
        <h1 class="title">Reminders</h1>
        <reminder v-for="data in reminders" :data ="data" />

        <button class="is-icon-button is-large is-rounded" id="add-reminder-button" @click="addReminder">
        <span class="icon">
            <font-awesome-icon :icon="['fas', 'plus']" />
        </span>
        </button>
    </section>
</template>

<script lang="ts">

import { Vue, Component } from "vue-facing-decorator";
import Reminder, { DefineReminder } from "../components/reminder.vue";

@Component({ components: { Reminder } })
export default class ReminderManager extends Vue {

    reminders : DefineReminder[] = [];

    async created()
    {
        // Get the reminders from the server
        const data = await $fetch('http://localhost:8000/api/test');
        console.log(data);
    }
    
    addReminder() 
    {
        // Get an id from the server

        this.reminders.push({
            isEdit: true,
            id: this.reminders.length.toString(),
            manager: this,
            collapseContent: false
        });
    }

    deleteReminder(toDelete : DefineReminder)
    {
        // Remove reminder from client side
        const index : number = this.reminders.indexOf(toDelete);
        this.reminders.splice(index, 1);

        // Tell database to remove the reminder as well
    }

}
</script>


<style>
@import 'bulma/css/bulma.css';

#add-reminder-button {
    position: fixed;
    bottom: 10vh;
    right: 5vh;
    border: none;
}

</style>