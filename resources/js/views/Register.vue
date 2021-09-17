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
            <p>Register</p>
            <input v-model="user.name" type="text" placeholder="Name" />
            <input v-model="user.username" type="text" placeholder="Username" />
            <input v-model="user.email" type="text" placeholder="Email" />
            <input
                v-model="user.password"
                type="password"
                placeholder="Password"
            />
            <input
                v-model="user.passwordRe"
                type="password"
                placeholder="Password Confirmation"
            />
            <button @click="register">Register</button>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

export default {
    setup() {
        const store = useStore()

        const router = useRouter()

        const state = reactive({
            user: {
                name: '',
                username: '',
                email: '',
                password: '',
                passwordRe: '',
            },
        })

        function register() {
            store
                .dispatch('registerUser', {
                    user: state.user,
                })
                .then(() => {
                    router.push({
                        name: 'Home',
                    })
                })
        }

        return {
            ...toRefs(state),
            register,
        }
    },
}
</script>

<style lang="scss" scoped></style>
