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

            <div class="table">
                <div class="table_filter">
                    <span class="table_filter-Btn">
                        <i class="fas fa-ellipsis-h"></i>
                    </span>
                    <div>
                        <ul class="table_filter-list">
                            <li>
                                <p class="table_filter-link table_filter-link--active">All</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="table_search">
                    <div class="table_search-wrapper">
                        <select class="table_search-select" name id>
                            <option value>Filter</option>
                        </select>
                    </div>
                    <div class="relative">
                        <i class="table_search-input--icon fas fa-search"></i>
                        <input class="table_search--input" type="text" placeholder="Search Service" />
                    </div>
                </div>

                <div class="service_table-heading">
                    <p>Title</p>
                    <p>Icon</p>
                    <p>Description</p>
                    <p>Actions</p>
                </div>
                <!-- item 1 -->
                <div
                    class="service_table-items"
                    v-for="(item, index) in services"
                    :key="index"
                    v-if="services.length"
                >
                    <p>{{ item.name }}</p>
                    <button class="service_table-icon">
                        <i :class="item.icon"></i>
                    </button>
                    <p>{{ item.description }}</p>
                    <div>
                        <button class="btn-icon success" @click="openModal(true, index)">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="btn-icon danger" @click="deleteItem(item.id)">
                            <i class="far fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
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

const services = ref([]);
const showModal = ref(false);
const hideModal = ref(true);
const editMode = ref(false);
const formData = ref({
    name: "",
    icon: "",
    description: ""
});
const modalTitle = computed(() => `${editMode.value ? `Update` : `Add`} Service`)

//funcs
const openModal = (edit = false, itemIndex) => (edit ? (editMode.value = true, initService(services.value[itemIndex])) : null, showModal.value = !showModal.value);
const closeModal = () => (editMode.value = false, showModal.value = !hideModal.value, resetFormData());
const initService = (service) => Object.keys(service).forEach(e => formData.value[e] = service[e]);
const getServices = async () => await axios_request.get('/get_all_services').then(response => services.value = response.data.services);
const createService = async () => {
    await axios_request.post('/create_service', formData.value).then((result) => {
        getServices(), closeModal(), resetFormData();
        Swal.fire("Service Add Successfully", "", "success");
    }).catch((err) => {
        axios_catcher(err);
    });
}
const resetFormData = () => Object.keys(formData.value).forEach(e => formData.value[e] = '');
const updateData = async () => {
    await axios_request.put('/update_service/' + formData.value.id, formData.value).then((result) => {
        Swal.fire("Service Update Successfully", "", "success");
        getServices(), closeModal(), resetFormData();
    }).catch((err) => {
        axios_catcher(err)
    });
}

const deleteItem = async (id) => {
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

onMounted(async () => {
    getServices();
});
</script>

<style lang="scss" scoped>

</style>
