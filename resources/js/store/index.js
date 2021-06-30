import axios from 'axios'
import { createStore } from 'vuex'

export default createStore({
    state: {
        loggedin: false,
    },
    mutations: {
        /*loginUser(state, payload) {
            axios.get('/sanctum/csrf-cookie').then((response) => {})
        },*/
        writeOutShit(payload) {},
        logout(state) {
            axios.post('/logout').then(console.log('Successfully logged out'))
            state.loggedin = false
        },
        login(state) {
            state.loggedin = true
        },
    },
    actions: {
        registerUser({ commit }, { user }) {
            //commit('writeOutShit', user)
            const name = user.name
            const username = user.username
            const email = user.email
            const pass = user.password
            const passRe = user.passwordRe

            axios.get('/sanctum/csrf-cookie').then((response) => {
                axios
                    .post('/register', {
                        name: name,
                        username: username,
                        email: email,
                        password: pass,
                        password_confirmation: passRe,
                    })
                    .then((response) => console.log(response))
            })
            commit('login')
        },
        loginUser({ commit }, { user }) {
            const email = user.email
            const pass = user.password
            const remember = user.remember
            axios.get('/sanctum/csrf-cookie').then((response) => {
                axios
                    .post('/login', {
                        username: email,
                        password: pass,
                        remember: remember,
                    })
                    .then((response) => console.log(response.setCookie))
            })
            commit('login')
        },
        amILoggedin({ commit }) {
            axios.get('/api/loggedin').then((response) => {
                if (response.data) {
                    commit('login')
                }
            })
        },
    },
    modules: {},
})
