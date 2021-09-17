<template>
    <div
        class="
            w-100
            my-2
            ms-1
            p-2
            d-flex
            justify-content-between
            align-items-center
            rounded-3
        "
        :style="`background: ` + tag.color"
    >
        <p class="mb-0" style="font-weight: bold">{{ tag.name }}</p>
        <i class="bi bi-x-lg" @click="deleteTag"></i>
    </div>
</template>

<script>
export default {
    props: ['tag'],
    emits: ['on-delete'],
    setup(props, { emit }) {
        const deleteTag = () => {
            axios.post('/api/tag', {
                tagId: props.tag.id,
            })
            emit('on-delete')
        }

        return {
            deleteTag,
        }
    },
}
</script>

<style scoped></style>
