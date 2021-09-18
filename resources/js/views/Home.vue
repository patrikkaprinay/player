<template>
    <div class="container">
        <p
            style="font-size: 40px"
            class="my-3"
            v-if="store.state.username && store.state.loggedin"
        >
            Welcome, {{ store.state.username }}
        </p>
        <div class="pb-3">
            <SongRow
                class="mb-5"
                topTitle="Recently played"
                seeAll="history"
                :songs="history"
            />
            <SongRow
                class="mb-5"
                topTitle="Your liked songs"
                seeAll="liked"
                :songs="liked"
            />
            <SongRow
                class="mb-5"
                topTitle="Latest songs added"
                seeAll="search"
                :songs="hot"
            />
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import { useStore } from 'vuex'
import SongRow from '../components/SongRow.vue'

export default {
    components: { SongRow },
    setup() {
        const store = useStore()

        const state = reactive({
            history: [],
            liked: [],
            hot: [],
        })

        const getHistory = () => {
            axios.get('/api/history/few-songs').then((response) => {
                state.history = response.data
            })
        }

        const getLiked = () => {
            axios.get('/api/songs/liked/few').then((response) => {
                state.liked = response.data
            })
        }

        const getNew = () => {
            axios.get('/api/songs/few').then((response) => {
                console.log(response)
                state.hot = response.data
            })
        }

        onMounted(() => {
            getHistory()
            getLiked()
            getNew()
        })

        const getUsers = () => {
            axios.get('/api/loggedin').then((response) => console.log(response))
        }

        return {
            ...toRefs(state),
            getUsers,
            store,
        }
    },
}
</script>

<style scoped></style>
