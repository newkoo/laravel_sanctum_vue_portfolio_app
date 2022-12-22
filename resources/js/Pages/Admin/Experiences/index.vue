<template>
    <section class="educations section" id="educations">
        <div class="skills_container">
            <div class="titlebar">
                <div class="titlebar_item">
                    <h1>Experiences</h1>
                </div>
                <div class="titlebar_item">
                    <div class="btn btn__open--modal" @click="modalStatus = !modalStatus">New Skill</div>
                </div>
            </div>

            <Datatable
                card-title="Experiences"
                :request-u-r-l="uris.get"
                :data-content="dataContent"
                @datachanged="(tableData = $event, refresh = false)"
                :refresh="refresh"
                @event-triggered="eventWatcher"
            />
        </div>
        <!-------------- SERVICES MODAL --------------->
        <Modal
            v-if="modalStatus"
            :modal-title="modalTitle"
            @submit-trigger="createForm.click()"
            @close-trigger="closeModal"
            :update-button-active="editMode"
            @update-trigger="createForm.click()"
        >
            <form @submit.prevent="sendForm">
                <div>
                    <p>Company</p>
                    <input type="text" class="input" v-model="formData.company" />

                    <p>Period</p>
                    <input type="text" class="input" v-model="formData.period" />

                    <p>Position</p>
                    <input type="text" class="input" v-model="formData.position" />
                </div>
                <button type="submit" ref="createForm" style="display: none;">Submit</button>
            </form>
        </Modal>
    </section>
</template>

<script setup>
import Swal from 'sweetalert2';
import { ref } from 'vue';
import Modal from '../../../Components/Modal.vue';
import { computed } from "@vue/reactivity";
import { axios_catcher, axios_request } from '../../../Utils/axios';
import Datatable from '../../../Components/Datatable/Datatable.vue';

const uris = {
    get: "/get_all_experience",
    create: "/create_experience",
    update: (id) => "/update_experience/" + id,
    delete: (id) => "/delete_experience/" + id
};

const createForm = ref();
const refresh = ref(false);
let rawFormData = {
    company: '',
    period: '',
    position: '',
};
let formData = ref({ ...rawFormData });
const tableData = ref([]);

const dataContent = [
    { data: "company", headText: "Company" },
    { data: "period", headText: "Period" },
    { data: "position", headText: "Position" },
    {
        data: "id",
        headText: "Actions",
        render: (row, data, index) => {
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
const modalStatus = ref(false);
const editMode = ref(false);
const modalTitle = computed(() => (!editMode.value ? "Add" : "Update") + " Skills");
const eventWatcher = (e) => {
    if (e.name == "openModal") openModal(e.firstParam, e.secondParam)
    if (e.name == "deleteItem") deleteItem(e.firstParam);
};
const openModal = (edit = false, itemIndex) => (edit ? (editMode.value = true, initData(tableData.value[itemIndex])) : null, modalStatus.value = !modalStatus.value);
const initData = (data) => (data.forEach(e => formData.value[e.name] = e.value));
const closeModal = () => (editMode.value = false, modalStatus.value = false, formData.value = { ...rawFormData })
const sendForm = async (e) => {
    e.preventDefault();
    let ax = editMode.value ? axios_request.put : axios_request.post;

    let uri = editMode.value ? uris.update(formData.value.id) : uris.create;
    await ax(uri, formData.value).then((result) => {
        refresh.value = true, closeModal(), Swal.fire('Success', '', 'success')
    }).catch((err) => {
        axios_catcher(err);
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
            await axios_request.delete(uris.delete(id)).then((result) => {
                Swal.fire("Success", "Deleted", "success"), refresh.value = true, closeModal()
            }).catch((err) => {
                axios_catcher(err)
            });
        }
    })
}
</script>
