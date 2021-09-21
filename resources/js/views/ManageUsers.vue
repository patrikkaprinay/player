<template>
    <div class="container">
        <div class="d-flex justify-content-between my-2 mt-4">
            <h2>Users</h2>
            <i
                class="
                    btn btn-dark
                    d-flex
                    justify-content-center
                    align-items-center
                    bi bi-arrow-repeat
                "
                @click.prevent="getUsers"
            ></i>
        </div>
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
            getUsers,
        }
    },
}
</script>

<style lang="scss" scoped></style>
