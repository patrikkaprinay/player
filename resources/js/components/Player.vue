<template>
    <div class="player">
        <div class="d-flex justify-content-start align-items-center w-25">
            <img
                :src="store.state.currentlyPlaying.albumcover"
                style="width: 60px"
                alt=""
            />
            <div class="ms-3">
                <p class="mb-0 fs-5">{{ store.state.currentlyPlaying.name }}</p>
                <router-link
                    href="#"
                    :to="`/artist/` + artistnameFormatted"
                    class="mb-0"
                    style="color: #dbdbdb; text-decoration: none"
                    >{{ store.state.currentlyPlaying.artist }}</router-link
                >
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
                <i
                    class="bi bi-skip-backward-fill music-controller"
                    @click="previousSong"
                ></i>
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
        <div class="d-flex justify-content-end align-items-center w-25">
            <router-link to="/queue" href="#">
                <i class="bi bi-list me-3" style="font-size: 20px"></i>
            </router-link>
            <i
                class="bi bi-volume-up me-2"
                style="font-size: 22px"
                v-if="!store.state.muted"
                @click="mute"
            ></i>
            <i
                class="bi bi-volume-mute me-2"
                style="font-size: 22px"
                v-if="store.state.muted"
                @click="unMute"
            ></i>
            <input
                type="range"
                style="width: 100px"
                v-model="volume"
                @change="updateVolume"
            />
            <p>{{ store.state.currentlyPlaying.volume }}</p>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs, onMounted, computed } from 'vue'
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        const state = reactive({
            playing: false,
            volume: getCookie('volume') * 100 * 2,
            muted: false,
            beforeMuted: 0,
        })

        const artistnameFormatted = computed(() =>
            store.state.currentlyPlaying.artist
                .normalize('NFD')
                .replace(/\p{Diacritic}/gu, '')
                .toLowerCase()
                .replace(' ', '-')
        )

        // Original JavaScript code by Chirp Internet: chirpinternet.eu
        // Please acknowledge use of this code by including this header.

        function getCookie(name) {
            var re = new RegExp(name + '=([^;]+)')
            var value = re.exec(document.cookie)
            return value != null ? unescape(value[1]) : null
        }

        const volumeCookie = getCookie('volume')

        onMounted(() => {
            store.commit('setVolume', volumeCookie)
        })

        const play = () => {
            store.state.playing = true
            store.commit('playMusic')
        }

        const stop = () => {
            store.state.playing = false
            store.commit('stopMusic')
        }

        const mute = () => {
            store.commit('mute')
            state.beforeMuted = state.volume
            state.volume = 0
        }

        const unMute = () => {
            store.commit('unMute')
            state.volume = state.beforeMuted
        }

        const updateVolume = () => {
            const newVolume = state.volume / 100
            store.state.player.volume = newVolume
            store.commit('unMute')
            document.cookie = 'volume=' + newVolume + '; SameSite=Lax;'
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
            axios.post('/api/history', {
                id: store.state.currentlyPlaying.id,
                played: store.state.currentlyPlaying.queueId,
            })
            axios
                .post('/api/queue/next', {
                    played: store.state.currentlyPlaying.queueId,
                })
                .then((response) => {
                    if (response.data == '') {
                        console.log('Nincs tobb zene a queue-ben')
                        store.state.player.currentTime = 0
                        store.commit('stopMusic')
                    } else {
                        setTimeout(() => {
                            store.dispatch('firstQueueSong')
                            store.dispatch('getToQueue')
                        }, 200)
                        setTimeout(() => {
                            console.log(store.state.player.src)
                            store.commit('playMusic')
                        }, 300)
                    }
                })
        }

        const previousSong = () => {
            axios.get('/api/history/last').then((response) => {
                console.log(response.data)
                store.commit('updateAudioData', response.data)
                play()
            })
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
            updateVolume,
            unMute,
            mute,
            artistnameFormatted,
            previousSong,
        }
    },
}
</script>

<style scoped>
.player {
    padding: 0 15px;
    background: #212529;
    /* background: rgb(228, 228, 228); */
    border-radius: 10px;
    margin: 10px;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 0 6px #00000034;
}

.music-controller {
    font-size: 20px;
}

.music-controller:not(:last-child) {
    margin-right: 10px;
}

#playerSlider {
    background: transparent;
}

#playerSlider::-moz-range-progress {
    background-color: rgb(121, 121, 121);
}

#playerSlider::-moz-range-track {
    background-color: rgb(216, 216, 216);
}

@media (max-width: 500px) {
    .player {
        width: 90%;
    }
}
</style>
