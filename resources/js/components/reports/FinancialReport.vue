<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Financial Report</h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <canvas id="financialChart"></canvas>
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
                        <th class="p-2 border">Category</th>
                        <th class="p-2 border">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredFinancials" :key="item.id">
                        <td class="p-2 border">{{ item.category }}</td>
                        <td class="p-2 border">${{ item.amount }}</td>
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
            financials: [],
            searchQuery: "",
        };
    },
    computed: {
        filteredFinancials() {
            return this.financials.filter((item) =>
                item.category
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase())
            );
        },
    },
    async mounted() {
        const response = await axios.get("/api/financial");
        this.financials = response.data;

        const ctx = document.getElementById("financialChart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: this.financials.map((f) => f.category),
                datasets: [
                    {
                        label: "Amount",
                        data: this.financials.map((f) => f.amount),
                        backgroundColor: ["#28a745", "#dc3545", "#007bff"],
                    },
                ],
            },
        });
    },
};
</script>
