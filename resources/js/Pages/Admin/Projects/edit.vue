<template>
    <section class="about section" id="project">
        <div class="about_container">
            <div class="titlebar">
                <div class="titlebar_item">
                    <h1>Add Project</h1>
                </div>
                <div class="titlebar_item">
                    <div class="btn btn-secondary" @click="save">Save Project</div>
                </div>
            </div>
            <div class="card_wrapper">
                <div class="wrapper_left">
                    <div class="card">
                        <p>Title</p>
                        <input type="text" class="input" v-model="formData.title" />

                        <p>Description</p>
                        <textarea cols="10" rows="5" v-model="formData.description"></textarea>

                        <p>Link</p>
                        <input type="text" class="input" v-model="formData.link" />
                    </div>
                </div>

                <div class="wrapper_right">
                    <div class="card">
                        <div class="project_img-container">
                            <img :src="getPhoto()" alt class="project_img" />
                        </div>
                        <br />
                        <input type="file" id="fileimg" @change="changePhoto" />
                        <br />
                        <br />
                        <br />
                    </div>
                </div>
            </div>
            <div class="titlebar">
                <div class="titlebar_item"></div>
                <div class="titlebar_item">
                    <div class="btn btn-secondary" @click="save">Save Project</div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { axios_catcher, axios_request } from '../../../Utils/axios';
const router = useRouter();
const route = useRoute();
let formData = ref({
    title: '',
    description: '',
    link: '',
    photo: '',
});
const id = route.params.id;
(async () => {
    await axios_request.get('/get_project/' + id).then((result) => {
        formData.value = result.data;
    }).catch((err) => {
        axios_catcher(err)
    });
})();


const getPhoto = () => {
    let base = '/img/upload';
    let photo = base + "/avatar.png";
    if (formData.value.photo) {
        if (formData.value.photo.indexOf('base64') != -1) photo = formData.value.photo;
        else photo = base + '/' + formData.value.photo;
    }
    return photo;
}
const changePhoto = (e) => {
    let file = e.target.files[0];
    let reader = new FileReader();
    let limit = 1024 * 1024 * 2;
    if (file['size'] > limit) {
        Swal.fire({
            icon: 'error',
            title: 'Ooops...',
            text: 'You are uploading a large file'
        })
        return false;
    }

    reader.onloadend = (file) => {
        formData.value.photo = reader.result;
    }

    reader.readAsDataURL(file);
}

const save = async () => {
    await axios_request.put('/update_project/' + id, formData.value).then((result) => {
        Swal.fire("Project Edit Successfully", "", "success").then(() => router.push('/admin/projects'));
    }).catch((err) => {
        axios_catcher(err);
    });

}

</script>

<style lang="scss" scoped>

</style>
