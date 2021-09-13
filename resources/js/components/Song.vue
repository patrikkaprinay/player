<template>
    <div
        class="songComplication me-2"
        v-if="
            store.state.currentlyPlaying.id != song.id || !store.state.playing
        "
        @click="playNow(song.id)"
    >
        <i class="bi bi-play" style="font-size: 22px"></i>
    </div>
    <div
        class="songComplication me-2"
        v-if="store.state.currentlyPlaying.id == song.id && store.state.playing"
        @click="store.commit('stopMusic')"
    >
        <i class="bi bi-pause" style="font-size: 22px"></i>
    </div>
    <img
        :src="song.album.artwork_path"
        style="width: 65px; margin-right: 30px"
        alt=""
    />
    <div class="me-3">
        <p class="mb-0" style="font-size: 22px">{{ song.name }}</p>
        <router-link
            :to="`/artist/` + nice(song.artist.name)"
            class="mb-0"
            style="color: black; text-decoration: none"
            >{{ song.artist.name }}</router-link
        >
    </div>
    <div class="ms-auto me-3 d-flex justify-content-center align-items-center">
        <div class="songComplication me-1" @click="addToQueue(song.id)">
            <i class="bi bi-list-nested songComplicationIcon"></i>
        </div>
        <div class="songComplication" @click="likeSong(song.id)">
            <i class="bi bi-heart songComplicationIcon"></i>
            <i class="bi bi-heart-fill d-none"></i>
        </div>
    </div>
</template>

<script>
import { useStore } from 'vuex'

export default {
    props: ['song'],
    setup() {
        const store = useStore()

        const addToQueue = (song) => {
            let notificationText = 'Added to queue'
            axios
                .post('/api/queue/add', {
                    song: song,
                })
                .then((response) => {
                    if (response.data.message) {
                        console.log(response.data.message)
                        notificationText = response.data.message
                    }
                    store.dispatch('getToQueue')
                    store.dispatch('newNotification', {
                        text: notificationText,
                        status: 0,
                    })
                    store.dispatch('notify')
                })
        }

        const playNow = (id) => {
            if (id == store.state.currentlyPlaying.id) {
                store.commit('playMusic')
            } else {
                axios.post('/api/queue/now', { id: id }).then((response) => {
                    store.dispatch('firstQueueSong')
                    setTimeout(() => {
                        store.commit('playMusic')
                        console.log('playing song')
                    }, 100)
                })
            }
        }

        const nice = (name) => {
            return name
                .normalize('NFD')
                .replace(/\p{Diacritic}/gu, '')
                .toLowerCase()
                .replace(' ', '-')
        }

        const likeSong = (id) => {
            axios
                .post('/api/songs/liked', {
                    id: id,
                })
                .then((response) => {
                    console.log(response)
                })
        }

        return {
            store,
            addToQueue,
            playNow,
            nice,
            likeSong,
        }
    },
}
</script>

<style scoped>
.songComplication {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    transition-duration: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
}

.songComplication:hover {
    background: #dbdbdb;
    cursor: pointer;
}

.songComplicationIcon {
    width: 16px;
    height: 20px;
}
</style>
