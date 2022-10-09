<template>
    <Navbar></Navbar>
    <router-view />
</template>

<script>
import Navbar from "./layout/Navbar.vue";
export default {
    name: "HomePage",
    components: { Navbar },
    setup() {
        return {};
    },
};
</script>

<style></style>
