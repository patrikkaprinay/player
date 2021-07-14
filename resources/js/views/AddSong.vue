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
            <div class="my-3">
                <input type="file" accept=".mp3,audio/*" id="song" />
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input
                    type="email"
                    v-model="name"
                    class="form-control"
                    :class="{ outline_red: error.ele == 'name' }"
                />
            </div>
            <AlbumBox @selectedAlbum="selectAlbum" />
            <p class="text-danger">{{ error.text }}</p>
            <button class="btn btn-primary" @click="submitForm">Submit</button>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import AlbumBox from '../components/AlbumBox.vue'

export default {
    components: {
        AlbumBox,
    },
    setup() {
        const state = reactive({
            name: '',
            artistid: null,
            albumid: null,
            error: {
                ele: '',
                text: '',
            },
        })

        const selectAlbum = (res) => {
            state.albumid = res.album
            state.artistid = res.artist
        }

        const submitForm = () => {
            if (!state.name) {
                state.error.text = "The name field can't be empty"
                state.error.ele = 'name'
                return
            } else if (!state.artistid) {
                state.error.text = 'Please select an album'
                state.error.ele = 'artist'
                return
            }

            let formData = new FormData()
            let song = document.querySelector('#song')
            console.log(song.files[0])
            formData.append('song', song.files[0])
            formData.append('name', state.name)
            formData.append('artist', state.artistid)
            formData.append('album', state.albumid)
            console.log(formData)
            axios
                .post('/api/add/song', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((response) => console.log(response))
        }

        return {
            ...toRefs(state),
            selectAlbum,
            submitForm,
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

.outline_red {
    outline: 2px solid red !important;
}
</style>
