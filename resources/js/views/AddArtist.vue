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
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" v-model="name" />
            </div>
            <div class="mb-3">
                <label class="form-label">Path</label>
                <input type="text" class="form-control" v-model="path" />
            </div>
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
            path: '',
        })

        const submitForm = () => {
            axios
                .post('/api/add/artist', {
                    name: state.name,
                    path: state.path,
                })
                .then((response) => console.log(response))
        }

        return {
            ...toRefs(state),
            submitForm,
        }
    },
}
</script>

<style lang="scss" scoped></style>
