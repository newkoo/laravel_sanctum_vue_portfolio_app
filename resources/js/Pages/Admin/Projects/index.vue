<template>
    <section class="educations section" id="educations">
        <div class="skills_container">
            <div class="titlebar">
                <div class="titlebar_item">
                    <h1>Projects</h1>
                </div>
                <div class="titlebar_item">
                    <div class="btn btn__open--modal" @click.prevent="redirNew">New Project</div>
                </div>
            </div>

            <Datatable
                card-title="Projects"
                :request-u-r-l="uris.get"
                :data-content="dataContent"
                @datachanged="(tableData = $event, refresh = false)"
                :refresh="refresh"
                @event-triggered="eventWatcher"
            />
        </div>
        <!-------------- SERVICES MODAL --------------->
    </section>
</template>

<script setup>
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import Modal from '../../../Components/Modal.vue';
import { computed } from "@vue/reactivity";
import { axios_catcher, axios_request } from '../../../Utils/axios';
import Datatable from '../../../Components/Datatable/Datatable.vue';

const router = useRouter();
const redirNew = () => router.push('/admin/projects/add')
const uris = {
    get: "/get_all_project",
    create: "/create_project",
    update: (id) => "/update_project/" + id,
    delete: (id) => "/delete_project/" + id
};

const createForm = ref();
const refresh = ref(false);
const tableData = ref([]);

const dataContent = [
    { data: "photo", headText: "Image", render: (row, data) => `<img src='/img/upload/${data}' class="project_img-list" />` },
    { data: "description", headText: "Description" },
    { data: "title", headText: "Title" },
    { data: "link", headText: "Link" },
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
                            emitName: "redirectEdit",
                            firstParam: data,
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
    if (e.name == "redirectEdit") router.push('Projects/' + e.firstParam)
    if (e.name == "deleteItem") deleteItem(e.firstParam);
};

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
