<template>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <h2>Queue</h2>
                <button
                    @click="store.dispatch('clearQueue')"
                    class="btn btn-primary"
                >
                    Clear queue
                </button>
            </div>
            <div class="mt-2">
                <div
                    v-for="song in queue"
                    :key="song.id"
                    class="
                        queueSong
                        d-flex
                        justify-content-start
                        align-items-center
                        flex-row
                        mb-2
                    "
                >
                    <img
                        :src="song.song.album[0].artwork_path"
                        style="width: 30px; margin-right: 30px"
                        alt=""
                    />

                    <p class="mb-0">
                        {{ song.song.name }} - {{ song.song.artist[0].name }}
                    </p>
                    <p
                        class="mb-0 ms-auto me-2"
                        @click="removeFromQueue(song.id)"
                        style="cursor: pointer"
                    >
                        x
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { computed } from 'vue'
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        const queue = computed(() => store.state.queue)

        const removeFromQueue = (id) => {
            axios
                .post('/api/queue/remove', {
                    id: id,
                })
                .then((response) => {
                    console.log(response)
                    store.commit('getQueue')
                })
        }

        return {
            store,
            queue,
            removeFromQueue,
        }
    },
}
</script>

<style scoped>
.queueSong {
    padding: 10px;
    background: rgb(219, 219, 219);
}
</style>
