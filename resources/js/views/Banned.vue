<template>
    <div class="container">
        <h2 class="my-3">Banned songs sorted by tags</h2>
    </div>
</template>

<script>
import { reactive, toRefs } from 'vue'

export default {
    setup() {
        const state = reactive({
            count: 0,
        })

        return {
            ...toRefs(state),
        }
    },
}
</script>

<style lang="scss" scoped></style>
