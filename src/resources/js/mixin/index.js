import { useStore } from "vuex";

export async function Todo(dispatchName, id, textSucess, textFailed) {
    const store = useStore();
    await store.dispatch(dispatchName, id).then((status) => {
        if (status) {
            Swal.fire({
                title: "Success",
                text: textSucess,
                icon: "success",
            }).then(() => {
                superRefresh();
            });
        } else {
            Swal.fire({
                title: "Failed",
                text: textFailed,
                icon: "warning",
            });
        }
    });
}
