<template>
    <main>
        <h1>TargetManager</h1>
        <nav>
            <ul>
                <li>
                    <router-link :to="{ name: 'home' }" exact>Home</router-link>
                    <router-link :to="{ name: 'example' }">Exemplo</router-link>
                </li>
            </ul>
        </nav>
        <content>
            <router-view></router-view>
        </content>
        <footer>
            @Todos os direitos violados
        </footer>
    </main>
</template>

<script>
export default {
    mounted() {
        console.log('main');
    }
}
</script>
