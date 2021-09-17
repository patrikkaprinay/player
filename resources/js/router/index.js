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
import LikedSongs from '../views/LikedSongs.vue'
import Album from '../views/Album.vue'
import History from '../views/History.vue'
import Tags from '../views/Tags.vue'
import Tag from '../views/Tag.vue'

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
        meta: {
            requiresAuth: true,
        },
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
        /*meta: {
            requiresAuth: true,
            is_admin: true,
        },*/
    },
    {
        path: '/add/artist',
        name: 'AddArtist',
        component: AddArtist,
        /*meta: {
            requiresAuth: true,
            is_admin: true,
        },*/
    },
    {
        path: '/add/album',
        name: 'AddAlbum',
        component: AddAlbum,
        /*meta: {
            requiresAuth: true,
            is_admin: true,
        },*/
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
        name: 'LikedSongs',
        component: LikedSongs,
    },
    {
        path: '/album/:name',
        name: 'Album',
        component: Album,
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
    },
    {
        path: '/tag/:name',
        name: 'Tag',
        component: Tag,
        props: true,
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        axios.get('/api/loggedin').then((response) => {
            if (response.data !== null) {
                if (to.matched.some((record) => record.meta.is_admin)) {
                    if (response.data.roles == 1) {
                        next()
                    } else {
                        next({
                            path: '/login',
                            params: { nextUrl: to.fullPath },
                        })
                    }
                } else {
                    next()
                }
            } else {
                next({
                    path: '/login',
                    params: { nextUrl: to.fullPath },
                })
            }
        })
    } else if (to.matched.some((record) => record.meta.guest)) {
        axios.get('/api/loggedin').then((response) => {
            console.log(response.data)
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
