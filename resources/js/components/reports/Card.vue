<template>
    <div class="card">
        <div class="icon"><i :class="'fas fa-' + icon"></i></div>
        <div class="info">
            <h3>{{ title }}</h3>
            <p>{{ value }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        value: [String, Number],
        icon: String,
    },
};
</script>

<style scoped>
.card {
    padding: 20px;
    border-radius: 8px;
    background: #f4f4f4;
    text-align: center;
}
.icon {
    font-size: 24px;
}
</style>
