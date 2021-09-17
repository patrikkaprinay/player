<template>
    <div class="mb-3 position-relative">
        <label for="exampleInputPassword1" class="form-label">Album</label>
        <input
            type="text"
            @keyup="searchForArtist"
            v-model="album"
            class="form-control"
        />
        <div class="searchResults">
            <div
                v-for="album in searchResult"
                :key="album.id"
                class="
                    d-flex
                    justify-content-start
                    align-items-center
                    searchAlbum
                "
                @click="
                    selectAlbum(album.name, album.id, album.artist[0].id),
                        $emit('selectedAlbum', {
                            album: albumid,
                            artist: artistid,
                        })
                "
            >
                <img
                    :src="album.artwork_path"
                    style="width: 50px; margin-right: 10px"
                    alt=""
                />
                <div>
                    <p class="mb-0">
                        {{ album.name }}
                    </p>
                    <p class="byWho mb-0">(By: {{ album.artist[0].name }})</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'

export default {
    emits: ['selectedAlbum'],
    setup() {
        const state = reactive({
            album: '',
            searchResult: '',
            albumid: null,
            artistid: null,
        })

        const searchForArtist = () => {
            axios
                .post('/api/albums/search', {
                    album: state.album,
                })
                .then((response) => (state.searchResult = response.data))
            state.albumid = null
        }

        const selectAlbum = (albumName, albumId, artistId) => {
            state.searchResult = ''
            state.album = albumName
            state.albumid = albumId
            state.artistid = artistId
        }

        return {
            ...toRefs(state),
            searchForArtist,
            selectAlbum,
        }
    },
}
</script>

<style scoped>
.searchAlbum {
    padding: 10px 15px;
    background: #444;
}

.searchAlbum:hover {
    cursor: pointer;
    background: #333;
}

.searchResults {
    position: absolute;
    z-index: 2;
    top: 100%;
    right: 0;
    width: 100%;
}

.byWho {
    font-size: 0.8em;
    color: grey;
}
</style>
