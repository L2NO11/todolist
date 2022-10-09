<template lang="">
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
            @change="change"
        >
            <option value="0">Incomplete</option>
            <option value="1">Completed</option>
        </select>
    </div>
</template>
<script>
import { ref } from "vue";
export default {
    name: "FilterDoneComponent",
    emits: ["selectChange"],
    props: ["isCompleted"],
    setup(props, { emit }) {
        const filterCompleted = ref(props.isCompleted);
        const change = () => {
            emit("selectChange", filterCompleted.value);
        };
        return {
            filterCompleted,
            change,
        };
    },
};
</script>
<style lang=""></style>
