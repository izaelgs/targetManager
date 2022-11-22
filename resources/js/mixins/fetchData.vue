<script>
import Cookie from "js-cookie";
import AppendToast from "./appendToast.vue";
export default {
    data() {
        return{
            access_token: "",
        }
    },
    created() {
        this.access_token = Cookie.get("access_token");
        this.fetchData();
    },
    methods: {

        post(url,payload, callback) {

            fetch("http://127.0.0.1:8000/api/" + url, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
                body: JSON.stringify(payload),
            })
                .then(res => {
                    if (res.ok) {
                    return res.json();
                    } else {
                    return Promise.reject(res);
                    }
                })
                .then((data) => {
                    callback(data);
                })
                .catch((error) => {
                    // if(error.errors) {
                    //     this.displayErrors(data.errors);
                    // }

                    // if(error.message) {
                    //     this.showToast(error.message, "danger", 'exclamation-triangle-fill');
                    // }
                    console.log(error);
                });
        },

        fetchData(url, callback) {
            fetch("http://localhost:8000/api/"+ url, {
                method: "GET",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    Authorization: "Bearer " + this.access_token,
                },
            })
                .then(response => this._handleErrors(response))
                .then((response) => response.json())
                .then((data) => {
                    callback(data);
                })
                .catch((erro) => {
                    console.log(erro);
                });
        },

        _handleErrors(res) {
            if(!res.ok) throw new Error(res.statusText);
            return res;
        }
    },

    mixins: [AppendToast]
}
</script>
