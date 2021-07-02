<template>
    <div>
        <Navbar />
        <router-view />
        <div class="player-container">
            <Player />
        </div>
    </div>
</template>

<script>
import Navbar from './components/Navbar.vue'
import Player from './components/Player.vue'

export default {
    components: {
        Navbar,
        Player,
    },
    mounted() {
        this.$store.dispatch('amILoggedin')
        this.$store.dispatch('getToQueue')
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
