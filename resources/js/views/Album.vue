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
            <div class="d-flex ms-2 w-100">
                <h1
                    style="
                        font-weight: bold;
                        font-size: 40px;
                        text-transform: uppercase;
                    "
                >
                    {{ album.name }}
                </h1>
            </div>
        </div>
        <div v-if="songs.length != 0">
            <h2 class="artistTitle">Songs</h2>
            <div v-for="song in songs" :key="song.id">
                <div class="queueSong mt-4">
                    <Song :song="song" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import Song from '../components/Song.vue'

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

                    /*
                    axios
                        .get(`/api/artist/` + artistId + `/all`)
                        .then((response) => {
                            state.songs = response.data.songs
                            state.albums = response.data.albums
                        })
                        */
                })
        })
        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
