<template>
    <div class="container">
        <div class="row row-cols-6">
            <div v-for="artist in artists" :key="artist.id" class="col mt-3">
                <router-link
                    :to="`/artist/` + nice(artist.name)"
                    class="
                        d-flex
                        justify-content-center
                        align-items-center
                        flex-column
                    "
                    style="color: black; text-decoration: none"
                    href="#"
                >
                    <img
                        :src="artist.image_path"
                        style="
                            width: 100px;
                            height: 100px;
                            object-fit: cover;
                            border-radius: 50%;
                        "
                        alt=""
                    />
                    <p>{{ artist.name }}</p>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'

export default {
    setup() {
        const state = reactive({
            artists: [],
        })

        onMounted(() => {
            loadArtists()
        })

        const nice = (name) => {
            return name
                .normalize('NFD')
                .replace(/\p{Diacritic}/gu, '')
                .toLowerCase()
                .replace(' ', '-')
        }

        const loadArtists = () => {
            axios.post('/api/artists').then((response) => {
                state.artists = response.data
            })
        }

        return {
            ...toRefs(state),
            nice,
        }
    },
}
</script>

<style lang="scss" scoped></style>
