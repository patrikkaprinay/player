<template>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <h2 class="mt-3">Search</h2>
        <input
            type="text"
            style="height: 30px; width: 30%; margin-bottom: 30px"
            placeholder="Search for a song"
        />
        <div class="w-50">
            <div
                v-for="song in songs"
                :key="song.id"
                class="bg-success w-100 mb-3 py-3 text-center"
                @click="addToQueue(song.id)"
            >
                <img
                    :src="song.album[0].artwork_path"
                    style="width: 30px"
                    alt=""
                />
                <p class="mb-0">{{ song.name }}</p>
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
                        alert('Song added to queue')
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

<style lang="scss" scoped></style>
