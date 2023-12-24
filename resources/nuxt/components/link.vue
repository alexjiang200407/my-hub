<template>
    <a v-if="to" :href="to" @click="goToLink"><slot>{{ txt }}</slot></a>
    <a v-else><slot>{{ txt }}</slot></a>
</template>


<script lang="ts">
import { Vue, Component, Prop } from 'vue-facing-decorator';


@Component({})
export default class VueLink extends Vue
{
    @Prop() to! : string;
    @Prop() txt! : string | HTMLElement;

    goToLink(event : Event)
    {
        event.preventDefault();
        this.$router.push(this.to);
    }
}

</script>