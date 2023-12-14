<template>
    <section>
        <h1 class="title">Reminders</h1>

        <!-- Reminders -->
        <div class="card" v-for="data in reminders">
            <header class="card-header">
              <p class="card-header-title">
                {{ data.header }}
              </p>
              <button class="card-header-icon" aria-label="more options">
                <span class="icon">
                    <font-awesome-icon :icon="['fas', 'angle-down']" />
                </span>
              </button>
            </header>
            <div class="card-content">
              <div class="content">
                <div class="rows">
                    <div class="rows-is-full">
                        {{ data.content }}
                    </div>
                    <div class="rows-is-full">
                        <a href="#">@bulmaio</a>
                        <a href="#" v-for="tag in data.tags">#{{tag}}</a>
                    </div>
                    <div class="rows-is-full">
                        <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                    </div>
                    </div>
                </div>
            </div>
            <footer class="card-footer">
                <a href="#" class="card-footer-item">Edit</a>
                <a href="#" class="card-footer-item">Delete</a>
            </footer>
        </div>

        <!-- Incoming reminders -->
        <div class="card" v-for="data in incoming">
            <div class="card-content">
                <div class="field">
                    <div class="control">
                      <input v-model="titleInput" class="input" type="text" placeholder="Title">
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                      <textarea v-model="msgInput" class="textarea" placeholder="Message"></textarea>
                    </div>
                </div>
            </div>
            <footer class="card-footer">
                <a href="#" class="card-footer-item" @click="saveReminder">Save</a>
                <a href="#" class="card-footer-item">Delete</a>
            </footer>
        </div>

        <button class="is-icon-button is-large is-rounded" id="add-reminder-button" @click="addReminderEdit">
        <span class="icon">
            <font-awesome-icon :icon="['fas', 'plus']" />
        </span>
        </button>

        <ReminderFormClass />
    </section>

</template>

<script lang="ts">

import { Vue, Component } from "vue-facing-decorator"

@Component
export default class ReminderClass extends Vue {

    reminders = [
        {
            header: "Hello",
            content: "shit",
            id: "abcdefg",
            tags: [
                "Fuck",
                "me"
            ],
            timestamp: ""
        }
    ];
    titleInput = '';
    msgInput = '';
    incoming = [];

    
    addReminderEdit() {
        this.incoming.push({});
    }
    saveReminder() {
        this.reminders.push({
            header: this.titleInput,
            content: this.msgInput,
            id: "abcdefg",
            timestamp: "",
            tags: ['herro', 'world']
        });
    }

    deleteReminderEdit() {
        
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


.card {
    min-width: 300px;
    margin-bottom: 2em;
}


</style>