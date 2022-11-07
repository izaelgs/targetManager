<template>
    <div>
        <pre>
            {{ $route.params.id }}
        </pre>
        Teste
    </div>
</template>

<script>
export default {
    created() {
        console.log(this.$route)
    }
}
</script>
