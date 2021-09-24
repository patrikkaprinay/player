<template>
    <div class="container">
        <h2 class="h1 my-3">Admins</h2>
        <div class="mt-3">
            <div
                v-for="admin in admins"
                :key="admin.id"
                class="d-flex align-items-center flex-row mb-3"
            >
                <p class="h5 mb-0 me-1">{{ admin.username }}</p>
                <span style="color: grey"> - {{ admin.email }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'

export default {
    setup() {
        const state = reactive({
            admins: [],
        })

        const getAdmins = () => {
            axios
                .get('/api/admins')
                .then((response) => (state.admins = response.data))
        }

        onMounted(() => {
            getAdmins()
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
