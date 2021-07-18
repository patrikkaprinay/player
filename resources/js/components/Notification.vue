<template>
    <div>
        <div
            class="body"
            :class="{
                showNotification: store.state.notification.shown,
                hideNotification: store.state.notification.hidden,
            }"
        >
            <div class="row h-100 w-100">
                <div
                    class="
                        col-3
                        d-flex
                        justify-content-center
                        align-items-center
                        h-100
                        pe-0
                    "
                >
                    <i class="bi bi-plus-lg ms-3 fs-4"></i>
                </div>
                <div
                    class="
                        col-9
                        d-flex
                        justify-content-center
                        align-items-center
                        h-100
                        ps-0
                    "
                >
                    {{ store.state.notification.text }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { reactive, toRefs, watch } from 'vue'
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        watch(() => {})

        const state = reactive({
            count: 0,
        })

        return {
            ...toRefs(state),
            store,
        }
    },
}
</script>

<style scoped>
.body {
    position: absolute;
    /*right: 10px;*/
    top: 90px;
    right: -200px;
    width: 200px;
    min-height: 70px;
    height: 70px;
    background: rgb(211, 211, 211);
    border-radius: 10px;
}

.showNotification {
    animation: notification-slide-in 0.8s;
    animation-fill-mode: forwards;
}

.hideNotification {
    animation: notification-slide-out 0.8s;
    animation-fill-mode: forwards;
}

@keyframes notification-slide-in {
    to {
        right: 10px;
    }
}

@keyframes notification-slide-out {
    from {
        right: 10px;
    }
    to {
        right: -200px;
    }
}
</style>
