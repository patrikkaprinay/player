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
                <router-link to="/" class="navbar-brand" href="#"
                    >Player</router-link
                >
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
                            <router-link
                                to="/"
                                class="nav-link active"
                                aria-current="page"
                                href="#"
                                >Home</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link to="/search" class="nav-link" href="#"
                                >Search</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link to="/queue" class="nav-link" href="#"
                                >Queue</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link to="/artists" class="nav-link" href="#"
                                >Artists</router-link
                            >
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li
                            class="nav-item dropdown"
                            v-if="store.state.username"
                        >
                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                id="navbarDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                {{ store.state.username }}
                            </a>
                            <ul
                                class="dropdown-menu dropdown-menu-end"
                                aria-labelledby="navbarDropdown"
                            >
                                <li>
                                    <a class="dropdown-item" href="#"
                                        >Settings</a
                                    >
                                </li>
                                <li>
                                    <router-link
                                        to="/add/song"
                                        class="dropdown-item"
                                        href="#"
                                        >Add</router-link
                                    >
                                </li>
                                <li v-if="!store.state.loggedin">
                                    <router-link
                                        class="dropdown-item"
                                        href="#"
                                        to="/login"
                                        >Login</router-link
                                    >
                                </li>
                                <li v-if="store.state.loggedin">
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                        @click="store.commit('logout')"
                                        >Logout</a
                                    >
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item" v-if="!store.state.username">
                            <router-link class="nav-link" to="/login"
                                >Login</router-link
                            >
                        </li>
                        <!-- <li class="nav-item" v-if="store.state.loggedin">
                            <a
                                class="nav-link"
                                href="#"
                                @click="store.commit('logout')"
                                >Logout</a
                            >
                        </li> -->
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
