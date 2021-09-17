<template>
    <div id="mainDiv">
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
.white-color {
    color: #dbdbdb;
}

.white-color-darker {
    color: #c7c7c7;
}

.white-onhover:hover {
    color: #9c9c9c;
}

.no-underline {
    text-decoration: none;
}

body,
html {
    margin: 0;
    height: 100%;
    color: white;
}
a {
    color: white;
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

#mainDiv {
    position: relative;
    overflow-x: hidden;
    height: 100%;
    background: #171a1d;
}

#app {
    height: 100%;
}
</style>
