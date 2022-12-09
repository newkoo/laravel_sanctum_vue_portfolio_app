<template>
    <section class="about section" id="about">
        <div class="about_container">
            <div class="titlebar">
                <div class="titlebar_item">
                    <h1>About Us</h1>
                </div>
                <div class="titlebar_item">
                    <div class="btn btn-secondary" @click.prevent="updateAbout">Save Changes</div>
                </div>
            </div>
            <div class="card_wrapper">
                <div class="wrapper_left">
                    <div class="card">
                        <p>Full Name</p>
                        <input type="text" class="input" v-model="form.name" />

                        <p>Email</p>
                        <input type="email" class="input" v-model="form.email" />

                        <p>Phone</p>
                        <input type="text" class="input" v-model="form.phone" />

                        <p>Address</p>
                        <input type="text" class="input" v-model="form.address" />

                        <p>Description</p>
                        <textarea cols="10" rows="5" v-model="form.description"></textarea>
                    </div>
                    <div class="card">
                        <p>Tagline</p>
                        <input type="text" class="input" v-model="form.tagline" />
                    </div>
                </div>

                <div class="wrapper_right">
                    <div class="card">
                        <div class="avatar_profile">
                            <img :src="getPhoto()" alt class="avatar_profile_img" />
                        </div>
                        <input type="file" id="fileimg" @change="changePhoto" />
                    </div>
                    <div class="card">
                        <p>CV</p>
                        <input type="file" id="filecv" @change="uploadCv" />
                    </div>
                </div>
            </div>
            <div class="titlebar">
                <div class="titlebar_item"></div>
                <div class="titlebar_item">
                    <div class="btn btn-secondary" @click.prevent="updateAbout">Save Changes</div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import Swal from 'sweetalert2';
import { ref, onMounted } from 'vue';
import { axios_catcher, axios_request } from '../../../Utils/axios';

const form = ref({
    id: '',
    name: '',
    photo: '',
    email: '',
    phone: '',
    address: '',
    description: '',
    tagline: '',
    cv: '',
});
onMounted(async () => {
    getAbout();
});

const getAbout = async () => {
    await axios_request.get('/edit_about').then((result) => {
        let data = result.data;
        form.value.id = data.id;
        form.value.name = data.name;
        form.value.photo = data.photo;
        form.value.email = data.email;
        form.value.phone = data.phone;
        form.value.address = data.address;
        form.value.description = data.description;
        form.value.tagline = data.tagline;
        form.value.cv = data.cv;
    }).catch((err) => {
        axios_catcher(err);
    });
}

const getPhoto = () => {
    let base = '/img';
    let photo = base + "/avatar.png";
    if (form.value.photo) {
        if (form.value.photo.indexOf('base64') != -1) photo = form.value.photo;
        else photo = base + '/upload/' + form.value.photo;
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
        form.value.photo = reader.result;
    }

    reader.readAsDataURL(file);
}

const uploadCv = (e) => {
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
        form.value.cv = reader.result;
    }

    reader.readAsDataURL(file);
}

const updateAbout = async () => {
    await axios_request.post('/update_about/' + form.value.id, form.value).then((result) => {
        Swal.fire({
            icon: 'success',
            title: 'Update Successfully'
        });
    }).catch((err) => {
        axios_catcher(err)
    });
}
</script>
