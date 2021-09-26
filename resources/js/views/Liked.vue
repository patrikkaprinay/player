<template>
    <div class="container">
        <h2 class="my-3">Liked Albums</h2>
        <AlbumRow :albums="albums" size="sm" />
        <h2 class="my-3">Liked Songs</h2>
        <div class="mx-auto mt-4">
            <div
                v-for="song in songs"
                :key="song.id"
                class="queueSong"
                style="width: 600px; margin-inline: auto"
            >
                <Song :song="song.songId" @updateSongs="getSongs" />
            </div>
            <p v-if="!songs.length">You don't have any liked songs</p>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import Song from '../components/Song.vue'
import AlbumRow from '../components/AlbumRow.vue'

export default {
    components: {
        Song,
        AlbumRow,
    },
    setup() {
        const state = reactive({
            songs: [],
            albums: [],
        })

        const getSongs = () => {
            axios.get('/api/songs/liked').then((response) => {
                state.songs = response.data.songs
                state.albums = response.data.albums
            })
        }

        onMounted(() => {
            getSongs()
        })

        return {
            ...toRefs(state),
            getSongs,
        }
    },
}
</script>

<style lang="scss" scoped></style>
