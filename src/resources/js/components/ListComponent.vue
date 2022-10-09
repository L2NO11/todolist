<template>
    <table
        class="list table table-striped table-hover mx-auto border border-dark"
    >
        <thead>
            <tr>
                <th scope="col">Todo</th>
                <th scope="col">Work at</th>
                <th scope="col">Completed</th>
                <th scope="col">Finish</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="todo in todolist" :key="todo.id">
                <th>{{ todo.content }}</th>
                <td>{{ todo.at }}</td>
                <td>{{ todo.completed === 1 ? "True" : "False" }}</td>
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
                    <button class="btn btn-danger" @click="deleteTodo(todo.id)">
                        Delete
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
import { useStore } from "vuex";
import { reactive, watchEffect, onMounted, watch } from "vue";
import Swal from "sweetalert2";
export default {
    name: "ListComponent",
    setup() {
        const store = useStore();
        const todolist = reactive([]);
        onMounted(async () => {
            const store = useStore();
            const { access_token } = store.getters.token;
            const config = {
                method: "get",
                url: "/api/todo/all",
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + access_token,
                },
            };
            await axios(config).then((resp) => {
                const { data } = resp;
                console.log("mounted = ", data);
                store.commit("SET_TODOLIST", [...data.todo]);
                Object.assign(todolist, [...data.todo]);
            });
        });

        const doneTodo = async (id) => {
            const { access_token } = store.getters.token;
            const config = {
                method: "put",
                url: "/api/todo/update/complete",
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + access_token,
                },
                data: {
                    id: `${id}`,
                },
            };
            await axios(config)
                .then((data) => {
                    console.log(data);
                    store.commit(
                        "SET_TODOLIST",
                        todolist.map((x) => {
                            if (x.id === id) {
                                x.completed = 1;
                                return x;
                            }
                            return x;
                        })
                    );
                    Swal.fire({
                        title: "Success",
                        text: "Done complete at id :" + id,
                        icon: "success",
                    });
                })
                .catch(() => {
                    Swal.fire({
                        title: "Failed",
                        text: "Done fail",
                        icon: "warning",
                    });
                });
        };
        const deleteTodo = async (id) => {
            const { access_token } = store.getters.token;
            const config = {
                method: "delete",
                url: "/api/todo/delete",
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + access_token,
                },
                data: {
                    id: `${id}`,
                },
            };
            await axios(config)
                .then((data) => {
                    const newlist = todolist.filter((x) => x.id !== id);
                    store.commit("SET_TODOLIST", newlist);

                    console.log("newlist :", newlist);
                    console.log("todolist :", todolist);
                    console.log("store todolist :", [...store.state.todolist]);
                    Swal.fire({
                        title: "Success",
                        text: "Delete complete at id :" + id,
                        icon: "success",
                    });
                })
                .catch(() => {
                    Swal.fire({
                        title: "Failed",
                        text: "Delete fail",
                        icon: "warning",
                    });
                });
        };
        return {
            todolist,
            doneTodo,
            deleteTodo,
        };
    },
};
</script>
<style>
.list {
    width: 70%;
}
</style>
