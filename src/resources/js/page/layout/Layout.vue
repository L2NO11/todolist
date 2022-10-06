<template>
    <div>
        <Navbar></Navbar>
        <slot name="content"></slot>
    </div>
</template>

<script>
import Navbar from "./navbar";
export default {
    name: "TodolistLayout",
    components: [Navbar],
};
</script>

<style></style>
