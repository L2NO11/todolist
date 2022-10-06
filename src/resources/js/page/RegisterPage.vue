<template>
    <div class="register-card card">
        <div class="card-body">
            <h1 class="register-header card-title">Register</h1>
            <div class="mb-3">
                <label for="fname" class="form-label">Firstname</label>
                <input
                    type="text"
                    class="form-control"
                    id="fname"
                    v-model="user.fname"
                />
                <ul v-if="errors.fname.length !== 0">
                    <li v-for="(item, index) in errors.fname" :key="index">
                        {{ item }}
                    </li>
                </ul>
            </div>
            <div class="mb-3">
                <label class="form-label">Lastname</label>
                <input type="text" class="form-control" v-model="user.lname" />
                <ul v-if="errors.lname.length !== 0">
                    <li v-for="(item, index) in errors.lname" :key="index">
                        {{ item }}
                    </li>
                </ul>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" v-model="user.email" />
                <ul v-if="errors.email.length !== 0">
                    <li v-for="(item, index) in errors.email" :key="index">
                        {{ item }}
                    </li>
                </ul>
            </div>
            <div class="mb-3">
                <label class="form-label">Passwords</label>
                <input
                    type="password"
                    class="form-control"
                    v-model="user.password"
                />
                <ul v-if="errors.password.length !== 0">
                    <li v-for="(item, index) in errors.password" :key="index">
                        {{ item }}
                    </li>
                </ul>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Passwords</label>
                <input
                    type="password"
                    class="form-control"
                    v-model="confirm_password"
                />
            </div>
            <div class="d-grid gap-2 col-6 mx-auto">
                <button class="btn btn-primary btn-block" @click="register">
                    Register
                </button>
                <label class="mx-auto"
                    >Don't you want to login ?
                    <router-link :to="{ name: 'login' }"
                        >Register Now!</router-link
                    ></label
                >
            </div>
        </div>
    </div>
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

<style>
.register-header {
    text-align: center;
    margin: 1rem 0 2rem;
}
.register-card {
    width: 50%;
    padding: 0 5rem;
    margin: 3rem auto;
}
</style>
