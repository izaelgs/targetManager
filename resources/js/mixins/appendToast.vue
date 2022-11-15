<script>
export default{methods: {
    appendToast(mensagem, status) {
        return new Promise((resolve, reject) => {
            let toast = document.createElement("div");

            toast.classList.add("toast");
            toast.id = "liveToast";
            toast.role = "alert";
            toast.setAttribute("aria-live", "assertive");
            toast.setAttribute("aria-atomic", "true");

            toast.innerHTML = `
            <div class="toast-header  bg-${status} text-light">
                <strong class="me-auto">TargetManager</strong>
                <small>Agora</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${mensagem}
            </div>`;

            document.querySelector(".toast-container").append(toast);

            resolve(toast);
        });
    }
}}
</script>
