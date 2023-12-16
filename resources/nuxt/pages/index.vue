
<template>
    <section>
        <h1 class="title">Reminders</h1>
        <reminder v-for="data in store.reminders" :data ="data" />

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

@Component({ components: { Reminder } })
export default class ReminderManager extends Vue 
{
    store = useReminderStore();

    async created()
    {
        await this.store.refreshReminders();
    }
    
    addReminder() 
    {
        // TODO
        // Get an id from the server
        this.store.pushReminder({
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