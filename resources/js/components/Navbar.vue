<template>
    <div class="navbar-container">
        <div
            class="navbar"
            style="display: flex; justify-content: space-between; width: 30%"
        >
            <router-link to="/" href="#">Home</router-link>
            <router-link to="/search" href="#">Search</router-link>
            <router-link v-if="!loggedin" to="/login" href="#"
                >Login</router-link
            >
            <a href="#" v-if="loggedin" @click="logOut">Log out</a>
        </div>
    </div>
</template>

<script>
import { computed } from '@vue/runtime-core'
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        const loggedin = computed(() => store.state.loggedin)

        const logOut = () => {
            store.commit('logout')
        }

        return {
            logOut,
            store,
            loggedin,
        }
    },
}
</script>

<style scoped>
.navbar-container {
    background: rgb(197, 118, 197);
    padding: 20px 0;
    display: flex;
    justify-content: center;
}

a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}
</style>
