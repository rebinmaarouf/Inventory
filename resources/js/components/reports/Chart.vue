<template>
    <div class="chart">
        <h3>{{ title }}</h3>
        <apexchart :options="options" :series="options.series"></apexchart>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    components: { apexchart: VueApexCharts },
    props: {
        title: String,
        options: Object,
    },
};
</script>
