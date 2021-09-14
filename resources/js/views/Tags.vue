<template>
    <div class="container">
        <h2 class="my-2">Tags</h2>
        <form @submit.prevent="addTag">
            <input type="text" v-model="newTag.name" />
            <input type="color" v-model="newTag.color" />
            <input type="submit" value="Add Tag" />
        </form>
        <div>
            <Tag v-for="tag in tags" :key="tag.id" :tag="tag" />
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, toRefs } from 'vue'
import Tag from '../components/Tag.vue'

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

<style lang="scss" scoped></style>
