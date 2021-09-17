<template>
    <div class="container">
        <h2 class="my-2">Tags</h2>
        <form @submit.prevent="addTag" class="addTagForm">
            <input
                type="text"
                v-model="newTag.name"
                placeholder="New tag name"
                required
            />
            <input type="color" v-model="newTag.color" />
            <input class="btn btn-dark" type="submit" value="Add Tag" />
        </form>
        <div class="mt-3">
            <p
                v-if="tags.length == 0"
                style="margin-top: 25px; font-size: 20px"
            >
                It seems like you don't have any tags created. :(
            </p>
            <Tag
                v-for="tag in tags"
                :key="tag.id"
                :tag="tag"
                @on-delete="loadData"
            />
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import Tag from '../components/Tag.vue'
import { useStore } from 'vuex'

export default {
    components: {
        Tag,
    },
    setup() {
        const state = reactive({
            tags: [],
            newTag: {
                name: '',
                color: '',
            },
        })

        const store = useStore()

        onMounted(() => {
            loadData()
        })

        const addTag = () => {
            axios
                .post('/api/add/tags', {
                    name: state.newTag.name,
                    color: state.newTag.color,
                })
                .then((response) => console.log(response))
            state.newTag.name = ''
            state.newTag.color = ''
            loadData()
            store.dispatch('getTags')
        }

        const loadData = () => {
            axios
                .get('/api/tags')
                .then((response) => (state.tags = response.data))
        }

        return {
            ...toRefs(state),
            addTag,
            loadData,
        }
    },
}
</script>

<style scoped>
.addTagForm {
    display: flex;
    justify-content: center;
    align-items: center;
}

.addTagForm input[type='color'] {
    padding: 0;
    width: 37px;
    height: 37px;
    margin-inline: 10px;
}

.addTagForm input[type='text'] {
    outline: none;
    padding: 5px 7px;
    border-radius: 5px;
    box-sizing: border-box;
}
</style>
