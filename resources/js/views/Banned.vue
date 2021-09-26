<template>
    <div class="container">
        <h2 class="my-3">Banned tags</h2>
        <div>
            <div v-for="(songArray, index) in songs" :key="songArray.id">
                <p
                    v-if="songArray.length > 0"
                    style="text-transform: uppercase; font-size: 22px"
                >
                    {{ index }}
                </p>
                <div
                    v-for="oneSong in songArray"
                    :key="oneSong.id"
                    class="queueSong"
                >
                    <Song :song="oneSong" />
                </div>
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

        const getBannedSongs = () => {
            axios
                .get('/api/songs/banned')
                .then((response) => (state.songs = response.data))
        }

        onMounted(() => {
            getBannedSongs()
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
