<template>
    <CreateComponent></CreateComponent>
    <ListComponent></ListComponent>
</template>
<script>
import CreateComponent from "./CreateComponent.vue";
import ListComponent from "./ListComponent.vue";
export default {
    name: "TodolistComponent",
    components: { CreateComponent, ListComponent },
};
</script>
<style></style>
