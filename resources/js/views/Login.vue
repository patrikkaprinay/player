<template>
    <div style="display: flex; justify-content: center">
        <div
            style="
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                width: 400px;
                margin-top: 30px;
            "
        >
            <p>Login</p>
            <input
                type="text"
                v-model="user.email"
                placeholder="Username or Email"
            />
            <input type="text" v-model="user.password" placeholder="Password" />
            <button @click="loginUser">Login</button>
            <router-link to="/register" href="#" style="font-size: 12px"
                >Register</router-link
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
        })

        const router = useRouter()

        const store = useStore()

        const loginUser = () => {
            store.dispatch('loginUser', { user: state.user }).then(() => {
                router.push({
                    name: 'Home',
                })
            })
        }

        return {
            ...toRefs(state),
            loginUser,
        }
    },
}
</script>

<style lang="scss" scoped></style>
