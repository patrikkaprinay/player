<template>
    <div class="container">
        <h2 class="my-3">Liked Songs</h2>
        <div class="mx-auto mt-4">
            <div
                v-for="song in liked"
                :key="song.id"
                class="queueSong"
                style="width: 600px; margin-inline: auto"
            >
                <Song :song="song.songId" @updateSongs="getSongs" />
            </div>
            <p v-if="!liked.length">You don't have any liked songs</p>
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
    setup() {
        const state = reactive({
            liked: [],
        })

        const getSongs = () => {
            axios.get('/api/songs/liked').then((response) => {
                state.liked = response.data
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
