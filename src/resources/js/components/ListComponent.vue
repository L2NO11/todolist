<template lang="js">
    <table
        class="list table table-striped table-hover mx-auto border border-dark my-5"
    >
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Todo</th>
                <th scope="col">Completed</th>
                <th v-if="isCompleted != 1" scope="col">Done</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="todo in todolist" :key="todo.id">
                <th>{{ todo.id }}</th>
                <th>{{ todo.content }}</th>
                <td>
                    {{ todo.completed === 1 ? "True" : "False" }}
                </td>
                <td v-if="todo.completed !== 1">
                    <button
                        v-if="todo.completed === 0"
                        class="btn btn-success"
                        @click="doneTodo(todo.id)"
                    >
                        Done
                    </button>
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
import { onMounted } from "vue";
export default {
    name: "CopyListComponent",
    emits: ["deleteTodo", "doneTodo"],
    props: ["todolist", "isCompleted"],
    setup(props, { emit }) {
        const { todolist, isCompleted } = props;
        onMounted(() => {
            console.log("props", props);
        });
        const deleteTodo = (id) => {
            console.log("Delete", id);
            emit("deleteTodo", id);
        };
        const doneTodo = (id) => {
            console.log("Done", id);
            emit("doneTodo", id);
        };
        return {
            todolist,
            isCompleted,
            deleteTodo,
            doneTodo,
        };
    },
};
</script>
<style lang="css">
.list {
    width: 70%;
}
</style>
