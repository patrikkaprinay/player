<template>
    <div>
        <p>Home</p>
        <p v-if="store.state.loggedin">You are logged in</p>
        <div class="btn-group">
            <button
                class="btn btn-secondary dropdown-toggle"
                type="button"
                id="dropdownMenuClickableInside"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
            >
                Clickable inside
            </button>
            <ul
                class="dropdown-menu"
                aria-labelledby="dropdownMenuClickableInside"
            >
                <li>
                    <div class="btn-group">
                        <button
                            class="btn btn-secondary dropdown-toggle"
                            type="button"
                            id="dropdownMenuClickableInside"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="outside"
                            aria-expanded="false"
                        >
                            Clickable inside
                        </button>
                        <ul
                            class="dropdown-menu"
                            aria-labelledby="dropdownMenuClickableInside"
                        >
                            <li>
                                <a class="dropdown-item" href="#">Menu item</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Menu item</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Menu item</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        const getUsers = () => {
            axios.get('/api/loggedin').then((response) => console.log(response))
        }

        return {
            getUsers,
            store,
        }
    },
}
</script>

<style lang="scss" scoped></style>
