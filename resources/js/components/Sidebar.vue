<template>
    <nav
        :class="[
            'sidebar bg-primary m-2 rounded-2 p-2',
            {'active': active}
        ]"
    >
        <i @click="toggle"
            :class="[
                'toggle-sidebar bi pointer fs-1 px-2 text-dark position-absolute top-0 start-100 d-none bg-light rounded-5',
                active ? 'bi-x' : 'bi-list'
            ]"
        ></i>
        <router-link
            :to="{ name: 'home' }"
            class="nav-link text-header p-2"
            exact
            >TargetManager
        </router-link>
        <ul class="nav flex-column">
            <li>
                <router-link
                    :to="{ name: 'new-target' }"
                    class="nav-link d-flex align-items-center"
                >
                    <i
                        class="bi-plus-circle me-2"
                        style="font-size: 1.5rem; color: cornflowerblue"
                    ></i>
                    {{ $t("sidebar.addTarget") }}
                </router-link>
            </li>
            <li>
                <router-link
                    :to="{ name: 'categories' }"
                    class="nav-link d-flex align-items-center"
                >
                    <i
                        class="bi-tags me-2"
                        style="font-size: 1.5rem; color: cornflowerblue"
                    ></i>
                    {{ $t("sidebar.categories") }}
                </router-link>
            </li>
            <li v-if="token">
                <a @click="logout" class="nav-link d-flex align-items-center pointer">
                    <i
                        class="bi bi-box-arrow-left me-2"
                        style="font-size: 1.5rem; color: cornflowerblue"
                    ></i>
                    Logout
                </a>
            </li>
        </ul>

        <!-- Projetos -->
        <hr />
        <ul
            v-show="token"
            v-if="targets.length"
            v-for="target in targets"
            class="nav flex-column"
            :key="target.id"
        >
            <router-link
                :to="{ path: '/target/' + target.id }"
                exact
                class="nav-link d-flex align-items-center text-white"
            >
                <i
                    class="bi-bullseye me-2"
                    style="font-size: 1.5rem; color: cornflowerblue"
                ></i>
                {{ target.title }}
            </router-link>
        </ul>
    </nav>
</template>

<script>
import Cookie from "js-cookie";
import Api from "../mixins/Api.vue";

export default {
    data() {
        return {
            active: false,
        };
    },

    computed: {
        token() {
            return this.$store.state.token;
        },

        targets() {
            return this.$store.state.targets;
        },
    },

    created() {
        if(this.token) this.appendTargets();
    },

    methods: {

        logout() {
            Cookie.remove('access_token');
            this.$store.state.token = null;
            this.$router.push({name: 'login'});
        },

        appendTargets() {
            this.get('target', data => {
                if (!data.error) {
                    this.$store.state.targets = data;
                } else {
                    alert(data.error);
                }
            }, null, true)
        },

        toggle() {
            this.active = !this.active;
        }
    },

    mixins: [Api],
};
</script>
