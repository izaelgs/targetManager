<template>
    <footer class="bg-dark mt-4 rounded-2 text-secondary d-flex align-items-center justify-content-center footer position-relative">
        {{ $t("footer.development") }}
        <select
            v-model="language"
            @change="changeLang"
            class="form-select bg-dark border-secondary text-secondary position-absolute bottom-0 end-0 w-25 m-2"
            aria-label="Default select example"
        >
            <option value="pt-BR">pt-BR</option>
            <option value="en">En</option>
        </select>
    </footer>
</template>

<script>

import Cookie from "js-cookie";
let langs = ['en', 'pt-BR'];

export default {
    data() {
        return {
            language: Cookie.get('language') || (langs.includes(navigator.language) ? navigator.language : 'en'),
        }
    },

    methods: {
        changeLang() {
            this.$i18n.locale = this.language;
            Cookie.set('language', this.language);
        }
    }
}

</script>
