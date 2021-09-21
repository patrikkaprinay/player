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
            <div class="d-flex justify-content-center align-items-center">
                <div class="me-3" v-if="albums.length > 2">
                    <i
                        class="bi bi-caret-left"
                        style="font-size: 18px; margin-right: 7px"
                        @click="goLeft"
                        :style="{ color: showLeft }"
                    ></i>
                    <i
                        class="bi bi-caret-right"
                        style="font-size: 18px"
                        @click="goRight"
                        :style="{ color: showRight }"
                    ></i>
                </div>
            </div>
        </div>
        <div class="homeHistory">
            <div
                class="homeHistoryInner"
                :style="`margin-left: -` + margin + `px`"
            >
                <div class="songRow" v-for="album in albums" :key="album.id">
                    <router-link
                        href="#"
                        :to="`/album/` + album.name"
                        style="text-decoration: none"
                    >
                        <img
                            alt="Album Artwork"
                            style="width: 270px"
                            :src="album.artwork_path"
                        />
                        <p class="mb-0 h5 mt-1 white-color">
                            {{ album.name }}
                        </p>
                        <router-link
                            :to="`/artist/` + nice(album.artist.name)"
                            class="
                                mb-0
                                h5
                                mt-1
                                white-color-darker white-onhover
                            "
                            style="font-size: 18px; text-decoration: none"
                        >
                            {{ album.artist.name }}
                        </router-link>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, reactive, toRefs } from 'vue'

export default {
    props: ['albums'],
    setup(props) {
        const state = reactive({
            margin: 0,
            plus: 800,
            fullWidth: null,
        })

        const showLeft = computed(() => (state.margin != 0 ? '' : 'grey'))

        const showRight = computed(() =>
            state.margin == state.fullWidth ? 'grey' : ''
        )

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

            let fillWidth =
                270 * props.albums.length + 12 * props.albums.length - 1

            if (state.margin + state.plus < fillWidth - width + state.plus) {
                if (state.margin + state.plus > fillWidth - width) {
                    state.fullWidth = fillWidth - width
                    state.margin = state.fullWidth
                } else {
                    state.margin = state.margin + state.plus
                }
            }
        }

        const nice = (name) => {
            return name
                .replace(/\p{Diacritic}/gu, '')
                .toLowerCase()
                .replace(' ', '-')
        }

        return {
            ...toRefs(state),
            goLeft,
            goRight,
            nice,
            showLeft,
            showRight,
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
    transition: margin 500ms;
}
</style>
