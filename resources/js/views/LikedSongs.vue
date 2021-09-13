<template>
    <div class="container">
        <h2 class="mt-2">Liked Songs</h2>
        <div class="mx-auto mt-4" style="width: 85%">
            <div v-for="song in liked" :key="song.id" class="queueSong">
                <Song :song="song.songId" />
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import Song from '../components/Song.vue'

export default {
    components: {
        Song,
    },
    setup() {
        const state = reactive({
            liked: [],
        })

        axios.get('/api/songs/liked').then((response) => {
            state.liked = response.data
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
