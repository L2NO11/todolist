<template>
    <h1>Register</h1>
    <div class="mb-3">
        <label for="fname" class="form-label">Firstname</label>
        <input
            type="text"
            class="form-control"
            id="fname"
            v-model="user.fname"
        />
    </div>

    <ul v-if="errors.fname.length !== 0">
        <li v-for="(item, index) in errors.fname" :key="index">{{ item }}</li>
    </ul>
    <p>Lastname</p>
    <input type="text" v-model="user.lname" />
    <ul v-if="errors.lname.length !== 0">
        <li v-for="(item, index) in errors.lname" :key="index">{{ item }}</li>
    </ul>
    <p>Email</p>
    <input type="email" v-model="user.email" />
    <ul v-if="errors.email.length !== 0">
        <li v-for="(item, index) in errors.email" :key="index">{{ item }}</li>
    </ul>
    <p>Passwords</p>
    <input type="password" v-model="user.password" />
    <ul v-if="errors.password.length !== 0">
        <li v-for="(item, index) in errors.password" :key="index">
            {{ item }}
        </li>
    </ul>
    <p>Confirm Passwords</p>
    <input type="password" v-model="confirm_password" />
    <br />
    <button @click="register">Register</button>
    <br />
    <router-link :to="{ name: 'login' }">Register Now!</router-link>
</template>
<script>
import { reactive, ref, watchEffect } from "vue";
export default {
    name: "RegisterPage",
    setup() {
        const user = reactive({
            fname: "",
            lname: "",
            email: "",
            password: "",
        });
        const confirm_password = ref("");
        const confirm_password_pass = ref(true);
        const errors = reactive({
            fname: [],
            lname: [],
            email: [],
            password: [],
        });
        watchEffect(() => {
            if (
                user.password.length > 8 &&
                user.fname.length !== 0 &&
                user.lname.length !== 0 &&
                user.email.length !== 0
            ) {
                confirm_password_pass.value =
                    user.password !== confirm_password.value;
                return;
            }
            confirm_password_pass.value = true;
        });
        const process = ref(true);
        const register = async () => {
            console.log("register...");
            const result = await axios.post("/api/auth/register", user);
            if (result.data.err) {
                Object.assign(errors, result.data.msg);
                console.log(errors);
            }
        };
        return {
            user,
            confirm_password,
            register,
            errors,
            process,
        };
    },
};
</script>

<style></style>
