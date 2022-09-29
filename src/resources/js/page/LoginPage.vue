<template>
    <h1>Login</h1>
    <p>email</p>
    <input type="email" v-model="email" />
    <p>password</p>
    <input type="password" v-model="password" />
    <br />
    <button @click="login">Login</button>
    <router-link :to="{ name: 'register' }">Register Now!</router-link>
</template>

<script>
import { ref } from "vue";
import { useStore } from "vuex";
export default {
    name: "LoginPage",
    setup() {
        const email = ref("");
        const password = ref("");
        const store = useStore();
        const login = async () => {
            const body = {
                username: email.value,
                password: password.value,
            };
            await store.dispatch("login", body);
            console.log(store.state);
        };
        return {
            email,
            password,
            login,
        };
    },
};
</script>

<style></style>
