<template>
    <div class="container" style="padding-bottom: 80px">
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
                <div class="d-flex flex-row">
                    <p class="mb-0 me-2" v-if="song.likes > 0">
                        {{ song.likes }}
                    </p>
                    <i
                        class="
                            bi bi-heart
                            d-flex
                            justify-content-center
                            align-items-center
                        "
                        style="font-size: 17px"
                        v-if="song.liked == 0"
                        @click="likeRequest(song.id, 1)"
                    ></i>
                    <i
                        class="
                            bi bi-heart-fill
                            d-flex
                            justify-content-center
                            align-items-center
                        "
                        style="font-size: 17px"
                        v-if="song.liked == 1"
                        @click="likeRequest(song.id, 0)"
                    ></i>
                </div>
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

        const likeRequest = (id, like) => {
            axios
                .post('/api/request/like', {
                    id,
                })
                .then(
                    state.songs.forEach((song) => {
                        if (song.id == id) {
                            song.liked = !song.liked
                            if (like == 0) {
                                song.likes--
                            } else {
                                song.likes++
                            }
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
