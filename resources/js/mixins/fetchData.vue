<script>
import Cookie from "js-cookie";
import AppendToast from "./appendToast.vue";
import axios from 'axios';

export default {
    data() {
        return{
            access_token: "",
        }
    },
    created() {
        this.access_token = Cookie.get("access_token");
        this.language = Cookie.get("language");

        axios.defaults.baseURL = `/api/`;
        axios.defaults.headers = {
            "Authorization" : "Bearer " + this.access_token,
            "Accept" : "application/json",
            "Accept-Language" : this.language
        };
    },
    methods: {

        del(url, callback, hideSuccessMessage) {
            axios.delete(url)
            .then(data => {
                if(data.status == 200) {
                    callback(data.data);
                    if(!hideSuccessMessage) this.showToast(data.data.message, "success");
                } else {
                    this.showErrors(data.data);
                }
            })
        },

        post(url,payload, callback, errorHandler, hideSuccessMessage) {
            axios.post(url, payload)
                .then(data => {
                    if(!hideSuccessMessage) this.showToast(data.data.message, "success");
                    callback(data.data)
                })
                .catch(error => {
                    errorHandler(error.response.data)
                })
        },

        put(url,payload, callback, errorHandler) {
            axios.put(url, payload)
                .then(data => {
                    this.showToast(data.data.message, "success");
                    callback(data.data)
                })
                .catch(error => {
                    console.log(error);
                    errorHandler ? errorHandler(error.response.data) : this.showErrors(error.response.data);
                })
        },

        fetchData(url, callback, errorHandler, hideSuccessMessage) {
            axios.get(url)
            .then(data => {
                if(data.status == 200) {
                    callback(data.data);
                    if(!hideSuccessMessage) this.showToast(data.data.message, "success");
                } else {
                    errorHandler ? errorHandler(error.response.data) : this.showErrors(error.response.data);
                }
            })
        },


        showErrors(data) {
            if(data.message) {
                this.showToast(data.message, "danger", 'exclamation-triangle-fill');
            }
            if(data.errors) {
                this.displayErrors(data.errors);
            }
        },

        changeLanguage(language) {
            Cookie.set('language', language);
            window.location.reload()
        }
    },

    mixins: [AppendToast]
}
</script>
