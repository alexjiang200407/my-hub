import { defineStore } from "pinia";
import { DefineReminder } from "../types/reminder";


export interface DefineRemindersStore
{
    data: DefineReminder[]
}

export interface ReminderJsonData
{
    reminderC: number,
    reminders: DefineReminder[]
};


// Data store for reminders
export const useReminderStore = defineStore({
    id: 'reminder-store',
    state: () : DefineRemindersStore => ({
        data: []
    }),
    actions: {
        pushReminder(newReminder : DefineReminder) 
        {
            this.$state.data.push(newReminder);
        },
        removeReminder(remove : DefineReminder)
        {
            const index : number = this.$state.data.indexOf(remove);
            this.$state.data.splice(index, 1);
        },
        setReminders(reminders : DefineReminder[])
        {
            this.$state.data = reminders;
        },
        async refreshReminders()
        {
            // Get the reminders from the server
            const data : ReminderJsonData = await $fetch('http://localhost:8000/api/13458/reminders');
            console.log(data)
            this.setReminders(data.reminders);
        },
    },
    getters: {
        reminders: (state) => state.data
    }
});