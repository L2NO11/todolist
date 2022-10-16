<template lang="">
    <div class="d-flex pt-5 mb-3 w-75 mx-auto">
        <div class="input-group justify-content-center w-50">
            <label class="input-group-text" for="inputGroupSelect01"
                >Options</label
            >
            <select
                v-model="filterCompleted"
                class="form-select"
                id="inputGroupSelect01"
                @change="change"
            >
                <option value="0">Incomplete</option>
                <option value="1">Completed</option>
            </select>
        </div>
        <div class="supernoob">
            <div class="input-group-text">
                <input
                    class="form-check-input"
                    type="checkbox"
                    v-model="dateIsCheck"
                    aria-label="Checkbox for following text input"
                />
                <div class="mx-2">All</div>
            </div>
            <div class="runcan">
                <input
                    type="date"
                    v-model="date"
                    class="form-control w-50"
                    aria-label="Text input with checkbox"
                    v-if="!dateIsCheck"
                />
                <button @click="submit" type="submit" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </div>
    </div>
</template>
<script>
import { onMounted, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
export default {
    name: "FilterDoneComponent",
    emits: ["selectChange"],
    props: ["isCompleted"],
    setup(props, { emit }) {
        const route = useRoute();
        const router = useRouter();
        const filterCompleted = ref(props.isCompleted);
        const change = () => {
            emit("selectChange", filterCompleted.value);
        };
        const dateIsCheck = ref(true);
        const date = ref("");
        onMounted(() => {
            const { isAll, searchDate } = route.query;
            dateIsCheck.value = isAll === "true" || !isAll;
            date.value = searchDate;
        });
        const submit = () => {
            const config = {
                name: "test",
                params: {
                    page: 1,
                    completed: route.params.completed,
                },
                query: {
                    isAll: dateIsCheck.value,
                },
            };
            if (!dateIsCheck.value) {
                config["query"] = {
                    isAll: dateIsCheck.value,
                    searchDate: date.value,
                };
            }
            router.push(config);
        };
        return {
            filterCompleted,
            change,
            dateIsCheck,
            date,
            submit,
        };
    },
};
</script>
<style lang="css">
.filter-container {
    display: flex;
    width: 50%;
    margin: 0 auto;
}
div.d-flex {
    gap: 1rem;
}
div.runcan {
    display: flex;
    width: 100%;
}
div.supernoob {
    display: flex;
    width: 100%;
}
</style>
