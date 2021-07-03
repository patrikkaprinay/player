<template>
    <div class="player">
        <div class="top-bar d-flex justify-content-center align-items-center">
            <p class="mb-0 me-2">0:00</p>
            <input type="range" style="width: 100%" value="0" />
            <p class="mb-0 ms-2">3:21</p>
        </div>
        <div
            class="bottom-bar d-flex justify-content-center align-items-center"
        >
            <i class="bi bi-skip-backward-fill music-controller"></i>
            <i
                class="bi bi-play-fill music-controller"
                style="font-size: 30px"
                v-if="!store.state.playing"
                @click="play"
            ></i>
            <i
                class="bi bi-pause-fill music-controller"
                style="font-size: 30px"
                v-if="store.state.playing"
                @click="stop"
            ></i>
            <i class="bi bi-skip-forward-fill music-controller"></i>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import { useStore } from 'vuex'

export default {
    setup() {
        const state = reactive({
            playing: false,
        })

        const store = useStore()

        const play = () => {
            store.state.playing = true
            store.commit('playMusic')
        }

        const stop = () => {
            store.state.playing = false
            store.commit('stopMusic')
        }

        return {
            ...toRefs(state),
            store,
            play,
            stop,
        }
    },
}
</script>

<style scoped>
.player {
    /* delete this */
    display: none;
    padding: 15px 20px 0 20px;
    background: burlywood;
    border-radius: 10px;
    margin-bottom: 10px;
    width: 50%;
}

.music-controller {
    font-size: 20px;
}

.music-controller:not(:last-child) {
    margin-right: 10px;
}

@media (max-width: 500px) {
    .player {
        width: 90%;
    }
}
</style>
