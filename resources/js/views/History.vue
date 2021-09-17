<template>
    <div class="container">
        <div
            style="
                margin-bottom: 120px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            "
        >
            <h2 class="my-3 w-100">History</h2>
            <div v-for="song in songs" :key="song.id" class="queueSong">
                <Song :song="song.song" :when="song.when" />
            </div>
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

        axios.get('/api/history/songs').then((response) => {
            store.songs = response.data
        })
        return {
            ...toRefs(store),
        }
    },
}
</script>

<style lang="scss" scoped></style>
