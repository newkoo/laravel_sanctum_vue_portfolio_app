<template>
    <div class="login">
        <div class="formLogin">
            <form @submit.prevent="sendForm">
                <input type="email" placeholder="email" v-model="formData.email">
                <br>
                <input type="password" placeholder="password" v-model="formData.password">
                <br>
                <input type="submit" class="submit" value="Submit">
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { axios_request, axios_catcher } from "../../Utils/axios";
import { useRouter } from 'vue-router'
import { setUserInfo } from '../../Utils/auth_handler';
const formData = ref({
    email: '',
    password: '',
});

const router = useRouter();

const sendForm = async () => {
    await axios_request.post('login', formData.value).then((result) => {
        let data = result.data.data;
        setUserInfo(data.token, data.name);
        router.push('/Admin/Home');
    }).catch((err) => {
        axios_catcher(err);
    });
}

</script>


<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Fira Sans', sans-serif;
    font-size: 16px;
}

.login {
    background: #6c5ce7;
    padding: 0;
    margin: 0;
    width: 100%;
    height: 100vh;

}

.formLogin {
    display: flex;
    align-items: center;
    width: 22.8em;
    height: 55em;
    margin: 0 auto 0 auto;
    overflow: hidden;
}

input {
    background: rgba(228, 232, 243, 0.8);
    background-position: 0.5em 0.6em;
    border: none;
    color: rgba(80, 80, 80, 1);
    padding: 0 0 0 4rem;
    margin: 0 0 1em 0;
    width: 20em;
    height: 2.8em;
    outline: none;
    transition: background-color 0.4s;
}

input:hover {
    background-color: rgba(255, 255, 255, 255);
}

input:focus {
    background-color: rgba(255, 255, 255, 255);
}

.submit {
    background: rgba(220, 220, 220, 1);
    color: rgba(35, 35, 35, 0.8);
    padding: 0;
    margin: 2.5em 0 0 5em;
    width: 10em;
    cursor: pointer;
    transition: background-color 0.4s;
}

.submit:hover {
    background: #43467f;
    color: #ffffff;
}

.submit:focus {
    background: #43467f;
    color: #ffffff;
}
</style>
