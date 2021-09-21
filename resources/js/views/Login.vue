<template>
    <div style="display: flex; justify-content: center">
        <div class="loginIsland" style="">
            <h3>Login</h3>
            <p v-if="error == 1" class="text-danger">
                Email or password is incorrect!
            </p>
            <p v-if="error == 2" class="text-danger">
                Something went wrong, please try again later!
            </p>
            <input
                type="text"
                v-model="user.email"
                placeholder="Username or Email"
                class="loginInput"
            />
            <input
                type="text"
                class="loginInput"
                v-model="user.password"
                placeholder="Password"
                @keypress.enter="loginUser"
            />
            <button class="btn btn-danger" @click="loginUser">Login</button>
            <router-link
                to="/register"
                href="#"
                style="font-size: 12px; margin-top: 7px"
                >Don't have an account? Register</router-link
            >
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
    setup() {
        const state = reactive({
            user: {
                email: '',
                password: '',
            },
            error: 0,
        })

        const router = useRouter()

        const store = useStore()

        const loginUser = () => {
            store.dispatch('loginUser', { user: state.user }).then(() => {
                // if (response == '200') {
                router.push({
                    name: 'Home',
                })
                // } else if (response == '422') {
                //     state.error = 1
                // } else {
                //     state.error = 2
                // }
            })
        }

        return {
            ...toRefs(state),
            loginUser,
        }
    },
}
</script>

<style scoped>
.loginInput {
    border-radius: 7px;
    margin-block: 10px;
    padding: 7px 10px;
}

a {
    color: grey;
    text-decoration: none;
}

a:hover {
    color: rgb(151, 151, 151);
    text-decoration: underline;
}

.loginIsland {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    width: 400px;
    margin-top: 30px;
    background: #444;
    padding: 15px;
    border-radius: 20px;
}
</style>
