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
        <div
            class="d-flex justify-content-center align-items-center flex-column"
        >
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="email" class="form-control" />
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"
                    >Artist</label
                >
                <input type="password" class="form-control" />
            </div>
            <button class="btn btn-primary">Submit</button>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'

export default {
    setup() {
        const state = reactive({
            name: '',
            artist: '',
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
