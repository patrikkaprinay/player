<template>
    <div>
        <p>Home</p>
        <p v-if="store.state.loggedin">You are logged in</p>
    </div>
</template>

<script>
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        const getUsers = () => {
            axios.get('/api/loggedin').then((response) => console.log(response))
        }
        return {
            getUsers,
            store,
        }
    },
}
</script>

<style lang="scss" scoped></style>
