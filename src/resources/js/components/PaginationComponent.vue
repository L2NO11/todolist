<template lang="js">
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li v-if="paginate.prev !== null" class="page-item">
                <router-link class="page-link" :to="{
                    name: 'test',
                    params: { page: paginate.prev, completed: isCompleted },
                }">Previous</router-link>
            </li>
            <li v-for="i in paginate.all" :key="i" :class="isActive(i)">
                <router-link class="page-link" :to="{
                    name: 'test',
                    params: { page: i, completed: isCompleted },
                }">{{ i }}</router-link>
            </li>
            <li v-if="paginate.next !== null" class="page-item">
                <router-link class="page-link" :to="{
                    name: 'test',
                    params: { page: paginate.next, completed: isCompleted },
                }">Next</router-link>
            </li>
        </ul>
    </nav>
</template>
<script>
import { onMounted, ref, watchEffect } from "vue";
export default {
    name: "PaginationComponent",
    props: ["paginate", "isCompleted"],
    setup(props, { emit }) {
        const paginate = ref(props.paginate);
        const isCompleted = ref(props.isCompleted);
        onMounted(() => {});
        watchEffect(() => {
            paginate.value = props.paginate;
            isCompleted.value = props.isCompleted;
        });
        const isActive = (i) => {
            const actions = { "page-item": true };
            actions.active = paginate.value.curr === i;
            return actions;
        };
        return {
            paginate,
            isCompleted,
            isActive,
        };
    },
};
</script>
<style lang=""></style>
