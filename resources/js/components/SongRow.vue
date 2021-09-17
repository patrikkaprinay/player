<template>
    <div>
        <div
            style="
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
            "
        >
            <p style="font-size: 25px; margin-bottom: 0">{{ topTitle }}</p>
            <div class="d-flex justify-content-center align-items-center">
                <div class="me-3">
                    {{ margin }}
                    <i
                        class="bi bi-caret-left"
                        style="font-size: 18px; margin-right: 7px"
                        @click="goLeft"
                    ></i>
                    <i
                        class="bi bi-caret-right"
                        style="font-size: 18px"
                        @click="goRight"
                    ></i>
                </div>
                <router-link
                    :to="`/` + seeAll"
                    href="#"
                    class="white-color-darker white-onhover no-underline"
                    >See all
                </router-link>
            </div>
        </div>
        <div class="homeHistory">
            <div
                class="homeHistoryInner"
                :style="`margin-left: -` + margin + `px`"
            >
                <div class="songRow" v-for="song in songs" :key="song.id">
                    <router-link
                        href="#"
                        :to="`/album/` + song.song.album.name"
                        style="text-decoration: none"
                    >
                        <img
                            alt="Album Artwork"
                            style="width: 270px"
                            :src="song.song.album.artwork_path"
                        />
                        <p class="mb-0 h5 mt-1 white-color">
                            {{ song.song.name }}
                        </p>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'

export default {
    props: ['topTitle', 'seeAll', 'songs'],
    setup() {
        const state = reactive({
            margin: 0,
            plus: 600,
        })

        const goLeft = () => {
            if (state.margin != 0) {
                if (state.margin % state.plus != 0) {
                    state.margin -= state.margin % state.plus
                } else {
                    state.margin -= state.plus
                }
            }
        }

        const goRight = () => {
            const width =
                document.querySelectorAll('.homeHistory')[0].offsetWidth

            if (state.margin + state.plus < 2244 - width + state.plus) {
                if (state.margin + state.plus > 2244 - width) {
                    state.margin = 2244 - width
                } else {
                    state.margin = state.margin + state.plus
                }
            }
        }
        return {
            ...toRefs(state),
            goLeft,
            goRight,
        }
    },
}
</script>

<style scoped>
.homeHistory {
    overflow: hidden;
}

.homeHistoryInner {
    width: 2244px;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 12px;
}
</style>
