<template>
    <tr>
        <th scope="row">{{ user.id }}</th>
        <td>{{ user.name }}</td>
        <td>{{ user.username }}</td>
        <td>{{ user.email }}</td>
        <td>
            <select @change="changeSelect" v-model="role">
                <option :selected="user.role == 1" value="1">Admin - 1</option>
                <option :selected="user.role == 2" value="2">
                    Power User - 2
                </option>
                <option :selected="user.role == 3" value="3">User - 3</option>
            </select>
        </td>
        <td>
            <div
                class="
                    d-flex
                    justify-content-center
                    align-items-center
                    flex-row
                "
            >
                <i
                    class="bi bi-check-lg me-2"
                    @click.prevent="changeUser(user.id)"
                    v-if="changeMade == 1"
                ></i>
                <div class="dropdown">
                    <i
                        class="bi bi-trash"
                        role="button"
                        id="deleteDropdownLink"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        data-bs-auto-close="outside"
                    ></i>

                    <ul
                        class="dropdown-menu"
                        aria-labelledby="deleteDropdownLink"
                        v-if="user.id != this.$store.state.id"
                    >
                        <p class="mb-0 p-2 pt-0">
                            Are you sure you want to delete
                            <b>{{ user.username }}</b
                            >?
                        </p>
                        <li>
                            <a
                                class="dropdown-item bg-danger"
                                href="#"
                                :data-id="user.id"
                                @click.prevent="deleteUser(user.id)"
                                >Yes</a
                            >
                        </li>
                        <li>
                            <a
                                class="dropdown-item"
                                href="#"
                                @click.prevent="closeDeleteDrodown"
                                >No, go back</a
                            >
                        </li>
                    </ul>
                    <ul
                        class="dropdown-menu"
                        aria-labelledby="deleteDropdownLink"
                        v-if="user.id == this.$store.state.id"
                    >
                        <p class="mb-0 px-2">
                            You can't delete user <b>{{ user.username }}</b
                            >, because you're logged into it!
                        </p>
                    </ul>
                </div>
            </div>
        </td>
    </tr>
</template>

<script>
import { reactive, toRefs } from 'vue'

export default {
    props: ['user'],
    setup(props) {
        const state = reactive({
            role: props.user.role,
            changeMade: 0,
        })

        const changeSelect = () => {
            if (state.role != props.user.role) {
                state.changeMade = 1
            } else {
                state.changeMade = 0
            }
        }

        const closeDeleteDrodown = () => {
            document.querySelector('#deleteDropdownLink').click()
        }

        const changeUser = (id) => {
            axios
                .post('/api/user/update', {
                    id: id,
                    role: state.role,
                })
                .then((response) => {
                    console.log(response)
                    state.changeMade = 0
                })
        }

        const deleteUser = (id) => {
            axios
                .post('/api/user/delete', {
                    id,
                })
                .then((response) => console.log(response))
        }

        return {
            ...toRefs(state),
            changeSelect,
            closeDeleteDrodown,
            deleteUser,
            changeUser,
        }
    },
}
</script>

<style lang="scss" scoped></style>
