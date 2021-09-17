<template>
    <div class="container">
        <p
            style="font-size: 40px"
            class="my-3"
            v-if="store.state.username && store.state.loggedin"
        >
            Welcome, {{ store.state.username }}
        </p>
        <SongRow topTitle="Recently played" seeAll="history" :songs="history" />
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
        })

        const getHistory = () => {
            axios.get('/api/history/few-songs').then((response) => {
                state.history = response.data
                console.log(response)
            })
        }

        onMounted(() => {
            getHistory()
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
