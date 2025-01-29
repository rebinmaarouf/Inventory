<template>
    <div class="flowchart">
        <h3>{{ title }}</h3>
        <ul>
            <li v-for="(stage, index) in data" :key="index">
                {{ stage.label }}: {{ stage.value }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        data: Array,
    },
};
</script>
