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
        <h2>Add new album</h2>
        <div
            class="d-flex justify-content-center align-items-center flex-column"
            style="max-width: 223px"
        >
            <div class="my-3">
                <img
                    src=""
                    style="width: 100px; height: 100px"
                    id="previewArtwork"
                    @click="$refs.file.click()"
                />
                <input
                    type="file"
                    ref="file"
                    @change="getImgData"
                    src=""
                    id="artworkInput"
                    style="display: none"
                    accept="image/png, image/jpeg"
                />
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input
                    type="text"
                    v-model="name"
                    class="form-control"
                    v-bind:class="{ outline_red: error.ele == 'name' }"
                />
            </div>
            <SearchBox
                @selectedArtist="selectArtist"
                @change="artistid = null"
                :error="error"
            />
            <div class="mb-3 w-100">
                <label for="exampleInputPassword1" class="form-label"
                    >Date Released</label
                >
                <input
                    @change="checkDate"
                    type="date"
                    v-model="date"
                    class="form-control"
                    :class="{ outline_red: error.ele == 'date' }"
                />
            </div>
            <p class="text-danger text-center" @click="showImage($refs.file)">
                {{ error.text }}
            </p>
            <button class="btn btn-primary" @click="submitForm">Submit</button>
        </div>
    </div>
</template>

<script>
import SearchBox from '../components/SearchBox.vue'

import { reactive, toRefs } from 'vue'
import { useStore } from 'vuex'

export default {
    components: {
        SearchBox,
    },
    setup() {
        const state = reactive({
            name: '',
            artistid: null,
            date: '',
            error: {
                ele: '',
                text: '',
            },
        })

        const store = useStore()

        const selectArtist = (value) => {
            state.artistid = value
        }

        const getImgData = () => {
            const chooseFile = document.querySelector('#artworkInput')
            const files = chooseFile.files[0]
            if (files) {
                const fileReader = new FileReader()
                fileReader.readAsDataURL(files)
                fileReader.addEventListener('load', function () {
                    document.querySelector('#previewArtwork').src = this.result
                })
            }
        }

        const checkDate = () => {
            let now = new Date().toJSON().slice(0, 10)
            if (now < state.date) {
                state.error.text = 'The date has to be older than todays date'
                state.error.ele = 'date'
            } else {
                state.error = ''
            }
        }

        const isError = () => {
            if (state.name && state.artistid && state.date) {
                return true
            } else {
                return false
            }
        }

        const submitForm = () => {
            if (!state.name) {
                state.error.text = "The name field can't be empty"
                state.error.ele = 'name'
                return
            } else if (!state.artistid) {
                state.error.text = 'Please select an artist'
                state.error.ele = 'artist'
                return
            } else if (!state.date) {
                state.error.text = 'Please pick a release date'
                state.error.ele = 'date'
                return
            }

            let formData = new FormData()
            let imagefile = document.querySelector('#artworkInput')
            formData.append('image', imagefile.files[0])
            formData.append('name', state.name)
            formData.append('artist', state.artistid)
            formData.append('date', state.date)
            axios
                .post('/api/add/album', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((response) => {
                    if (response.status == 200) {
                        store.dispatch('newNotification', {
                            text: 'Album added',
                            status: 0,
                        })
                        store.dispatch('notify')
                    }
                })
        }

        return {
            ...toRefs(state),
            selectArtist,
            submitForm,
            checkDate,
            isError,
            getImgData,
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
