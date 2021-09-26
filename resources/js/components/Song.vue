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
                text-decoration: none;
                color: #dbdbdb;
            "
            >{{ song.name }}</router-link
        >
        <router-link
            :to="`/artist/` + nice(song.artist.name)"
            class="mb-0"
            style="color: #dbdbdb; text-decoration: none"
            >{{ song.artist.name }}
        </router-link>
        <div class="tagContainer" v-if="song.tags && !props.showTag">
            <div v-for="(tag, index) in song.tags" :key="tag.id">
                <div v-for="sTag in store.state.tags" :key="sTag.id">
                    <router-link
                        v-if="sTag.id == index && tag"
                        :to="`/tag/` + sTag.name"
                    >
                        <div
                            class="tagCircle"
                            :style="`background: ` + sTag.color"
                            :title="sTag.name"
                        ></div>
                    </router-link>
                </div>
            </div>
        </div>
        <p class="mb-0" style="color: grey" v-if="when">{{ when }}</p>
    </div>
    <div class="ms-auto me-3 d-flex justify-content-center align-items-center">
        <div v-if="song.plays" class="me-2" style="color: 6c6c6c">
            Plays: {{ song.plays }}
        </div>
        <div class="songComplication me-1" @click="addToQueue(song.id)">
            <i class="bi bi-list-nested songComplicationIcon"></i>
        </div>
        <div
            class="songComplication"
            v-if="store.state.username && (liked == null || liked == true)"
            @click="favoriteSong(song.id)"
        >
            <i
                class="bi bi-heart songComplicationIcon"
                v-if="liked == null"
            ></i>
            <i
                class="bi bi-heart-fill"
                style="margin-bottom: -3px"
                v-if="liked == true"
            ></i>
        </div>
        <div
            class="songComplication"
            v-if="liked == false"
            @click="removeDislike(song.id)"
        >
            <i
                class="bi bi-hand-thumbs-down-fill"
                style="font-size: 15px"
                v-if="liked == false"
            ></i>
        </div>
        <div class="songComplication" v-if="store.state.loggedin">
            <div>
                <p
                    class="mb-0"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                    id="dropdownThreeDots"
                >
                    <i class="bi bi-three-dots"></i>
                </p>
                <ul
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuClickableInside"
                >
                    <li
                        v-if="liked != false"
                        class="dropdown-item d-flex align-items-center"
                        @click="dislikeSong(song.id)"
                    >
                        <i
                            class="bi bi-hand-thumbs-down-fill me-2"
                            style="font-size: 15px"
                        ></i>
                        <p class="mb-0">Dislike</p>
                    </li>
                    <li class="dropdown-item" v-if="store.state.role == 1">
                        <div class="btn-group dropstart w-100">
                            <p
                                class="dropdown-toggle m-0"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                data-bs-auto-close="outside"
                                style="width: 100%"
                            >
                                Tags
                            </p>
                            <ul
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuClickableInside"
                            >
                                <li
                                    v-for="tag in store.state.tags"
                                    :key="tag.id"
                                >
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
    props: ['song', 'when', 'showTag'],
    emits: ['updateSongs'],
    setup(props, { emit }) {
        const store = useStore()

        const state = reactive({
            liked: props.song.liked,
        })

        const changeTag = (t, s) => {
            axios
                .post('/api/tag-entry', {
                    songId: s,
                    tagId: t,
                })
                .then((response) => console.log(response))
            emit('updateSongs')
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
            axios
                .post('/api/songs/liked', {
                    id: id,
                })
                .then((response) => {
                    console.log(response)
                })
            state.liked = !state.liked
        }

        const removeDislike = (id) => {
            console.log(id)
            axios
                .post('/api/songs/dislike/remove', {
                    id,
                })
                .then((response) => console.log(response))
            state.liked = null
        }

        const dislikeSong = (id) => {
            document.querySelector('#dropdownThreeDots').click()
            axios
                .post('/api/songs/dislike/add', {
                    songId: id,
                })
                .then((response) => console.log(response))
            state.liked = false
        }

        return {
            ...toRefs(state),
            store,
            addToQueue,
            playNow,
            nice,
            favoriteSong,
            dislikeSong,
            store,
            changeTag,
            props,
            removeDislike,
        }
    },
}
</script>

<style scoped>
.tagCircle {
    margin-top: 5px;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    border: 1px solid rgb(32, 32, 32);
    margin-right: 5px;
}

.tagContainer {
    display: flex;
    align-items: center;
    flex-direction: row;
}

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
    background: #3a3a3a;
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
