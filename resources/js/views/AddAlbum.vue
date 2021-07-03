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
                    v-bind:class="{ outline_red: error.ele == 'date' }"
                />
            </div>
            <p class="text-danger text-center">{{ error.text }}</p>
            <button class="btn btn-primary" @click="submitForm">Submit</button>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import SearchBox from '../components/SearchBox.vue'

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

        const selectArtist = (value) => {
            state.artistid = value
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
            } else if (!state.artistid) {
                state.error.text = 'Please select an artist'
                state.error.ele = 'artist'
            } else if (!state.date) {
                state.error.text = 'Please pick a release date'
                state.error.ele = 'date'
            }
        }

        return {
            ...toRefs(state),
            selectArtist,
            submitForm,
            checkDate,
            isError,
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
