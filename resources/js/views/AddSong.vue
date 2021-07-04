<template>
    <div
        class="
            d-flex
            justify-content-center
            align-items-center
            flex-column
            mt-2
        "
    >
        <h2>Add new song</h2>
        <router-link to="artist" href="#">Add new artist</router-link>
        <router-link to="album" href="#">Add new album</router-link>
        <div
            class="d-flex justify-content-center align-items-center flex-column"
        >
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="email" v-model="name" class="form-control" />
            </div>
            <SearchBox @selectedArtist="selectArtist" :error="null" />
            <AlbumBox @selectedAlbum="selectAlbum" />
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import SearchBox from '../components/SearchBox.vue'
import AlbumBox from '../components/AlbumBox.vue'

export default {
    components: {
        SearchBox,
        AlbumBox,
    },
    setup() {
        const state = reactive({
            name: '',
            artistid: null,
            albumid: null,
        })

        const selectArtist = (value) => {
            state.artistid = value
        }

        const selectAlbum = (value) => {
            state.albumid = value
        }

        return {
            ...toRefs(state),
            selectArtist,
            selectAlbum,
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
    top: 100%;
    right: 0;
    width: 100%;
}
</style>
