<template>
    <div class="container">
        <h2 class="my-3">Song Requests</h2>
        <form @submit.prevent="addRequest">
            <div class="d-flex flex-row">
                <div class="w-25 me-3">
                    <input
                        class="form-control"
                        type="text"
                        placeholder="Song Name"
                        required
                        v-model="song.name"
                    />
                </div>
                <div class="w-25">
                    <input
                        class="form-control"
                        type="text"
                        placeholder="Song Artist"
                        required
                        v-model="song.artist"
                    />
                </div>
                <div class="ms-auto">
                    <button class="btn btn-primary">Request Song</button>
                </div>
            </div>
        </form>
        <div style="margin-top: 40px">
            <div v-for="song in songs" :key="song.id" class="requestSong">
                <p class="mb-0">{{ song.name + ` - ` + song.artist }}</p>
                <i
                    class="bi bi-heart"
                    style="font-size: 17px; margin-bottom: -3px"
                    v-if="song.liked == 0"
                    @click="likeRequest(song.id)"
                ></i>
                <i
                    class="bi bi-heart-fill"
                    style="font-size: 17px; margin-bottom: -3px"
                    v-if="song.liked == 1"
                    @click="likeRequest(song.id)"
                ></i>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'

export default {
    setup() {
        const state = reactive({
            song: {
                name: '',
                artist: '',
            },
            songs: [],
        })

        const getRequests = () => {
            axios
                .get('/api/requests')
                .then((response) => (state.songs = response.data))
        }

        const addRequest = () => {
            axios
                .post('/api/request', {
                    name: state.song.name,
                    artist: state.song.artist,
                })
                .then((response) => {
                    let newRequest = response.data
                    newRequest['liked'] = 0
                    state.songs.unshift(newRequest)
                    state.song = ''
                })
        }

        const likeRequest = (id) => {
            axios
                .post('/api/request/like', {
                    id,
                })
                .then(
                    state.songs.forEach((song) => {
                        if (song.id == id) {
                            song.liked = !song.liked
                        }
                    })
                )
        }

        onMounted(() => {
            getRequests()
        })

        return {
            ...toRefs(state),
            addRequest,
            likeRequest,
        }
    },
}
</script>

<style scoped>
.requestSong {
    width: 100%;
    background: rgb(68, 68, 68);
    padding: 8px 15px;
    border-radius: 10px;
    font-size: 1.15em;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
