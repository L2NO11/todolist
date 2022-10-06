<template>
    <div class="login-card card">
        <div class="card-body">
            <h1 class="login-header card-title">Login</h1>
            <div class="col-12" v-if="err_status">
                <div class="alert alert-danger">
                    {{ err_msg }}
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="exampleInputEmail1"
                    aria-describedby="emailHelp"
                    v-model="email"
                />
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label"
                    >Password</label
                >
                <input
                    type="password"
                    class="form-control"
                    id="exampleInputPassword1"
                    v-model="password"
                />
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-primary btn-block" @click="login">
                    Login
                </button>
            </div>
            <div class="col-12 text-center">
                <label
                    >Don't have an account?
                    <router-link :to="{ name: 'register' }"
                        >Register Now!</router-link
                    >
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";
import Swal from "sweetalert2";
export default {
    name: "LoginPage",
    setup() {
        const email = ref("");
        const password = ref("");
        const store = useStore();
        const err_status = ref(false);
        const router = useRouter();
        const err_msg = ref("");
        const login = async () => {
            const body = {
                username: email.value,
                password: password.value,
            };
            await store.dispatch("login", body);
            if (!store.state.authenticated) {
                Swal.fire({
                    title: "Login failed",
                    text: "email or password is invalid.",
                    icon: "warning",
                });
                return;
            } else {
                Swal.fire({
                    title: "Login Success",
                    text: "You have been logged-in successfully.",
                    icon: "success",
                });
                router.push({ name: "home" });
            }
        };
        return {
            email,
            password,
            login,
            err_status,
            err_msg,
        };
    },
};
</script>

<style>
.auth-button {
    text-align: center;
}
.login-header {
    text-align: center;
    margin: 1rem 0 2rem;
}
.login-card {
    width: 50%;
    padding: 0 5rem;
    margin: 3rem auto;
}
</style>
