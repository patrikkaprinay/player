import axios from 'axios'
import { createStore } from 'vuex'

export default createStore({
    state: {
        loggedin: false,
        username: '',
        player: new Audio(''),
        playing: false,
        queue: [],
        currentlyPlaying: {
            name: '',
            image: '',
            artist: '',
        },
    },
    mutations: {
        logout(state) {
            axios.post('/logout').then(console.log('Successfully logged out'))
            state.loggedin = false
            state.username = ''
        },
        login(state) {
            state.loggedin = true
        },
        setUsername(state, payload) {
            state.username = payload
        },
        playMusic(state) {
            state.player.play()
        },
        stopMusic(state) {
            state.player.pause()
        },
        getQueue(state) {
            axios
                .get('/api/queue')
                .then((response) => (state.queue = response.data))
        },
        setQueue(state, payload) {
            state.queue = payload
            //state.player.src = state.queue[0].id
            //console.log(state.player)
        },
        addToQueue(state, payload) {
            state.queue.push(payload)
            //state.player.src = state.queue[0].id
            //console.log(state.player)
        },
        clearQueue(state) {
            state.queue = []
        },
        setAudioSrc(state, payload) {
            console.log(payload)
            state.player.src = payload.songNumber.path
            state.currentlyPlaying.name = payload.songNumber.name
            state.currentlyPlaying.artist = payload.songNumber.artist.name
        },
    },
    actions: {
        firstQueueSong({ commit }) {
            axios
                .get('/api/queue/first')

                .then((response) => commit('setAudioSrc', response.data))
        },
        registerUser({ commit }, { user }) {
            const name = user.name
            const username = user.username
            const email = user.email
            const pass = user.password
            const passRe = user.passwordRe

            axios.get('/sanctum/csrf-cookie').then((response) => {
                axios.post('/register', {
                    name: name,
                    username: username,
                    email: email,
                    password: pass,
                    password_confirmation: passRe,
                })
                //.then((response) => console.log(response))
            })
            commit('login')
        },
        loginUser({ commit }, { user }) {
            const email = user.email
            const pass = user.password
            axios.get('/sanctum/csrf-cookie').then((response) => {
                axios.post('/login', {
                    username: email,
                    password: pass,
                })
            })
            commit('login')
        },
        amILoggedin({ commit }) {
            axios.get('/api/loggedin').then((response) => {
                if (response.data) {
                    commit('login')
                    commit('setUsername', response.data.username)
                }
            })
        },
        getToQueue({ commit }) {
            axios.get('/api/queue').then((response) => {
                //console.log(response.data)
                /*response.data.forEach((song) => {
                    commit('addToQueue', song)
                })*/
                commit('setQueue', response.data)
            })
        },
        clearQueue({ commit }) {
            axios.post('/api/queue/clear').then(commit('clearQueue'))
        },
    },
    modules: {},
})
