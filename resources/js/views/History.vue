<template>
    <div class="container my-2">
        <h2>History</h2>
        <div v-for="song in songs" :key="song.id" class="queueSongs">
            <Song :song="song" />
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import Song from '../components/Song.vue'
export default {
    components: { Song },
    setup() {
        const store = reactive({
            songs: [],
        })

        axios.get('/api/history').then((response) => {
            store.songs = response.data
        })
        return {
            ...toRefs(store),
        }
    },
}
</script>

<style lang="scss" scoped></style>
