<template>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <h2 class="mt-3">Search</h2>
        <!-- <input
            type="text"
            style="height: 30px; width: 30%; margin-bottom: 30px"
            placeholder="Search for a song"
        /> -->
        <div class="">
            <div v-for="song in songs" :key="song.id" class="queueSong">
                <Song :song="song" />

                <!-- <div
                    class="songComplication me-2"
                    v-if="
                        store.state.currentlyPlaying.id != song.id ||
                        !store.state.playing
                    "
                    @click="playNow(song.id)"
                >
                    <i class="bi bi-play" style="font-size: 22px"></i>
                </div>
                <div
                    class="songComplication me-2"
                    v-if="
                        store.state.currentlyPlaying.id == song.id &&
                        store.state.playing
                    "
                    @click="store.commit('stopMusic')"
                >
                    <i class="bi bi-pause" style="font-size: 22px"></i>
                </div>
                <img
                    :src="song.album.artwork_path"
                    style="width: 65px; margin-right: 30px"
                    alt=""
                />
                <div class="me-3">
                    <p class="mb-0" style="font-size: 22px">{{ song.name }}</p>
                    <router-link
                        :to="`/artist/` + nice(song.artist.name)"
                        class="mb-0"
                        style="color: black; text-decoration: none"
                        >{{ song.artist.name }}</router-link
                    >
                </div>
                <div
                    class="
                        ms-auto
                        me-3
                        d-flex
                        justify-content-center
                        align-items-center
                    "
                >
                    <div
                        class="songComplication me-1"
                        @click="addToQueue(song.id)"
                    >
                        <i class="bi bi-list-nested songComplicationIcon"></i>
                    </div>
                    <div class="songComplication">
                        <i class="bi bi-heart songComplicationIcon"></i>
                        <i class="bi bi-heart-fill d-none"></i>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs, onMounted } from 'vue'
import { useStore } from 'vuex'
import Song from '../components/Song.vue'

export default {
    components: {
        Song,
    },
    setup() {
        const state = reactive({
            songs: [],
        })

        const store = useStore()

        const getSongs = () => {
            axios.get('/api/songs').then((response) => {
                state.songs = response.data
            })
        }

        const addToQueue = (song) => {
            let notificationText = 'Added to queue'
            axios
                .post('/api/queue/add', {
                    song: song,
                })
                .then((response) => {
                    if (response.data.message) {
                        console.log(response.data.message)
                        notificationText = response.data.message
                    }
                    store.dispatch('getToQueue')
                    store.dispatch('newNotification', {
                        text: notificationText,
                        status: 0,
                    })
                    store.dispatch('notify')
                })
        }

        const playNow = (id) => {
            if (id == store.state.currentlyPlaying.id) {
                store.commit('playMusic')
            } else {
                axios.post('/api/queue/now', { id: id }).then((response) => {
                    store.dispatch('firstQueueSong')
                    setTimeout(() => {
                        store.commit('playMusic')
                    }, 100)
                    console.log(response)
                })
            }
        }

        const nice = (name) => {
            return name
                .normalize('NFD')
                .replace(/\p{Diacritic}/gu, '')
                .toLowerCase()
                .replace(' ', '-')
        }

        onMounted(getSongs)

        return {
            ...toRefs(state),
            getSongs,
            addToQueue,
            store,
            playNow,
            nice,
        }
    },
}
</script>

<style>
.queueSong {
    width: 100%;
    margin-bottom: 16px;
    padding: 15px 10px;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    background: #eaeaea;
    border-radius: 7px;
    transition-duration: 0.2s;
}
</style>
