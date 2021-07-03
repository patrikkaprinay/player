<template>
    <div class="mb-3 position-relative">
        <label for="exampleInputPassword1" class="form-label">Artist</label>
        <input
            type="text"
            @keyup="searchForArtist"
            v-model="artist"
            class="form-control"
            v-bind:class="{ outline_red: error.ele == 'artist' }"
        />
        <div class="searchResults">
            <div
                v-for="artist in searchResult"
                :key="artist.id"
                class="
                    d-flex
                    justify-content-start
                    align-items-center
                    searchArtist
                "
                @click="
                    selectArtist(artist.name, artist.id),
                        $emit('selectedArtist', artistid)
                "
            >
                <img
                    :src="artist.image_path"
                    style="width: 50px; margin-right: 10px"
                    alt=""
                />
                <p class="mb-0">
                    {{ artist.name }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'

export default {
    emits: ['selectedArtist'],
    props: {
        error: {
            type: Object,
        },
    },
    setup() {
        const state = reactive({
            artist: '',
            searchResult: '',
            artistid: null,
        })

        const searchForArtist = () => {
            axios
                .post('/api/artists/search', {
                    artist: state.artist,
                })
                .then((response) => (state.searchResult = response.data))
            state.artistid = null
        }

        const selectArtist = (artistName, artistId) => {
            state.searchResult = ''
            state.artist = artistName
            state.artistid = artistId
        }

        return {
            ...toRefs(state),
            searchForArtist,
            selectArtist,
        }
    },
}
</script>

<style scoped>
.searchArtist {
    padding: 10px 15px;
    background: rgb(240, 240, 240);
}

.searchArtist:hover {
    cursor: pointer;
    background: rgb(214, 214, 214);
}

.searchResults {
    position: absolute;
    z-index: 3;
    top: 100%;
    right: 0;
    width: 100%;
}
.outline_red {
    outline: 2px solid red !important;
}
</style>
