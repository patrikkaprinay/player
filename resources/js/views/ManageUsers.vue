<template>
    <div class="container">
        <h2 class="my-2">Users</h2>
        <div>
            <table class="table" style="color: white">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                        <th scope="col" class="text-center">Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <UserLine
                        v-for="user in users"
                        :key="user.id"
                        :user="user"
                    />
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import UserLine from '../components/UserLine.vue'

export default {
    components: { UserLine },
    setup() {
        const state = reactive({
            users: [],
        })

        const getUsers = () => {
            axios.get('/api/users').then((response) => {
                console.log(response)
                state.users = response.data
            })
        }

        onMounted(() => {
            getUsers()
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
