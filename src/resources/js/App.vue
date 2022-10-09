<template>
    <router-view />
</template>

<script setup>
import { onMounted } from "vue";
import { useStore } from "vuex";
onMounted(() => {
    const store = useStore();
    store.dispatch("init");
});
</script>

<style></style>
