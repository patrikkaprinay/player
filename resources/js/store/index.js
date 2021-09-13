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
            id: 0,
            name: '',
            image: '',
            artist: '',
            length: '00:00',
            currentTime: '00:00',
            albumcover: '',
            queueId: null,
        },
        muted: false,
        notification: {
            shown: false,
            hidden: false,
            text: '',
            status: 0,
        },
    },
    mutations: {
        mute(state) {
            state.player.muted = true
            state.muted = true
        },
        unMute(state) {
            state.player.muted = false
            state.muted = false
        },
        setVolume(state, payload) {
            state.player.volume = payload
        },
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
            state.playing = true
            console.log(state.player.src)
        },
        stopMusic(state) {
            state.player.pause()
            state.playing = false
            //console.log('Stopping music')
        },
        getQueue(state) {
            axios.get('/api/queue').then((response) => () => {
                state.queue = response.data
            })
        },
        setQueue(state, payload) {
            state.queue = payload
        },
        addToQueue(state, payload) {
            state.queue.push(payload)
        },
        clearQueue(state) {
            state.queue = []
        },
        updateAudioData(state, payload) {
            if (payload) {
                state.player.src = payload.songNumber.path
                state.currentlyPlaying.name = payload.songNumber.name
                state.currentlyPlaying.artist = payload.songNumber.artist.name
                state.currentlyPlaying.albumcover =
                    payload.songNumber.album.artwork_path
                state.currentlyPlaying.queueId = payload.id
                state.currentlyPlaying.id = payload.songNumber.id
            }
        },
        changeNotification(state, payload) {
            state.notification.text = payload.text
            state.notification.status = payload.status
        },
        showNotification(state) {
            state.notification.shown = true
            state.notification.hidden = false
        },
        hideNotification(state) {
            state.notification.hidden = true
            state.notification.shown = false
        },
    },
    actions: {
        newNotification({ commit }, payload) {
            commit('changeNotification', payload)
        },
        notify({ commit }) {
            commit('showNotification')
            setTimeout(() => {
                commit('hideNotification')
            }, 1800)
        },
        firstQueueSong({ commit }) {
            axios
                .get('/api/queue/first')
                .then((response) => commit('updateAudioData', response.data))
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
                commit('setQueue', response.data)
            })
        },
        clearQueue({ commit }) {
            axios.post('/api/queue/clear').then(commit('clearQueue'))
        },
    },
    modules: {},
})
