<template>
    <section class="services section" id="services">
        <div class="services_container">
            <div class="titlebar">
                <div class="titlebar_item">
                    <h1>Services</h1>
                </div>
                <div class="titlebar_item">
                    <div class="btn btn__open--modal" @click="openModal(false)">New Service</div>
                </div>
            </div>

            <Datatable
                card-title="Skills"
                request-u-r-l="/get_all_services"
                method="get"
                :data-content="dataContent"
                @datachanged="(tableData = $event, refresh = false)"
                @event-triggered="eventWatcher"
                :refresh="refresh"
            />
        </div>
        <!-------------- SERVICES MODAL --------------->
        <div class="modal main__modal" :class="{ show: showModal }">
            <div class="modal__content">
                <span class="modal__close btn__close--modal" @click="closeModal">×</span>
                <h3 class="modal__title">{{ modalTitle }}</h3>
                <hr class="modal_line" />
                <br />
                <form @submit.prevent="createService">
                    <div>
                        <p>Service Name</p>
                        <input type="text" class="input" v-model="formData.name" />

                        <p>Icon Class</p>
                        <input type="text" class="input" v-model="formData.icon" />
                        <span
                            style="color:#006fbb;"
                        >Find your suitable icon: Font Awesome/IconsCount</span>

                        <p>Description</p>
                        <textarea cols="10" rows="5" v-model="formData.description"></textarea>
                    </div>

                    <br />
                    <hr class="modal_line" />
                    <div class="model__footer">
                        <button
                            class="btn mr-2 btn__close--modal"
                            @click.prevent="closeModal"
                        >Cancel</button>
                        <button
                            class="btn btn-secondary btn__close--modal"
                            type="submit"
                            v-if="!editMode"
                        >Save</button>
                        <button
                            class="btn btn-secondary btn__close--modal"
                            @click.prevent="updateData"
                            v-else
                        >Update</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup>
import Swal from 'sweetalert2';
import { onMounted, ref } from 'vue';
import { axios_catcher, axios_request } from '../../../Utils/axios';
import { computed } from '@vue/reactivity';
import Datatable from '../../../Components/Datatable/Datatable.vue';

const services = ref([]);
const refresh = ref(false);
const showModal = ref(false);
const hideModal = ref(true);
const editMode = ref(false);
const tableData = ref([]);
const formData = ref({
    name: null,
    icon: null,
    description: null
});
const modalTitle = computed(() => `${editMode.value ? `Update` : `Add`} Service`)
const dataContent = [
    {
        data: "name",
        headText: "Name",
    },
    {
        data: "icon",
        headText: "Icon"
    },
    {
        data: "description",
        headText: "Description"
    },
    {
        data: "id",
        headText: "Actions",
        render: function (row, data, index) {
            return {
                isRaw: false,
                nodes: [
                    {
                        elementType: "button",
                        isRaw: false,
                        class: "btn-icon success",
                        trigger: {
                            type: "click",
                            emitName: "openModal",
                            firstParam: true,
                            secondParam: index
                        }, innerHTML: `<i class="fas fa-pencil-alt"></i>`
                    },
                    {
                        elementType: "button",
                        isRaw: false,
                        class: "btn-icon danger",
                        trigger: {
                            type: "click",
                            emitName: "deleteItem",
                            firstParam: data,
                        }, innerHTML: `<i class="fas fa-trash-alt"></i>`
                    }
                ]
            }
        }
    }
];

//funcs
const openModal = (edit = false, itemIndex) => (edit ? (editMode.value = true, initService(tableData.value[itemIndex])) : null, showModal.value = !showModal.value);
const closeModal = () => (editMode.value = false, showModal.value = !hideModal.value, resetFormData());
const initService = (service) => (service.forEach(e => formData.value[e.name] = e.value));
const createService = async () => {
    await axios_request.post('/create_service', formData.value).then((result) => {
        getServices(), closeModal(), resetFormData();
        Swal.fire("Service Add Successfully", "", "success");
    }).catch((err) => {
        axios_catcher(err);
    });
}
const resetFormData = () => { Object.keys(formData.value).forEach(e => formData.value[e] = ''), refresh.value = true };
const updateData = async () => {
    await axios_request.put('/update_service/' + formData.value.id, formData.value).then((result) => {
        Swal.fire("Service Update Successfully", "", "success");
        closeModal(), resetFormData();
    }).catch((err) => {
        axios_catcher(err)
    });
}

const deleteItem = async (id) => {
    console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            await axios_request.delete('/delete_service/' + id).then((result) => {
                Swal.fire("Success", "Service Deleted", "success");
                getServices(), closeModal(), resetFormData();
            }).catch((err) => {
                axios_catcher(err)
            });
        }
    })
}

const eventWatcher = (e) => {
    if (e.name == "openModal") openModal(e.firstParam, e.secondParam)
    else if (e.name == "deleteItem") deleteItem(e.firstParam);
};
</script>

<style lang="scss" scoped>

</style>
