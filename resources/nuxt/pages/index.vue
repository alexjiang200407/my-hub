
<template>
    <section>
        <h1 class="title">Reminders</h1>
        <reminder v-for="data in store.$state" :data ="data" />

        <button class="is-icon-button is-large is-rounded" id="add-reminder-button" @click="addReminder">
        <span class="icon">
            <font-awesome-icon :icon="['fas', 'plus']" />
        </span>
        </button>
    </section>
</template>

<script lang="ts">

import { Vue, Component } from "vue-facing-decorator";
import Reminder from "../components/reminder.vue";
import { DefineReminder } from "../types/reminder"
import { useReminderStore } from "../store/reminderStore"

export interface ReminderJsonData
{
    reminderC: number,
    reminders: DefineReminder[]
};




@Component({ components: { Reminder } })
export default class ReminderManager extends Vue 
{
    store = useReminderStore();

    async created()
    {
        // Get the reminders from the server
        const data : ReminderJsonData = await $fetch('http://localhost:8000/api/13458/reminders');
        this.store.setReminders(data.reminders);
    }
    
    addReminder() 
    {
        // TODO
        // Get an id from the server

        this.store.$state.push({
            isEdit: true,
            id: "fuck me",
            collapseContent: false
        });
    }

    deleteReminder(toDelete : DefineReminder)
    {
        // Remove reminder from client side
        this.store.removeReminder(toDelete);

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