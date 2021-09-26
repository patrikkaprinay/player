<template>
    <div class="container">
        <h2 class="my-3" style="text-transform: uppercase">
            {{ name }}
            <span style="text-transform: none; color: grey; font-size: 18px"
                >Showing all songs with the '{{ name }}' tag</span
            >
        </h2>
        <div v-if="enabled == 0" class="alert alert-danger" role="alert">
            This tag is currently disabled!
        </div>
        <div>
            <div v-for="song in songs" :key="song.id" class="queueSong">
                <Song :song="song" showTag="false" />
            </div>
        </div>
        <div v-if="songs.length == 0">
            <p>There are no songs associated to this tag or it doesn't exist</p>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import Song from '../components/Song.vue'
export default {
    components: { Song },
    props: ['name'],
    setup(props) {
        const state = reactive({
            songs: [],
            enabled: 1,
        })

        const getSongs = () => {
            axios
                .post('/api/tag/all-songs', {
                    name: props.name,
                })
                .then((response) => {
                    state.songs = response.data.songs
                    state.enabled = response.data.enabled
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
