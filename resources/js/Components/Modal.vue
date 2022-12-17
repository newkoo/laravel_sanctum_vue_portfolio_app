<template>
    <div class="modal main__modal show">
        <div class="modal__content">
            <span class="modal__close btn__close--modal" @click="closeModal">×</span>
            <h3 class="modal__title">{{ modalTitle }}</h3>
            <hr class="modal_line" />
            <br />
            <slot></slot>
            <br />
            <hr class="modal_line" />
            <div class="model__footer">
                <button
                    class="btn mr-2 btn__close--modal"
                    @click="triggerUpdate"
                    v-if="updateButtonActive"
                >{{ updateButtonText }}</button>
                <button
                    class="btn mr-2 btn__close--modal"
                    @click="closeModal"
                >{{ cancelButtonText }}</button>
                <button
                    class="btn btn-secondary btn__close--modal"
                    @click="triggerSubmit"
                >{{ saveButtonText }}</button>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    modalTitle: {
        type: String,
        required: true,
    },
    saveButtonText: {
        type: String,
        default: "Save",
    },
    cancelButtonText: {
        type: String,
        default: "Cancel",
    },
    updateButtonActive: {
        type: Boolean,
    },
    updateButtonText: {
        type: String,
        default: "Update"
    },
});

const emit = defineEmits(["updateTrigger", "submitTrigger", "closeTrigger"]);
const closeModal = () => emit("closeTrigger");
const triggerSubmit = () => emit("submitTrigger");
const triggerUpdate = () => emit("updateTrigger");
</script>

<style lang="scss" scoped>

</style>
