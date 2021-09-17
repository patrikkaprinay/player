<template>
    <div class="container">
        <p
            style="font-size: 40px"
            class="my-3"
            v-if="store.state.username && store.state.loggedin"
        >
            Welcome, {{ store.state.username }}
        </p>
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

        onMounted(() => {
            getHistory()
            getLiked()
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
