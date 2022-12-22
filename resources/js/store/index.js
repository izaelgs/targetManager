import Vue from 'vue';
import Vuex from 'vuex';

import Cookie from "js-cookie";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        teste: 'testando com sucesso',
        token: Cookie.get('access_token'),
        targets: [],
    },

    getters: {

        access_token(state) {
            return state.access_token;
        },

        targets() {
            this.get('target', data => {
                if (!data.error) {
                    this.$store.state.targets = data;
                } else {
                    alert(data.error);
                }
            }, null, true)
        },
    }
})
