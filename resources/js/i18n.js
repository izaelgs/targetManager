import en from '../lang/en/en.json';
import ptBR from '../lang/pt-BR/pt-BR.json';

import VueI18n from 'vue-i18n';
import Vue from 'vue';

import Cookie from "js-cookie";


Vue.use(VueI18n);

let langs = ['en', 'pt-BR'];

export default new VueI18n({
    locale: Cookie.get('language') || (langs.includes(navigator.language) ? navigator.language : 'en'),
    messages: {
        en,
        "pt-BR": ptBR,
    }
});
