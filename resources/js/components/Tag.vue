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
        <div class="d-flex flex-row">
            <div class="form-check form-switch me-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    :checked="tag.enabled"
                    @click="banTag(tag.id)"
                />
            </div>
            <i class="bi bi-x-lg" @click="deleteTag"></i>
        </div>
    </div>
</template>

<script>
export default {
    props: ['tag'],
    emits: ['on-delete', 'on-ban'],
    setup(props, { emit }) {
        const deleteTag = () => {
            axios.post('/api/tag', {
                tagId: props.tag.id,
            })
            emit('on-delete')
        }

        const banTag = (id) => {
            axios
                .post('/api/tag/ban', {
                    id,
                })
                .then(emit('on-ban'))
        }

        return {
            deleteTag,
            banTag,
        }
    },
}
</script>

<style scoped></style>
