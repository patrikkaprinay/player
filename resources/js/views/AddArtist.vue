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
        <h2>Add new artist</h2>
        <div
            class="
                d-flex
                justify-content-center
                align-items-center
                flex-column
                mt-5
            "
        >
            <div class="my-3">
                <img
                    src=""
                    style="width: 100px; height: 100px"
                    id="previewPfp"
                    @click="$refs.file.click()"
                />
                <input
                    type="file"
                    ref="file"
                    @change="getImgData"
                    src=""
                    id="pfpInput"
                    style="display: none"
                    accept="image/png, image/jpeg"
                />
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input
                    type="text"
                    class="form-control"
                    v-model="name"
                    :class="{ outline_red: error.ele == 'name' }"
                />
            </div>
            <p class="text-danger">{{ error.text }}</p>
            <button class="btn btn-primary px-3" @click="submitForm">
                Add
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { reactive, toRefs } from 'vue'

export default {
    setup() {
        const state = reactive({
            name: '',
            error: {
                ele: '',
                text: '',
            },
        })

        const getImgData = () => {
            const chooseFile = document.querySelector('#pfpInput')
            const files = chooseFile.files[0]
            if (files) {
                const fileReader = new FileReader()
                fileReader.readAsDataURL(files)
                fileReader.addEventListener('load', function () {
                    document.querySelector('#previewPfp').src = this.result
                })
            }
        }

        const submitForm = () => {
            if (!state.name) {
                state.error.text = "The name field can't be empty"
                state.error.ele = 'name'
                return
            }

            let formData = new FormData()
            let imagefile = document.querySelector('#pfpInput')
            formData.append('image', imagefile.files[0])
            formData.append('name', state.name)
            axios
                .post('/api/add/artist', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                })
                .then((response) => console.log(response))
        }

        /*const submitForm = () => {
            axios
                .post('/api/add/artist', {
                    name: state.name,
                    path: state.path,
                })
                .then((response) => console.log(response))
        }*/

        return {
            ...toRefs(state),
            submitForm,
            getImgData,
        }
    },
}
</script>

<style scoped>
.outline_red {
    outline: 2px solid red !important;
}
</style>
