<template>
    <div>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <div class="container-fluid">
                <router-link
                    to="/"
                    class="
                        navbar-brand
                        d-flex
                        justify-content-center
                        align-items-center
                        fw-bold
                    "
                    href="#"
                >
                    <Logo />
                </router-link>
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
                                class="nav-link"
                                aria-current="page"
                                href="#"
                                active-class="activeNavbar"
                                >Home</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                to="/search"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >Search</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                to="/artists"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >Artists</router-link
                            >
                        </li>
                        <li class="nav-item" v-if="store.state.username">
                            <router-link
                                to="/liked"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >Liked</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                to="/history"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >History</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                to="/requests"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >Requests</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                to="/banned"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >Banned</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                to="/charts"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >Charts</router-link
                            >
                        </li>
                        <li class="nav-item">
                            <router-link
                                to="/info"
                                class="nav-link"
                                href="#"
                                active-class="activeNavbar"
                                >Info</router-link
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
                                    <router-link
                                        to="/add/song"
                                        class="dropdown-item"
                                        href="#"
                                        >Add</router-link
                                    >
                                </li>
                                <li v-if="store.state.role == 1">
                                    <router-link
                                        to="/queue-settings"
                                        class="dropdown-item"
                                        href="#"
                                        >Queue Settings</router-link
                                    >
                                </li>
                                <li v-if="store.state.role == 1">
                                    <router-link
                                        to="/tags"
                                        class="dropdown-item"
                                        href="#"
                                        >Tags</router-link
                                    >
                                </li>
                                <li v-if="store.state.role == 1">
                                    <router-link
                                        to="/users"
                                        class="dropdown-item"
                                        href="#"
                                        >Users</router-link
                                    >
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#"
                                        >Settings</a
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
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</template>

<script>
import { useStore } from 'vuex'
import Logo from './Logo.vue'

export default {
    components: {
        Logo,
    },
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

<style>
.activeNavbar {
    font-weight: 500;
    color: rgba(255, 255, 255, 0.75) !important;
}
</style>
