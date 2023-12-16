import { defineStore } from "pinia";
import { DefineReminder } from "../types/reminder";

// Data store for reminders
export const useReminderStore = defineStore({
    id: 'reminder-store',
    state: () : DefineReminder[] => {
        return [];
    },
    actions: {
        pushReminder(newReminder : DefineReminder) 
        {
            this.$state.push(newReminder);
        },
        removeReminder(remove : DefineReminder)
        {
            const index : number = this.$state.indexOf(remove);
            this.$state.splice(index, 1);
        },
        setReminders(reminders : DefineReminder[])
        {
            this.$state = reminders;
        }
    }
});