<template>
    <!-- Editing/Adding the post -->
    <div v-if="data.isEdit" class="card post-edit">

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
                <a href="#" class="card-footer-item" @click="savePost">Save</a>
                <a href="#" class="card-footer-item" @click="deletePost">Delete</a>
            </footer>
        </form>
    </div>
    <!-- Post display -->
    <div v-else class="card post">
        <header class="card-header">
            <div class="card-header-title">
                <div class="rows">
                    <p class="post-title row is-full">{{data.title}}</p>
    
                    <div class="row is-full">
                        <time>{{ getTimeStr(data.timestamp as string) }}</time>
                        <span class="icon period">
                            <font-awesome-icon :icon="['fas', 'circle']" />
                        </span>
                        <a class="username" href="#">@{{ user.name }}</a>
                    </div>
                </div>

            </div>
            <button class="card-header-icon" aria-label="more options">
                <span class="icon" @click="changeContentVisibility">
                    <font-awesome-icon :icon="['fas', 'angle-down']" />
                </span>
            </button>
        </header>
        <div class="card-content" :class="{ collapse: data.collapseContent }">
            <div class="content">
                <div class="rows">
                    <div class="post-text" v-html="htmlContent()" />
                </div>
            </div>
        </div>
        <footer class="card-footer">
            <a href="#" class="card-footer-item" @click="editPost">Edit</a>
            <a href="#" class="card-footer-item" @click="deletePost">Delete</a>
        </footer>
    </div>
</template>

<script lang="ts">

import { Vue, Component, Ref, Prop } from 'vue-facing-decorator';
import { DefinePost } from '../types/post';
import { usePostStore } from "../store/postStore"
import { useUserStore } from '../store/userStore';

@Component
export default class Post extends Vue
{
    @Prop({ default: { id: 'invalid', isEdit: true } }) data! : DefinePost;

    @Ref('formElement')
    form!: HTMLFormElement;

    @Ref('titleInput')
    title!: HTMLInputElement;

    store = usePostStore();
    user = useUserStore();

    mounted()
    {
        // Focus on the title if is editing
        if (this.data.isEdit)
        {
            this.title.focus();
        }
    }

    // Saves the post
    async savePost() 
    {
        // Show prompts if invalid
        if (!this.form.reportValidity())
        {
            return;            
        }

        // Update fields
        const date : Date = new Date;
        this.data.timestamp = date.toDateString();
        this.findTags();
        this.data.isEdit = false;

        // New post
        if (this.data.id === "")
        {
            let postId : BigInt = await this.store.createPost(this.data);
            this.data.id = postId.toString();
        }
        // Update post
        else
        {
            await this.store.updatePost(this.data);
        }
    }

    // Edits the post
    editPost() 
    {
        this.data.isEdit = true;
    }

    deletePost()
    {
        this.store.deletePost(this.data);
    }

    changeContentVisibility()
    {
        this.data.collapseContent = !this.data.collapseContent;
    }


    getTimeStr(timeStr : string) : string
    {
        let date = new Date(timeStr);
        let today = new Date();
        let months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

        return `${date.getDate()} ${months[date.getMonth()]} ${(date.getFullYear() === today.getFullYear())? '' : date.getFullYear()}`;
    }


    findTags()
    {
        // No content
        if (!this.data.content)
        {
            return;
        }

        // Find tokens
        let matches : RegExpMatchArray | null = this.data.content.match(/(#[a-z\d-]+)/g);

        // Remove previous tags
        this.data.tags?.splice(0, this.data.tags.length);

        // Add matches
        if (matches !== null)
        {
            this.data.tags = matches;
        }
    }


    htmlContent()
    {
        // No content
        if (!this.data.content)
        {
            return;
        }

        return this.data.content.replaceAll(/(#[a-z\d-]+)/g, "<a>$&</a>");
    }
}
</script>


<style>
@import 'bulma/css/bulma.css';

.card.post, .card.post-edit 
{
    width: 100%;
    margin-bottom: 2em;
}

.post-edit textarea 
{
    resize: none;
}

.post-edit .field 
{
    width: 100%;
}

.post-edit 
{
    animation: post-create 0.2s ease-out;
}

.card.post .card-header-title .username
{
    font-weight: bold;
}

.card.post .card-header-title
{
    color: #6d7575;
    font-weight: normal;
}

.card.post .period
{
    font-size: 0.3em;
    width: 0.3em;
    margin: 0 1.5em;
}

.post 
{
    animation: post-bulge 0.3s ease-out;
}

.post .card-content
{
    transition: all 0.2s ease-out;
    overflow: hidden;
}

.card.post .post-title
{
    color: black;
    font-weight: 600;
    font-size: 1.25em;
}

.post .post-text
{
    text-overflow: ellipsis;    
    overflow: hidden;
}

.post .card-content.collapse 
{
    padding: 0;
    max-height: 0;
    opacity: 0;
}

@keyframes post-create 
{
    0% { transform: translateY(4em); opacity: 0; }
    75% { transform: translateY(-0.5em); opacity: 1;}
    100% { transform: translateY(0); }
}

@keyframes post-bulge 
{
    0% { transform: scale(1); opacity: 0.25; }
    50% { transform: scale(1.15);}
    100% { transform: scale(1); opacity: 1;}
}

</style>