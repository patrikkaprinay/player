<template>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <h2 class="mt-3">En vagyok itt a search helo Search</h2>
        <input
            type="text"
            style="height: 30px; width: 30%; margin-bottom: 30px"
            placeholder="Search for a song"
        />
        <div class="w-50">
            <div
                v-for="song in songs"
                :key="song.id"
                class="queueSong"
                @click="addToQueue(song.id)"
            >
                <img
                    :src="song.album.artwork_path"
                    style="width: 65px; margin-right: 30px"
                    alt=""
                />
                <div>
                    <p class="mb-0" style="font-size: 22px">{{ song.name }}</p>
                    <p class="mb-0">{{ song.artist.name }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs, onMounted } from 'vue'
import { useStore } from 'vuex'

export default {
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
            axios
                .post('/api/queue/add', {
                    song: song,
                })
                .then((response) => {
                    if (response.status == 200) {
                        console.log('Song added to queue')
                    }
                })
            store.dispatch('getToQueue')
        }

        onMounted(getSongs)

        return {
            ...toRefs(state),
            getSongs,
            addToQueue,
            store,
        }
    },
}
</script>

<style scoped>
.queueSong {
    width: 100%;
    margin-bottom: 16px;
    padding: 15px;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    background: #cecece;
    border-radius: 15px;
    transition-duration: 0.2s;
    cursor: pointer;
}

.queueSong:hover {
    transform: scale(1.012);
}
</style>
