import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Search from '../views/Search.vue'
import Queue from '../views/Queue.vue'
import AddSong from '../views/AddSong.vue'
import AddArtist from '../views/AddArtist.vue'
import AddAlbum from '../views/AddAlbum.vue'
import Artists from '../views/Artists.vue'
import Artist from '../views/Artist.vue'
import QueueSettings from '../views/QueueSettings.vue'
import Liked from '../views/Liked.vue'
import Album from '../views/Album.vue'
import History from '../views/History.vue'
import Tags from '../views/Tags.vue'
import Tag from '../views/Tag.vue'
import ManageUsers from '../views/ManageUsers.vue'
import Info from '../views/Info.vue'
import Request from '../views/Request.vue'
import Banned from '../views/Banned.vue'
import Charts from '../views/Charts.vue'

import axios from 'axios'

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
        meta: {
            guest: true,
        },
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: {
            guest: true,
        },
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
        meta: {
            is_user: true,
        },
    },
    {
        path: '/add/artist',
        name: 'AddArtist',
        component: AddArtist,
        meta: {
            is_user: true,
        },
    },
    {
        path: '/add/album',
        name: 'AddAlbum',
        component: AddAlbum,
        meta: {
            is_user: true,
        },
    },
    {
        path: '/artists',
        name: 'Artists',
        component: Artists,
    },
    {
        path: '/artist/:name',
        name: 'Artist',
        component: Artist,
    },
    {
        path: '/queue-settings',
        name: 'QueueSettings',
        component: QueueSettings,
    },
    {
        path: '/liked',
        name: 'Liked',
        component: Liked,
        meta: {
            requiresAuth: true,
        },
    },
    {
        path: '/album/:name',
        name: 'Album',
        component: Album,
        props: true,
    },
    {
        path: '/history',
        name: 'History',
        component: History,
    },
    {
        path: '/tags',
        name: 'Tags',
        component: Tags,
        meta: {
            is_admin: true,
        },
    },
    {
        path: '/tag/:name',
        name: 'Tag',
        component: Tag,
        props: true,
    },
    {
        path: '/users',
        name: 'ManageUsers',
        component: ManageUsers,
        meta: {
            is_admin: true,
        },
    },
    {
        path: '/info',
        name: 'Info',
        component: Info,
    },
    {
        path: '/requests',
        name: 'Request',
        component: Request,
    },
    {
        path: '/banned',
        name: 'Banned',
        component: Banned,
    },
    {
        path: '/charts',
        name: 'Charts',
        component: Charts,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.is_user)) {
        axios.get('/api/loggedin').then((response) => {
            if (response && response.data.role <= 3) {
                next()
            } else {
                next({
                    path: '/login',
                    params: { nextUrl: to.fullPath },
                })
            }
        })
    } else if (to.matched.some((record) => record.meta.is_admin)) {
        axios.get('/api/loggedin').then((response) => {
            if (response && response.data.role == 1) {
                next()
            } else {
                next({
                    path: '/login',
                    params: { nextUrl: to.fullPath },
                })
            }
        })
    } else if (to.matched.some((record) => record.meta.requiresAuth)) {
        axios.get('/api/loggedin').then((response) => {
            if (!response.data) {
                next({
                    path: '/login',
                    params: { nextUrl: to.fullPath },
                })
            } else {
                next()
            }
        })
    } else if (to.matched.some((record) => record.meta.guest)) {
        axios.get('/api/loggedin').then((response) => {
            if (!response.data) {
                next()
            } else {
                next({
                    name: 'Home',
                    params: { nextUrl: to.fullPath },
                })
            }
        })
    } else {
        next()
    }
})

export default router
