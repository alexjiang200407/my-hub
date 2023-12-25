<template>
    <!-- Post display -->
    <div class="card post">
        <header class="card-header">
            <div class="card-header-title">
                <div class="rows">
                    <p class="row is-full post-title">
                        {{data.title}}
                    </p>
    
                    <div class="row is-full">
                        <time>{{ getTimeStr(data.timestamp as string) }}</time>
                        <a class="username" href="#">@{{ data.posterName }}</a>
                    </div>
                </div>
            </div>
    
        </header>
        <div class="card-content">
            <div class="content">
                <div class="rows">
                    <div class="post-text" v-html="htmlContent()" />
                </div>
            </div>
        </div>

        <!-- Footer -->
        <slot></slot>
    </div>
</template>

<script lang="ts">
import { Vue, Prop, Component } from 'vue-facing-decorator';
import { DefinePost } from '../types/post';

@Component
export default class Post extends Vue
{
    @Prop({ default: { id: 'invalid', isEdit: true } }) data! : DefinePost;

    getTimeStr(timeStr : string) : string
    {
        let date = new Date(timeStr);
        let today = new Date();
        let months = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];

        return `${date.getDate()} ${months[date.getMonth()]} ${(date.getFullYear() === today.getFullYear())? '' : date.getFullYear()}`;
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

.card.post
{
    width: 100%;
    margin-bottom: 2em;
}

.card.post .card-header-title .username
{
    font-weight: bold;
}

.card.post .card-header-title
{
    color: #6d7575;
    font-weight: normal;
    max-width: 100%;
}

.card.post .card-header
{
    max-width: 100%;
}

.card.post .card-header-title .rows
{
    width: 100%;
}

.card.post .card-header-title .rows .row
{
    overflow: hidden;
    text-overflow: ellipsis;
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