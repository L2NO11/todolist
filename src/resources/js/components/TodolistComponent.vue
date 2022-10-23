<template>
    <div v-if="!data.Loading">
        <h3 class="my-5 text-center" v-if="data.error.status">
            {{ data.error.msg }}
        </h3>
        <div v-else>
            <filterComponent
                @selectChange="selectChange"
                :isCompleted="newIsComplete"
            ></filterComponent>
            <h3 class="my-5 text-center" v-if="data.notFoundTodos">
                Not Found Todo List.
            </h3>
            <div v-else>
                <ListComponent
                    :todolist="data.todolist"
                    :isCompleted="newIsComplete"
                    @doneTodo="doneTodo"
                    @deleteTodo="deleteTodo"
                ></ListComponent>
                <PaginationComponent
                    :paginate="data.pagination"
                    :isCompleted="newIsComplete"
                ></PaginationComponent>
            </div>
        </div>
    </div>
    <h2 class="my-5 text-center" v-else>Loading.....</h2>
</template>
<script>
import { onMounted, reactive, computed, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useStore } from "vuex";
import { Todo } from "../mixin";
import filterComponent from "./FilterComponent.vue";
import ListComponent from "./ListComponent.vue";
import PaginationComponent from "./PaginationComponent.vue";
export default {
    name: "TodolistComponent",
    components: { filterComponent, ListComponent, PaginationComponent },
    setup() {
        const route = useRoute();
        const router = useRouter();
        const store = useStore();
        const data = reactive({
            Loading: true,
            isCompleted: null,
            notFoundTodos: false,
            pagination: {
                all: null,
                curr: null,
                prev: null,
                next: null,
            },
            error: {
                status: false,
                msg: "",
            },
            todolist: [
                {
                    id: 0,
                    content: "superman",
                    completed: 0,
                    at: "2022-10-08 12:19:00",
                    user_id: 1,
                },
            ],
        });
        const setPaginate = (allPage, currPage) => {
            data.pagination.all = allPage;
            data.pagination.curr = currPage;
            data.pagination.prev = currPage === 1 ? null : currPage - 1;
            data.pagination.next =
                data.pagination.all < currPage + 1 ? null : currPage + 1;
        };
        const getParams = () => {
            return {
                page: parseInt(route.params.page),
                completed: parseInt(route.params.completed),
            };
        };
        const refreshPage = async () => {
            data.Loading = true;
            const params = getParams();
            await store
                .dispatch("getTodolist", params)
                .then((resp) => {
                    console.log("resp", resp);
                    if (resp.err && resp.msg === "Notfound page") {
                        data.notFoundTodos = true;
                        data.Loading = false;
                        return;
                    }
                    data.todolist = store.getters.todolist;
                    let { page, completed } = params;
                    setPaginate(resp.allpage, page);
                    data.isCompleted = completed;
                    data.Loading = false;
                })
                .catch(() => {
                    data.error.status = true;
                    data.error.msg = "!!Something wrong!!";
                    data.Loading = false;
                });
        };
        const refreshWithDate = async () => {
            data.Loading = true;
            const params = getParams();
            params["date"] = route.query.searchDate;
            await store
                .dispatch("getTodolistWithDate", params)
                .then((resp) => {
                    console.log("resp", resp);
                    if (resp.err && resp.msg === "Notfound page") {
                        data.notFoundTodos = true;
                        data.Loading = false;
                        return;
                    }
                    data.todolist = store.getters.todolist;
                    let { page, completed } = params;
                    setPaginate(resp.allpage, page);
                    data.isCompleted = completed;
                    data.Loading = false;
                })
                .catch(() => {
                    data.error.status = true;
                    data.error.msg = "!!Something wrong!!";
                    data.Loading = false;
                });
        };
        const refreshWithName = async () => {
            data.Loading = true;
            const params = getParams();
            params["job"] = route.query.job;
            await store
                .dispatch("getTodolistWithName", params)
                .then((resp) => {
                    console.log("resp", resp);
                    if (resp.err && resp.msg === "Notfound page") {
                        data.notFoundTodos = true;
                        data.Loading = false;
                        return;
                    }
                    data.todolist = store.getters.todolist;
                    console.log(data.todolist);
                    let { page, completed } = params;
                    setPaginate(resp.allpage, page);
                    data.isCompleted = completed;
                    data.Loading = false;
                })
                .catch(() => {
                    data.error.status = true;
                    data.error.msg = "!!Something wrong!!";
                    data.Loading = false;
                });
        };
        const superRefresh = () => {
            if (
                (!route.query.isAll || route.query.isAll === "true") &&
                !route.query.job
            ) {
                refreshPage();
            } else if (route.query.job) {
                console.log(route.query.job);
                refreshWithName();
            } else {
                const date = route.query.searchDate;
                console.log(date);
                refreshWithDate();
            }
        };
        onMounted(async () => {
            try {
                superRefresh();
            } catch (err) {
                console.log(err.toString);
            }
        });
        watch(route, async () => {
            superRefresh();
        });
        const newIsComplete = computed(() => {
            return route.params.completed;
        });
        const newPaginate = computed(() => {
            let { page } = getParams();
            setPaginate(data.pagination.all, page);
            return data.pagination;
        });
        const selectChange = (completed) => {
            data.isCompleted = completed;
            router.push({
                name: "test",
                params: { page: "1", completed: completed },
            });
        };
        const doneTodo = async (id) => {
            await Todo("done", id, "Done complete at id :" + id, "Done fail");
        };
        const deleteTodo = async (id) => {
            await Todo(
                "deleteTodo",
                id,
                "Delete complete at id :" + id,
                "Delete fail"
            );
        };
        return {
            data,
            selectChange,
            newIsComplete,
            newPaginate,
            doneTodo,
            deleteTodo,
        };
    },
};
</script>
<style></style>
