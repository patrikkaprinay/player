<template>
    <div class="container">
        <div
            class="
                d-flex
                align-items-end
                mx-auto
                pt-5
                pb-4
                px-4
                mb-3
                contentOnSite
            "
        >
            <img
                :src="album.artwork_path"
                class="artistImage"
                style="width: 180px; height: 180px; object-fit: cover"
            />
            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-center
                    ms-2
                    w-100
                "
            >
                <h1
                    style="
                        font-weight: bold;
                        font-size: 40px;
                        text-transform: uppercase;
                    "
                >
                    {{ album.name }}
                </h1>
                <div
                    class="
                        d-flex
                        flex-row
                        justify-content-center
                        align-items-center
                    "
                >
                    <i
                        class="bi bi-heart songComplicationIcon"
                        style="margin-right: 5px"
                        v-if="!album.liked"
                        @click="likeAlbum"
                    ></i>
                    <i
                        class="bi bi-heart-fill"
                        style="margin-right: 5px"
                        v-if="album.liked"
                        @click="likeAlbum"
                    ></i>
                    <div class="songComplication">
                        <p
                            class="mb-0"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                            id="dropdownThreeDots"
                        >
                            <i class="bi bi-three-dots"></i>
                        </p>
                        <ul
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuClickableInside"
                        >
                            <li
                                class="dropdown-item"
                                v-if="store.state.role == 1"
                            >
                                <div class="btn-group dropstart w-100">
                                    <p
                                        class="dropdown-toggle m-0"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        data-bs-auto-close="outside"
                                        style="width: 100%"
                                    >
                                        Tags
                                    </p>
                                    <ul
                                        class="dropdown-menu"
                                        aria-labelledby="dropdownMenuClickableInside"
                                    >
                                        <li
                                            v-for="tag in store.state.tags"
                                            :key="tag.id"
                                        >
                                            <label
                                                class="dropdown-item"
                                                style="
                                                    text-transform: capitalize;
                                                "
                                            >
                                                <input
                                                    type="checkbox"
                                                    class="me-2"
                                                    @click="changeTag(tag.id)"
                                                />
                                                {{ tag.name }}
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="songs.length != 0">
            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-center
                    flex-row
                "
            >
                <h2 class="artistTitle">Songs</h2>
                <button
                    class="
                        btn
                        d-flex
                        justify-content-between
                        align-items-center
                        flex-row
                        rounded-pill
                        shuffle-play
                    "
                    v-if="store.state.role == 1"
                    @click="shuffleAlbum"
                >
                    Shuffle Play<i
                        class="
                            bi bi-play-fill
                            ms-2
                            d-flex
                            justify-content-between
                            align-items-center
                            flex-row
                        "
                    ></i>
                </button>
            </div>
            <div v-for="song in songs" :key="song.id">
                <div class="queueSong mt-4">
                    <Song :song="song" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, onMounted, reactive, toRefs } from 'vue'
import Song from '../components/Song.vue'
import { useStore } from 'vuex'

export default {
    components: {
        Song,
    },
    props: ['name'],
    setup(props) {
        const state = reactive({
            album: [],
            songs: [],
            name: props.name,
        })

        const store = useStore()

        state.name = state.name.replace(/-/g, ' ')
        onMounted(() => {
            axios
                .post('/api/album/getAlbum', {
                    album: state.name,
                })
                .then((response) => {
                    console.log(response)

                    state.album = response.data.album
                    state.songs = response.data.songs
                })
        })

        const shuffleAlbum = () => {
            axios
                .post('/api/album/shuffle', {
                    id: state.album.id,
                })
                .then((response) => console.log(response))
            store.dispatch('skipSong')
        }

        const changeTag = (tId) => {
            axios
                .post('/api/album/tag', {
                    tag: tId,
                    album: state.album.id,
                })
                .then((response) => console.log(response))
        }

        const likeAlbum = () => {
            axios
                .post('/api/album/like', {
                    id: state.album.id,
                })
                .then(() => {
                    state.album.liked = !state.album.liked
                })
        }

        return {
            ...toRefs(state),
            store,
            changeTag,
            likeAlbum,
            shuffleAlbum,
        }
    },
}
</script>

<style scoped>
.songComplication {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    transition-duration: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
}

.songComplication:hover {
    background: #3a3a3a;
    cursor: pointer;
}

.shuffle-play {
    background: #21a54d;
    color: white;
}

.shuffle-play:hover {
    background: #1d9143;
}
</style>
