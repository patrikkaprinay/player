<template>
    <div class="container">
        <h1 class="mb-4 mt-2">Queue Rules</h1>
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                id="samesong"
                @change="changeSetting(1)"
                :checked="rules[0]"
                :disabled="store.state.role != 1"
            />
            <div>
                <label class="form-check-label me-2" for="samesong"
                    >Don't play the same song for 30 mins
                </label>
                <i
                    class="bi bi-question-circle-fill"
                    data-bs-toggle="tooltip"
                    data-bs-placement="left"
                    title="While this switch is turned on, you won't be able to add a song to the queue that has been played in the last 30 minutes"
                ></i>
            </div>
        </div>
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                id="sameartist"
                @change="changeSetting(2)"
                :checked="rules[1]"
                :disabled="store.state.role != 1"
            />
            <div>
                <label class="form-check-label me-2" for="sameartist"
                    >Don't play the same artist more than 5 times in 30 mins
                </label>
                <i
                    class="bi bi-question-circle-fill"
                    data-bs-toggle="tooltip"
                    data-bs-placement="left"
                    title="While this switch is turned on, you won't be able to add a song from artist who has been played more than 5 times in the last 30 minutes"
                ></i>
            </div>
        </div>
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                id="cooldownuser"
                @change="changeSetting(3)"
                :checked="rules[2]"
                :disabled="store.state.role != 1"
            />
            <div>
                <label class="form-check-label me-2" for="cooldownuser"
                    >Don't let users add more than 10 songs into the queue in 30
                    minutes
                </label>
                <i
                    class="bi bi-question-circle-fill"
                    data-bs-toggle="tooltip"
                    data-bs-placement="left"
                    title="While this switch is turned on, users won't be able to add songs to the queue if they added more than 10 songs to the queue in the last 30 minutes"
                ></i>
            </div>
        </div>
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                id="fillqueue"
                @change="changeSetting(4)"
                :checked="rules[3]"
                :disabled="store.state.role != 1"
            />
            <div>
                <label class="form-check-label me-2" for="fillqueue"
                    >Don't play the queue unless there are more than 10 songs in
                    it
                </label>
                <i
                    class="bi bi-question-circle-fill"
                    data-bs-toggle="tooltip"
                    data-bs-placement="left"
                    title="While this switch is turned on, music won't be played until there's 10 songs in the queue"
                ></i>
            </div>
        </div>
        <div class="form-check form-switch">
            <input
                class="form-check-input"
                type="checkbox"
                id="autodj"
                @change="changeSetting(5)"
                :checked="rules[4]"
                v-model="rules[4]"
                :disabled="store.state.role != 1"
            />
            <div>
                <label class="form-check-label me-2" for="autodj"
                    >Auto DJ
                </label>
                <i
                    class="bi bi-question-circle-fill"
                    data-bs-toggle="tooltip"
                    data-bs-placement="left"
                    title="Don't want to add songs to the queue? Enable Auto DJ, sit back and relax, Auto DJ will take care of the songs for you"
                ></i>
            </div>
            <div v-if="rules[4]">
                <label>
                    Play songs with likes above
                    <input
                        type="number"
                        min="1"
                        style="width: 50px"
                        v-model="rules[5]"
                        @change="ADJLike"
                    />
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import { computed, onMounted, reactive, toRefs } from 'vue'
import { useStore } from 'vuex'

export default {
    setup() {
        const store = useStore()

        const changeSetting = (id) => {
            axios.post('/api/rule', {
                id,
            })
        }
        const state = reactive({
            rules: [],
        })
        onMounted(() => {
            axios.get('/api/rules').then((response) => {
                state.rules = response.data.rules
            })
        })

        const ADJLike = () => {
            axios
                .post('/api/autodj/like', {
                    likes: state.rules[6],
                })
                .then((response) => console.log(response))
        }

        return {
            ...toRefs(state),
            changeSetting,
            store,
            ADJLike,
        }
    },
}
</script>

<style lang="scss" scoped></style>
