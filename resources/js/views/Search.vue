<template>
    <div class="container">
        <div
            class="d-flex justify-content-center align-items-center flex-column"
        >
            <h2 class="mt-3 h1">Search</h2>
            <input
                id="searchInput"
                type="text"
                placeholder="Search for a song, artist, album"
                v-model="search"
                @keyup="searchTerm"
            />
            <div v-if="!search">
                <div v-for="song in songs" :key="song.id" class="queueSong">
                    <Song :song="song" @updateSongs="getSongs" />
                </div>
            </div>
        </div>
        <div>
            <div v-if="searchResult.songs && searchResult.songs.length != 0">
                <h2>Songs</h2>
                <div
                    v-for="song in searchResult.songs"
                    :key="song.id"
                    class="queueSong"
                >
                    <Song :song="song" @updateSongs="asd" />
                </div>
            </div>
            <div v-if="searchResult.albums && searchResult.albums.length != 0">
                <h2>Albums</h2>
                <AlbumRow :albums="searchResult.albums" />

                <!-- <div class="albums">
                    <div v-for="album in searchResult.albums" :key="album.id">
                        <router-link
                            :to="`/album/` + nice(album.name)"
                            href="#"
                            style="text-decoration: none"
                        >
                            <img
                                :src="album.artwork_path"
                                alt="Album Artwork"
                                style="width: 250px"
                            />
                            <p class="mb-0 h5 mt-1" style="color: #dbdbdb">
                                {{ album.name }}
                            </p>
                            <p style="font-size: 14px; color: #dbdbdb">
                                {{ albumDate(album.published) }}
                            </p>
                        </router-link>
                    </div>
                </div> -->
            </div>
            <div
                v-if="searchResult.artists && searchResult.artists.length != 0"
            >
                <h2>Artists</h2>
                <div
                    class="
                        d-flex
                        justify-content-start
                        align-items-center
                        flex-row
                    "
                >
                    <div
                        v-for="artist in searchResult.artists"
                        class="me-4"
                        :key="artist.id"
                    >
                        <router-link
                            :to="`/artist/` + nice(artist.name)"
                            class="
                                d-flex
                                justify-content-center
                                align-items-center
                                flex-column
                            "
                            style="color: black; text-decoration: none"
                            href="#"
                        >
                            <img
                                :src="artist.image_path"
                                style="
                                    width: 170px;
                                    height: 170px;
                                    object-fit: cover;
                                    border-radius: 50%;
                                "
                                alt=""
                            />
                            <p
                                style="
                                    color: #dbdbdb;
                                    font-size: 20px;
                                    margin-top: 5px;
                                "
                            >
                                {{ artist.name }}
                            </p>
                        </router-link>
                    </div>
                </div>
            </div>
            <div>
                <p v-if="isSearchEmpty()">
                    It seems like there's no album, artist or track with this
                    name in our database
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs, onMounted } from 'vue'
import { useStore } from 'vuex'
import Song from '../components/Song.vue'
import AlbumRow from '../components/AlbumRow.vue'

export default {
    components: {
        Song,
        AlbumRow,
    },
    setup() {
        const state = reactive({
            songs: [],
            search: '',
            searchResult: [],
        })

        const store = useStore()

        const isSearchEmpty = () => {
            if (state.search != '') {
                return true
            } else {
                return false
            }
        }

        const searchTerm = () => {
            if (state.search == '') {
                state.searchResult = []
            } else {
                axios
                    .post('/api/search', {
                        search: state.search,
                    })
                    .then((response) => (state.searchResult = response.data))
            }
        }

        const getSongs = () => {
            axios.get('/api/songs').then((response) => {
                state.songs = response.data
            })
        }

        const nice = (name) => {
            return name
                .normalize('NFD')
                .replace(/\p{Diacritic}/gu, '')
                .toLowerCase()
                .replace(' ', '-')
        }

        const albumDate = (date) => {
            return date.substring(0, 4)
        }

        const asd = () => {
            console.log('asd')
        }

        onMounted(getSongs)

        return {
            ...toRefs(state),
            getSongs,
            store,
            searchTerm,
            asd,
            nice,
            albumDate,
            isSearchEmpty,
        }
    },
}
</script>

<style>
.queueSong {
    min-width: 600px;
    margin-bottom: 16px;
    padding: 15px 10px;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    background: #444444;
    border-radius: 7px;
    transition-duration: 0.2s;
}

#searchInput {
    width: 370px;
    margin-block: 30px;
    padding: 7px 15px;
    border-radius: 25px;
    background: #121416;
    color: white;
    border: 2px solid rgb(77, 77, 77);
    transition: 0.3s;
}

#searchInput:focus {
    outline: none;
    border: 2px solid rgb(131, 131, 131);
}

.albums {
    display: flex;
    align-items: center;
    flex-direction: row;
    gap: 15px;
}
</style>
