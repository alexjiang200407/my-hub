<template>
    <!-- Editing/Adding the reminder -->
    <div v-if="data.isEdit" class="card reminder-edit">

        <!-- HTML form api for data validation -->
        <form ref="formElement">
            <header  class="card-header">
                <div class="field">
                    <div class="control card-header-title">
                      <input v-model="data.title" ref="titleInput" required maxlength="256" class="input" type="text" placeholder="Title">
                    </div>
                </div>
            </header>
            
            <div class="card-content">
                <div class="field">
                    <div class="control">
                      <textarea v-model="data.content" type="text" class="textarea" maxlength="1024" placeholder="Message"></textarea>
                    </div>
                </div>
            </div>
            <footer class="card-footer">
                <a href="#" class="card-footer-item" @click="saveReminder">Save</a>
                <a href="#" class="card-footer-item" @click="deleteReminder">Delete</a>
            </footer>
        </form>
    </div>
    <!-- Reminder display -->
    <div v-else class="card reminder">
        <header class="card-header">
            <p class="card-header-title">
                {{ data.title }}
            </p>
            <button class="card-header-icon" aria-label="more options">
                <span class="icon" @click="changeContentVisibility">
                    <font-awesome-icon :icon="['fas', 'angle-down']" />
                </span>
            </button>
        </header>
        <div class="card-content" :class="{ collapse: data.collapseContent }">
            <div class="content">
              <div class="rows">
                  <div class="reminder-text">
                      {{ data.content }}
                  </div>
                  <div class="rows-is-full">
                      <a href="#">@bulmaio</a>
                      <a href="#" v-for="tag in data.tags">#{{tag}}</a>
                  </div>
                  <div class="rows-is-full">
                      <time>{{ data.timestamp }}</time>
                  </div>
                  </div>
              </div>
        </div>
        <footer class="card-footer">
            <a href="#" class="card-footer-item" @click="editReminder">Edit</a>
            <a href="#" class="card-footer-item" @click="deleteReminder">Delete</a>
        </footer>
    </div>
</template>

<script lang="ts">

import { Vue, Component, Ref, Prop } from 'vue-facing-decorator';
import ReminderManager from '../pages/index.vue';

export interface DefineReminder 
{
    title?: string,
    id: string,
    isEdit: boolean,
    timestamp?: string,
    content?: string,
    tags?: string[], 
    manager: ReminderManager,
    collapseContent: boolean
};

@Component
export default class Reminder extends Vue
{
    @Prop({ default: { id: 'invalid', isEdit: true } }) data! : DefineReminder;

    @Ref('formElement')
    form!: HTMLFormElement;

    @Ref('titleInput')
    title!: HTMLInputElement;


    mounted()
    {
        // Focus on the title if is editing
        if (this.data.isEdit)
        {
            this.title.focus();
        }
    }

    // Saves the reminder
    saveReminder() 
    {
        // Show prompts if invalid
        if (!this.form.reportValidity())
        {
            return;            
        }

        // Update fields
        const date : Date = new Date;
        this.data.timestamp = date.toDateString();
        this.data.tags = ['herro', 'world'];
        this.data.isEdit = false;

        // Send data to server

    }

    // Edits the reminder
    editReminder() 
    {
        this.data.isEdit = true;
    }

    deleteReminder()
    {
        this.data.manager.deleteReminder(this.data);
    }

    changeContentVisibility()
    {
        this.data.collapseContent = !this.data.collapseContent;
    }
}
</script>


<style>
@import 'bulma/css/bulma.css';

.card.reminder, .card.reminder-edit 
{
    width: 35em;
    margin-bottom: 2em;
}

.reminder-edit textarea 
{
    resize: none;
}

.reminder-edit .field 
{
    width: 100%;
}

.reminder-edit 
{
    animation: reminder-create 0.2s ease-out;
}

.reminder 
{
    animation: reminder-bulge 0.3s ease-out;
}

.reminder .card-content
{
    transition: all 0.2s ease-out;
    overflow: hidden;
}

.reminder .reminder-text
{
    text-overflow: ellipsis;    
    overflow: hidden;
}

.reminder .card-content.collapse 
{
    padding: 0;
    max-height: 0;
    opacity: 0;
}

@keyframes reminder-create 
{
    0% { transform: translateY(4em); opacity: 0; }
    75% { transform: translateY(-0.5em); opacity: 1;}
    100% { transform: translateY(0); }
}

@keyframes reminder-bulge 
{
    0% { transform: scale(1); opacity: 0.25; }
    50% { transform: scale(1.15);}
    100% { transform: scale(1); opacity: 1;}
}

</style>