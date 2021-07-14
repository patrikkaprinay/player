<template>
    <div class="player">
        <div class="d-flex justify-content-start align-items-center w-25">
            <img
                :src="store.state.currentlyPlaying.albumcover"
                style="width: 60px"
                alt=""
            />
            <div class="ms-2">
                <p class="mb-0">{{ store.state.currentlyPlaying.name }}</p>
                <p class="mb-0">{{ store.state.currentlyPlaying.artist }}</p>
            </div>
        </div>
        <div style="width: 50%; padding: 15px 20px 0 20px">
            <div
                class="top-bar d-flex justify-content-center align-items-center"
            >
                <p class="mb-0 me-2">
                    {{ store.state.currentlyPlaying.currentTime }}
                </p>
                <input
                    type="range"
                    style="width: 100%"
                    value="0"
                    id="playerSlider"
                    @change="moveProgress"
                />
                <p class="mb-0 ms-2">
                    {{ store.state.currentlyPlaying.length }}
                </p>
            </div>
            <div
                class="
                    bottom-bar
                    d-flex
                    justify-content-center
                    align-items-center
                "
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
                <i
                    class="bi bi-skip-forward-fill music-controller"

                    @click="skipSong"

                ></i>
            </div>
        </div>
        <div
            class="
                d-flex
                justify-content-center
                align-items-end
                flex-column
                w-25
            "
        >
            <div class="d-flex justify-content-center align-items-center">
                <i
                    class="bi bi-volume-up me-1"
                    v-if="!store.state.muted"
                    style="font-size: 22px"
                    @click="store.commit('mute')"
                ></i>
                <i
                    class="bi bi-volume-mute me-1"
                    v-if="store.state.muted"
                    @click="store.commit('unMute')"
                    style="font-size: 22px"
                ></i>
                <input
                    type="range"
                    style="width: 80px"
                    @change="changeVolume"
                    id="volume"
                />
            </div>
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

        const changeVolume = () => {
            const value = document.querySelector('#volume').value / 100
            document.cookie = 'volume=' + value + '; path=/; SameSite=Lax;'
            console.log(value)
            store.commit('changeVolume', value)
        }

        function moveProgress() {
            const clickX = document.querySelector('#playerSlider').value
            const duration = store.state.player.duration
            const newTime = (duration / 100) * clickX
            store.state.player.currentTime = newTime
        }

        store.state.player.addEventListener('timeupdate', (e) => {
            const { duration, currentTime } = e.srcElement
            const progressPercent = (currentTime / duration) * 100
            document.querySelector('#playerSlider').value = progressPercent
            store.state.currentlyPlaying.currentTime = sToTime(currentTime)
            store.state.currentlyPlaying.length = sToTime(duration)
        })


        store.state.player.addEventListener('ended', () => {
            skipSong()
        })

        const skipSong = () => {
            axios
                .post('/api/queue/next', {
                    played: store.state.currentlyPlaying.queueId,
                })
                .then((response) => {
                    console.log(response)
                    setTimeout(() => {
                        store.dispatch('firstQueueSong')
                        store.dispatch('getToQueue')
                    }, 200)
                    setTimeout(() => {
                        console.log(store.state.player.src)
                        store.commit('playMusic')
                    }, 300)
                })
            console.log('fasz')
        }

        function sToTime(t) {
            return (
                padZero(parseInt((t / 60) % 60)) +
                ':' +
                padZero(parseInt(t % 60))
            )
        }

        function padZero(v) {
            return v < 10 ? '0' + v : v
        }

        return {
            ...toRefs(state),
            store,
            play,
            stop,
            moveProgress,
            skipSong,

        }
    },
}
</script>

<style scoped>
.player {
    /* delete this */
    /* display: none; */
    padding: 0 15px;
    background: rgb(228, 163, 233);
    border-radius: 10px;
    margin-bottom: 10px;
    width: 95%;
    display: flex;
    justify-content: space-between;
    align-items: center;
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
