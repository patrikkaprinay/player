<template>
    <div class="container">
        <h2 class="mt-2">Liked Songs</h2>
        <div class="mx-auto mt-4" style="width: 85%">
            <div v-for="song in liked" :key="song.id" class="queueSong">
                <Song :song="song.songId" @updateSongs="getSongs" />
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
