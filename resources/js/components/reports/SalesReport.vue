<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Sales Report</h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <canvas id="salesChart"></canvas>
        </div>
        <div class="mt-8">
            <input
                v-model="searchQuery"
                placeholder="Search..."
                class="p-2 border rounded"
            />
            <table class="w-full mt-4">
                <thead>
                    <tr>
                        <th class="p-2 border">Product</th>
                        <th class="p-2 border">Quantity Sold</th>
                        <th class="p-2 border">Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="sale in filteredSales" :key="sale.id">
                        <td class="p-2 border">{{ sale.product_name }}</td>
                        <td class="p-2 border">{{ sale.quantity_sold }}</td>
                        <td class="p-2 border">${{ sale.revenue }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Chart from "chart.js/auto";

export default {
    data() {
        return {
            sales: [],
            searchQuery: "",
        };
    },
    computed: {
        filteredSales() {
            return this.sales.filter((sale) =>
                sale.product_name
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase())
            );
        },
    },
    async mounted() {
        const response = await axios.get("/api/sales");
        this.sales = response.data;

        const ctx = document.getElementById("salesChart").getContext("2d");
        new Chart(ctx, {
            type: "line",
            data: {
                labels: this.sales.map((s) => s.product_name),
                datasets: [
                    {
                        label: "Revenue",
                        data: this.sales.map((s) => s.revenue),
                        borderColor: "#007bff",
                        fill: false,
                    },
                ],
            },
        });
    },
};
</script>
