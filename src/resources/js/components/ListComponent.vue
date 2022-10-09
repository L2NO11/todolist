<template>
    <h2 class="my-5 text-center" v-if="Loading">Loading.....</h2>
    <div v-else>
        <div class="input-group my-5 justify-content-center">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01"
                    >Select</label
                >
            </div>
            <select
                v-model="filterCompleted"
                class="custom-select"
                id="inputGroupSelect01"
                @change="selectChange"
            >
                <option value="0">Incomplete</option>
                <option value="1">Completed</option>
            </select>
        </div>
        <div v-if="!error.status">
            <table
                class="list table table-striped table-hover mx-auto border border-dark my-5"
            >
                <thead>
                    <tr>
                        <th scope="col">Todo</th>
                        <th scope="col">Completed</th>
                        <th v-if="filterCompleted !== 1" scope="col">Finish</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="todo in datalist.todolist" :key="todo.id">
                        <th>{{ todo.content }}</th>
                        <td v-if="todo.completed !== 1">
                            {{ todo.completed === 1 ? "True" : "False" }}
                        </td>
                        <td>
                            <button
                                v-if="todo.completed === 0"
                                class="btn btn-success"
                                @click="doneTodo(todo.id)"
                            >
                                Done
                            </button>
                            <label v-else>Done</label>
                        </td>
                        <td>
                            <button
                                class="btn btn-danger"
                                @click="deleteTodo(todo.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li v-if="pagination.prev !== null" class="page-item">
                        <router-link
                            class="page-link"
                            :to="{
                                name: 'todo-list',
                                params: { page: pagination.prev, completed: 0 },
                            }"
                            >Previous</router-link
                        >
                    </li>
                    <li
                        v-for="i in pagination.all"
                        :key="i"
                        :class="{
                            'page-item': true,
                            active: pagination.curr === i,
                        }"
                    >
                        <router-link
                            class="page-link"
                            :to="{
                                name: 'todo-list',
                                params: { page: i, completed: 0 },
                            }"
                            >{{ i }}</router-link
                        >
                    </li>
                    <li v-if="pagination.next !== null" class="page-item">
                        <router-link
                            class="page-link"
                            :to="{
                                name: 'todo-list',
                                params: { page: pagination.next, completed: 0 },
                            }"
                            >Next</router-link
                        >
                    </li>
                </ul>
            </nav>
        </div>
        <h1 class="my-5 text-center" v-else>{{ error.msg }}</h1>
    </div>
</template>
<script>
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import { reactive, onMounted, ref, watchEffect } from "vue";
import Swal from "sweetalert2";
export default {
    name: "ListComponent",
    setup() {
        const store = useStore();
        const route = useRoute();
        const router = useRouter();
        const Loading = ref(true);
        const filterCompleted = ref(0);
        const pagination = reactive({
            all: null,
            curr: null,
            prev: null,
            next: null,
        });
        const error = reactive({
            status: false,
            msg: "",
        });
        const datalist = reactive({
            todolist: [],
        });
        const fetchData = async (params) => {
            Loading.value = true;
            await store
                .dispatch("getTodolist", params)
                .then((resp) => {
                    console.log(resp);
                    if (!resp.err) {
                        pagination.all = parseInt(resp.allpage);
                        const page = parseInt(params.page);
                        datalist.todolist = store.getters.todolist;
                        pagination.curr = page;
                        pagination.prev = page === 1 ? null : page - 1;
                        pagination.next =
                            pagination.all < page + 1 ? null : page + 1;
                    } else {
                        error.status = true;
                        error.msg = "!!Someting wrong!!";
                    }
                    Loading.value = false;
                    console.log(pagination);
                })
                .catch(() => {
                    error.status = true;
                    error.msg = "!!Someting wrong!!";
                    Loading.value = false;
                });
        };
        const Init = async () => {
            filterCompleted.value = parseInt(route.params.completed);
            fetchData(route.params);
        };
        watchEffect(() => {
            console.log(route.params);
            Loading.value = true;
            Init();
        });
        onMounted(Init);
        const selectChange = () => {
            Loading.value = true;
            const params = { page: 1, completed: filterCompleted.value };
            router.push({
                name: "todo-list",
                params,
            });
            fetchData(params);
        };
        const doneTodo = async (id) => {
            await store.dispatch("done", id).then((status) => {
                if (status) {
                    Swal.fire({
                        title: "Success",
                        text: "Done complete at id :" + id,
                        icon: "success",
                    }).then(() => {
                        Loading.value = true;
                        Init(route.params);
                    });
                } else {
                    Swal.fire({
                        title: "Failed",
                        text: "Done fail",
                        icon: "warning",
                    });
                }
            });
        };
        const deleteTodo = async (id) => {
            await store.dispatch("deleteTodo", id).then((status) => {
                if (status) {
                    Swal.fire({
                        title: "Success",
                        text: "Delete complete at id :" + id,
                        icon: "success",
                    }).then(() => {
                        Loading.value = true;
                        Init();
                    });
                } else {
                    Swal.fire({
                        title: "Failed",
                        text: "Delete fail",
                        icon: "warning",
                    });
                }
            });
        };
        return {
            datalist,
            doneTodo,
            deleteTodo,
            error,
            Loading,
            pagination,
            filterCompleted,
            selectChange,
        };
    },
};
</script>
<style>
.list {
    width: 70%;
}
</style>
