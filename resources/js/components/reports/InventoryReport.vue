<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-4">Inventory Report</h1>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <canvas id="inventoryChart"></canvas>
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
                        <th class="p-2 border">SKU</th>
                        <th class="p-2 border">Product</th>
                        <th class="p-2 border">Quantity</th>
                        <th class="p-2 border">Reorder Level</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in filteredInventory" :key="item.id">
                        <td class="p-2 border">{{ item.sku }}</td>
                        <td class="p-2 border">{{ item.product_name }}</td>
                        <td class="p-2 border">{{ item.quantity }}</td>
                        <td class="p-2 border">{{ item.reorder_level }}</td>
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
            inventory: [],
            searchQuery: "",
        };
    },
    computed: {
        filteredInventory() {
            return this.inventory.filter((item) =>
                item.product_name
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase())
            );
        },
    },
    async mounted() {
        const response = await axios.get("/api/inventory");
        this.inventory = response.data;

        const ctx = document.getElementById("inventoryChart").getContext("2d");
        new Chart(ctx, {
            type: "pie",
            data: {
                labels: this.inventory.map((i) => i.product_name),
                datasets: [
                    {
                        label: "Inventory",
                        data: this.inventory.map((i) => i.quantity),
                        backgroundColor: ["#ffc107", "#007bff"],
                    },
                ],
            },
        });
    },
};
</script>
