<template>
    <div class="d-flex justify-content-center align-items-center flex-column">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mt-2">
                <h2>Queue</h2>
                <div>
                    <router-link
                        to="/queue-settings"
                        href="#"
                        class="me-4"
                        style="color: black"
                    >
                        <i class="bi bi-gear-fill" style="font-size: 18px"></i>
                    </router-link>
                    <button
                        @click="store.dispatch('clearQueue')"
                        class="btn btn-primary"
                    >
                        Clear queue
                    </button>
                </div>
            </div>
            <div class="mt-2">
                <h3>Currently Playing</h3>
                <div
                    v-if="store.state.currentlyPlaying"
                    class="
                        queueSong
                        d-flex
                        justify-content-start
                        align-items-center
                        flex-row
                        mb-2
                    "
                    style="border-radius: 10px"
                >
                    <img
                        :src="store.state.currentlyPlaying.albumcover"
                        style="width: 30px; margin-right: 30px"
                        alt=""
                    />

                    <p class="mb-0">
                        {{ store.state.currentlyPlaying.name }} -
                        {{ store.state.currentlyPlaying.artist }}
                    </p>
                </div>
                <div class="mt-4" v-if="queue.length > 1">
                    <h3>In Queue</h3>
                </div>
                <div v-for="song in queue" :key="song.id">
                    <div
                        v-if="song != queue[0]"
                        class="
                            queueSong
                            d-flex
                            justify-content-start
                            align-items-center
                            flex-row
                            mb-2
                        "
                        style="border-radius: 10px"
                    >
                        <img
                            :src="song.song.album[0].artwork_path"
                            style="width: 30px; margin-right: 30px"
                            alt=""
                        />

                        <p class="mb-0">
                            {{ song.song.name }} -
                            {{ song.song.artist[0].name }}
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
