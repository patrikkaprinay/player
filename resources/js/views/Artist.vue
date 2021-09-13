<template>
    <div class="container" v-if="isLoaded">
        <div class="d-flex align-items-end w-75 mx-auto pt-5 contentOnSite">
            <img
                :src="artist.image_path"
                class="artistImage"
                style="width: 180px; height: 180px; object-fit: cover"
            />
            <div class="d-flex ms-2 w-100">
                <h1
                    style="
                        font-weight: bold;
                        font-size: 40px;
                        text-transform: uppercase;
                    "
                >
                    {{ artist.name }}
                </h1>
            </div>
        </div>
        <div v-if="songs">
            <!-- {{ songs }} -->
            <h2 class="artistTitle">Songs</h2>
            <div v-for="song in songs" :key="song.id">
                <div class="queueSong mt-4">
                    <Song :song="song" />
                </div>
            </div>
        </div>
        <div v-if="albums" style="margin-top: 50px">
            <h2 class="artistTitle">Albums</h2>
            <div class="albums">
                <div v-for="album in albums" :key="album.id">
                    <router-link to="albumneve" href="#">
                        <img
                            :src="album.artwork_path"
                            alt="Album Artwork"
                            style="width: 250px"
                        />
                        <p
                            class="mb-0 h5 mt-1"
                            style="color: black; text-decoration: none"
                        >
                            {{ album.name }}
                        </p>
                        <p
                            style="
                                font-size: 14px;
                                color: black;
                                text-decoration: none;
                            "
                        >
                            {{ albumDate(album.published) }}
                        </p>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, onMounted, reactive, toRefs } from 'vue'
import { useRoute } from 'vue-router'
import Song from '../components/Song.vue'

export default {
    components: {
        Song,
    },
    setup() {
        const state = reactive({
            artist: [],
            isLoaded: false,
            songs: [],
            albums: [],
        })

        const artistImage = computed(() => state.artist.image_path)

        const route = useRoute()

        onMounted(() => {
            let name = route.params.name
            name = name.replace(/-/g, ' ')

            axios
                .post('/api/artist', {
                    artist: name,
                })
                .then((response) => {
                    let artistId = response.data.id
                    state.artist = response.data
                    state.isLoaded = true

                    axios
                        .get(`/api/artist/` + artistId + `/all`)
                        .then((response) => {
                            state.songs = response.data.songs
                            state.albums = response.data.albums
                        })
                })
        })

        const albumDate = (date) => {
            return date.substring(0, 4)
        }

        return {
            ...toRefs(state),
            route,
            artistImage,
            albumDate,
        }
    },
}
</script>

<style scoped>
.artistImage {
    border-radius: 50%;
}

.contentOnSite {
    background: rgb(224, 224, 224);
    padding: 15px;
}

.artistTitle {
    margin-bottom: 30px;
}

.albums {
    display: grid;
    grid-gap: 1rem;
}

@media (min-width: 600px) {
    .albums {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 992px) {
    .albums {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (min-width: 1200px) {
    .albums {
        grid-template-columns: repeat(4, 1fr);
    }
}

@media (min-width: 1400px) {
    .albums {
        grid-template-columns: repeat(5, 1fr);
    }
}
</style>
