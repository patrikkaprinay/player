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
    setup(props) {
        const deleteTag = () => {
            axios.post('/api/tag', {
                tagId: props.tag.id,
            })
            // have to add bus to parent to refresh the tags list
        }

        return {
            deleteTag,
        }
    },
}
</script>

<style scoped></style>
