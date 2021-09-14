<template>
    <div class="numberOrder" v-if="song.order">
        {{ song.order }}
    </div>
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
        <router-link
            :to="`/album/` + nice(song.album.name)"
            class="mb-0"
            style="
                font-size: 22px;
                display: block;
                color: black;
                text-decoration: none;
            "
            >{{ song.name }}</router-link
        >
        <router-link
            :to="`/artist/` + nice(song.artist.name)"
            class="mb-0"
            style="color: black; text-decoration: none"
            >{{ song.artist.name }}
        </router-link>
        <p class="mb-0" style="color: grey" v-if="when">{{ when }}</p>
    </div>
    <div class="ms-auto me-3 d-flex justify-content-center align-items-center">
        <div class="songComplication me-1" @click="addToQueue(song.id)">
            <i class="bi bi-list-nested songComplicationIcon"></i>
        </div>
        <div
            class="songComplication"
            v-if="store.state.username"
            @click="favoriteSong(song.id)"
        >
            <i class="bi bi-heart songComplicationIcon" v-if="!liked"></i>
            <i
                class="bi bi-heart-fill"
                style="margin-bottom: -3px"
                v-if="liked"
            ></i>
        </div>
        <div class="songComplication" @click="clickDropdown">
            <div>
                <p
                    class="mb-0"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                >
                    <i class="bi bi-three-dots"></i>
                </p>
                <ul
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuClickableInside"
                >
                    <li v-for="tag in store.state.tags" :key="tag.id">
                        <label
                            class="dropdown-item"
                            style="text-transform: capitalize"
                        >
                            <input
                                type="checkbox"
                                class="me-2"
                                :checked="song.tags[tag.id]"
                                @click="changeTag(tag.id, song.id)"
                            />
                            {{ tag.name }}
                        </label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import { useStore } from 'vuex'
import { reactive, toRefs } from 'vue'

export default {
    props: ['song', 'when'],
    setup(props) {
        const store = useStore()

        const state = reactive({
            liked: props.song.liked,
        })

        const changeTag = (t, s) => {
            console.log(t + ` ` + s)
            axios
                .post('/api/tag-entry', {
                    songId: s,
                    tagId: t,
                })
                .then((response) => console.log(response))
        }

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

        const favoriteSong = (id) => {
            console.log('favorite pressed')
            axios
                .post('/api/songs/liked', {
                    id: id,
                })
                .then((response) => {
                    console.log(response)
                })
            state.liked = !state.liked
            console.log('favorite ended')
        }

        return {
            ...toRefs(state),
            store,
            addToQueue,
            playNow,
            nice,
            favoriteSong,
            store,

            changeTag,
        }
    },
}
</script>

<style scoped>
label:hover {
    cursor: pointer;
    user-select: none;
}

.songComplication:not(:last-child) {
    margin-right: 0.25rem;
}

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

.numberOrder {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    transition-duration: 0.3s;
    display: flex;
    justify-content: center;
    align-items: center;
    user-select: none;
}
</style>
