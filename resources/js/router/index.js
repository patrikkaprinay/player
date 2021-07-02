import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Search from '../views/Search.vue'
import Queue from '../views/Queue.vue'
import AddSong from '../views/AddSong.vue'
import AddArtist from '../views/AddArtist.vue'
import Artists from '../views/Artists.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
    },
    {
        path: '/search',
        name: 'Search',
        component: Search,
    },
    {
        path: '/queue',
        name: 'Queue',
        component: Queue,
    },
    {
        path: '/add/song',
        name: 'AddSong',
        component: AddSong,
    },
    {
        path: '/add/artist',
        name: 'AddArtist',
        component: AddArtist,
    },
    {
        path: '/artists',
        name: 'Artists',
        component: Artists,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
