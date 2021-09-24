<template>
    <div class="container">
        <h2 class="my-3">Song Requests</h2>
        <div class="d-flex flex-row">
            <div class="w-25 me-3">
                <input
                    class="form-control"
                    type="text"
                    placeholder="Song Name"
                />
            </div>
            <div class="w-25">
                <input
                    class="form-control"
                    type="text"
                    placeholder="Song Artist"
                />
            </div>
            <div class="ms-auto">
                <button class="btn btn-primary">Request New Song</button>
            </div>
        </div>
        <div>
            {{ songs }}
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'

export default {
    setup() {
        const state = reactive({
            songs: [],
        })

        const getRequests = () => {
            axios
                .get('/api/requests')
                .then((response) => (state.songs = response.data))
        }

        onMounted(() => {
            getRequests()
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
