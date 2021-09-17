<template>
    <div class="container">
        <h2 class="my-3" style="text-transform: uppercase">
            {{ name }}
            <span style="text-transform: none; color: grey; font-size: 18px"
                >Showing all songs with the '{{ name }}' tag</span
            >
        </h2>
        <div>
            <div v-for="song in songs" :key="song.id" class="queueSong">
                <Song :song="song" showTag="false" />
            </div>
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
        })

        const getSongs = () => {
            axios
                .post('/api/tag/all-songs', {
                    name: props.name,
                })
                .then((response) => (state.songs = response.data))
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
