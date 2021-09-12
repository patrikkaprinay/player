<template>
    <div class="container" v-if="isLoaded">
        <div class="d-flex align-items-end w-75 mx-auto pt-5 contentOnSite">
            <img
                :src="artist.image_path"
                class="artistImage"
                style="width: 180px; height: 180px; object-fit: cover"
            />
            <div class="d-flex ms-2 w-100">
                <h1
                    style="
                        font-weight: bold;
                        font-size: 40px;
                        text-transform: uppercase;
                    "
                >
                    {{ artist.name }}
                </h1>
            </div>
        </div>
        <div>
            {{ songs }}
        </div>
    </div>
</template>

<script>
import { computed, onMounted, reactive, toRefs } from 'vue'
import { useRoute } from 'vue-router'

export default {
    setup() {
        const state = reactive({
            artist: [],
            isLoaded: false,
            songs: [],
        })

        const artistImage = computed(() => state.artist.image_path)

        const route = useRoute()

        onMounted(() => {
            let name = route.params.name
            name = name.replace(/-/g, ' ')

            axios
                .post('/api/artist', {
                    artist: name,
                })
                .then((response) => {
                    state.artist = response.data
                    state.isLoaded = true
                    axios
                        .get(`/api/artist/` + response.data.id + `/songs`)
                        .then((response) => {
                            state.songs = response.data
                        })
                })
        })

        return {
            ...toRefs(state),
            route,
            artistImage,
        }
    },
}
</script>

<style scoped>
.artistImage {
    border-radius: 50%;
}

.contentOnSite {
    background: rgb(224, 224, 224);
    padding: 15px;
}
</style>
