<template>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">IDIO</a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarScroll"
                aria-controls="navbarScroll"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul
                    class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll"
                    style="--bs-scroll-height: 100px"
                >
                    <li class="nav-item">
                        <router-link
                            class="nav-link active"
                            :to="{ name: 'todo-list' }"
                            >Todo-list</router-link
                        >
                    </li>
                    <li class="nav-item">
                        <router-link
                            class="nav-link active"
                            :to="{ name: 'create' }"
                            >Create-todo</router-link
                        >
                    </li>
                </ul>
                <ul class="nav justify-content-end">
                    <li class="nav-item">
                        <a @click="logout" class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
import { useStore } from "vuex";
import { useRouter } from "vue-router";
export default {
    name: "NavBarTodolist",
    setup() {
        const store = useStore();
        const router = useRouter();
        const logout = () => {
            store.dispatch("logout");
            router.push({ name: "login" });
        };
        return {
            logout,
        };
    },
};
</script>
