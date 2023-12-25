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
    <Post v-else :data="data">
        <footer class="card-footer">
            <a href="#" class="card-footer-item" @click="editPost">Edit</a>
            <a href="#" class="card-footer-item" @click="deletePost">Delete</a>
        </footer>
    </Post>
</template>

<script lang="ts">

import { Vue, Component, Ref, Prop } from 'vue-facing-decorator';
import { DefinePost } from '../types/post';
import { usePostStore } from "../store/postStore"
import { useUserStore } from '../store/userStore';
import Post from './post.vue';

@Component({ components: { Post } })
export default class UserPost extends Vue
{
    
    @Ref('formElement')
    form!: HTMLFormElement;
    
    @Ref('titleInput')
    title!: HTMLInputElement;
    
    store = usePostStore();
    user = useUserStore();
    
    @Prop({ default: { id: 'invalid', isEdit: true } }) data! : DefinePost;

    mounted()
    {
        // Focus on the title if is editing
        if (this.data.isEdit)
        {
            this.title.focus();
        }

        this.data.posterName = this.user.name;
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

    getTimeStr(timeStr : string) : string
    {
        let date = new Date(timeStr);
        let today = new Date();
        let months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

        return `${ date.getDate() } ${ months[date.getMonth()]} ${(date.getFullYear() === today.getFullYear())? '' : date.getFullYear() }`;
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

        // Remove duplicates
        let uniqMatches = Array.from(new Set(matches));

        // Add matches
        if (matches !== null)
        {
            this.data.tags = uniqMatches.map((value : string) => {
                return value.slice(1);
            });
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

.card.post-edit 
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



</style>