<template>
    <div class="createbox card p-5 mx-auto my-5">
        <h3>Create Todo</h3>
        <form class="m-auto row g-2">
            <div class="form-floating mb-3">
                <input
                    type="text"
                    class="form-control"
                    id="floatingInput"
                    placeholder="Enter Todo"
                    v-model="todo.content"
                />
                <label for="floatingInput">Enter Todo</label>
            </div>
            <div class="form-floating mb-3">
                <input
                    type="datetime-local"
                    class="form-control"
                    id="floatingInput"
                    placeholder="add"
                    v-model="todo.at"
                />
                <label for="floatingInput">When do this</label>
            </div>
            <div class="col-auto m-auto">
                <button
                    @click="createTodo"
                    class="btn btn-primary mb-3"
                    :disabled="checkSubmit"
                >
                    Create
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { reactive, ref, watchEffect } from "vue";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import axios from "axios";
import Swal from "sweetalert2";
export default {
    name: "CreateComponent",
    setup() {
        const todo = reactive({
            content: "",
            at: "",
        });
        const router = useRouter();
        const checkSubmit = ref(true);
        watchEffect(() => {
            checkSubmit.value = !(
                todo.content.length > 3 && todo.at.length !== 0
            );
        });
        const store = useStore();
        const createTodo = async () => {
            const { access_token = undefined } = store.getters.token;
            if (!access_token) {
                router.push({ name: "login" });
            }
            const config = {
                method: "post",
                url: "/api/todo/add",
                data: todo,
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + access_token,
                },
            };
            await axios(config)
                .then(({ data, status }) => {
                    if (status === 200 && !data.err) {
                        Swal.fire({
                            title: "Create Success",
                            text: data.msg,
                            icon: "success",
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        title: "Create Failed",
                        text: "Something wrong",
                        icon: "warning",
                    });
                });
        };
        return {
            todo,
            checkSubmit,
            createTodo,
        };
    },
    mounted() {
        console.log("Component mounted.");
    },
};
</script>

<style>
.createbox {
    width: 50%;
    margin: 0 auto;
    text-align: center;
}
</style>
