<template>
    <div class="container">
        <h2 class="my-3">Charts</h2>
        <div>
            <div v-for="song in songs" :key="song.id" class="queueSong">
                <Song :song="song" />
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import Song from '../components/Song.vue'

export default {
    components: { Song },
    setup() {
        const state = reactive({
            songs: [],
        })

        const getSongs = () => {
            axios.get('/api/songs/charts').then((response) => {
                state.songs = response.data
            })
        }

        onMounted(() => {
            getSongs()
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
