<template>
    <div style="position: relative; overflow-x: hidden">
        <Navbar />
        <Notification />
        <router-view style="margin-bottom: 100px" />
        <div class="player-container">
            <Player />
        </div>
    </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import Player from './components/Player.vue'
import Notification from './components/Notification.vue'

export default {
    components: {
        Navbar,
        Player,
        Notification,
    },
    mounted() {
        this.$store.dispatch('amILoggedin')
        this.$store.dispatch('getToQueue')
        this.$store.dispatch('firstQueueSong')
        this.$store.dispatch('getTags')
    },
    setup() {
        return {}
    },
}
</script>

<style>
body {
    margin: 0;
}
* {
    box-sizing: border-box;
}

.player-container {
    position: fixed;
    display: flex;
    justify-content: center;
    width: 100%;
    bottom: 0;
    right: 0;
}
</style>
