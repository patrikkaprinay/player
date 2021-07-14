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
                            name: 'Login',
                            params: { nextUrl: to.fullPath },
                        })
                    }
                } else {
                    next()
                }
            } else {
                next({
                    name: 'Login',
                    params: { nextUrl: to.fullPath },
                })
            }
        })
    } else if (to.matched.some((record) => record.meta.guest)) {
        axios.get('/api/loggedin').then((response) => {
            console.log(response.data)
            console.log('fasz');
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
