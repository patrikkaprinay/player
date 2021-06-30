<template>
    <div>
        <!-- <div
            class="navbar"
            style="display: flex; justify-content: space-between; width: 30%"
        >
            <router-link to="/" href="#">Home</router-link>
            <router-link to="/search" href="#">Search</router-link>
            <router-link v-if="!store.state.loggedin" to="/login" href="#"
                >Login</router-link
            >
            <a href="#" v-if="store.state.loggedin" @click="logOut">Log out</a>
        </div> -->
        <nav class="navbar navbar-expand-sm navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Player</a>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div
                    class="collapse navbar-collapse"
                    id="navbarSupportedContent"
                >
                    <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                        <li class="nav-item">
                            <a
                                class="nav-link active"
                                aria-current="page"
                                href="#"
                                >Home</a
                            >
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        const logOut = () => {
            store.commit('logout')
        }

        return {
            logOut,
            store,
        }
    },
}
</script>

<style scoped></style>
