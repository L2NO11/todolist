<template>
    <h1>Register</h1>
    <!-- <ul v-if="errors.length !== 0">
        <li v-for="(item, index) in errors" :key="index">{{ item }}</li>
    </ul> -->
    <p>Firstname</p>
    <input type="text" v-model="user.fname" />
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
    <router-link :to="{ name: 'register' }">Register Now!</router-link>
</template>
<script>
import { reactive, ref } from "vue";
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
                buttonIsDisable.value =
                    user.password !== confirm_password.value;
                return;
            }
            buttonIsDisable.value = true;
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
