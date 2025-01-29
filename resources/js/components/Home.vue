<template>
    <div class="dashboard">
        <h1>Dashboard</h1>
        <!-- Filters Section -->
        <div class="filters">
            <label for="month">Month:</label>
            <select v-model="selectedMonth" @change="fetchData">
                <option
                    v-for="(month, index) in months"
                    :key="index"
                    :value="index + 1"
                >
                    {{ month }}
                </option>
            </select>

            <label for="year">Year:</label>
            <select v-model="selectedYear" @change="fetchData">
                <option v-for="year in years" :key="year" :value="year">
                    {{ year }}
                </option>
            </select>

            <label for="week">Week:</label>
            <select v-model="selectedWeek" @change="fetchData">
                <option v-for="week in weeks" :key="week" :value="week">
                    {{ week }}
                </option>
            </select>
        </div>

        <!-- Dashboard Cards -->
        <div class="grid">
            <Card
                title="Total Sales"
                :value="`$${
                    dashboardData.totalSales && !isNaN(dashboardData.totalSales)
                        ? dashboardData.totalSales.toFixed(2)
                        : '0.00'
                }`"
                icon="dollar-sign"
            />
            <Card
                title="Total Orders"
                :value="dashboardData.totalOrders"
                icon="shopping-cart"
            />
            <Card
                title="Top Selling Item"
                :value="`${dashboardData.topSellingItem?.product_name} (${dashboardData.topSellingItem?.category_name})`"
                icon="star"
            />
            <Card
                title="Monthly Sales"
                :value="`$${
                    dashboardData.monthlySales &&
                    !isNaN(dashboardData.monthlySales)
                        ? dashboardData.monthlySales.toFixed(2)
                        : '0.00'
                }`"
                icon="chart-line"
            />
        </div>

        <!-- Charts Section -->
        <div class="charts">
            <Chart
                v-if="
                    weeklySalesChartOptions.series &&
                    weeklySalesChartOptions.series.length > 0
                "
                title="Weekly Sales"
                :options="weeklySalesChartOptions"
            />
            <Chart
                v-if="
                    monthlySalesChartOptions.series &&
                    monthlySalesChartOptions.series.length > 0
                "
                title="Monthly Sales"
                :options="monthlySalesChartOptions"
            />
            <Chart
                v-if="
                    yearlySalesChartOptions.series &&
                    yearlySalesChartOptions.series.length > 0
                "
                title="Yearly Sales"
                :options="yearlySalesChartOptions"
            />
            <Chart
                v-if="
                    categoryChartOptions.series &&
                    categoryChartOptions.series.length > 0
                "
                title="Top Categories"
                :options="categoryChartOptions"
            />
        </div>

        <div class="top-items">
            <h3>Top Items by Sales</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product ID</th>
                        <th>Items Sold</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in dashboardData.topItems"
                        :key="item.product_id"
                    >
                        <td>{{ item.product_name }}</td>
                        <td>{{ item.product_id }}</td>
                        <td>{{ item.total_sales }} items</td>
                        <td>
                            ${{
                                item.amount && !isNaN(item.amount)
                                    ? item.amount.toFixed(2)
                                    : "0.00"
                            }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Card from "./reports/Card.vue";
import Chart from "./reports/Chart.vue";
import axios from "axios";

export default {
    components: { Card, Chart },
    data() {
        return {
            dashboardData: {
                totalSales: 0,
                totalOrders: 0,
                topSellingItem: {
                    product_name: "Loading...",
                    category_name: "Loading...",
                },
                topItems: [],
                monthlySales: 0,
                weeklySales: [],
                categoryChart: [],
                salesChart: [],
            },
            weeklySalesChartOptions: { series: [] },
            monthlySalesChartOptions: { series: [] },
            yearlySalesChartOptions: { series: [] },
            categoryChartOptions: { series: [] },
            selectedMonth: new Date().getMonth() + 1,
            selectedYear: new Date().getFullYear(),
            selectedWeek: 1,
            months: [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December",
            ],
            years: [2023, 2024, 2025],
            weeks: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        };
    },
    async created() {
        this.fetchData();
    },
    methods: {
        async fetchData() {
            try {
                const response = await axios.get("/api/dashboard", {
                    params: {
                        month: this.selectedMonth,
                        year: this.selectedYear,
                        week: this.selectedWeek,
                    },
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "access_token"
                        )}`,
                    },
                });

                console.log("API Response:", response.data); // Check API response structure
                if (response.data) {
                    this.dashboardData = response.data;
                    this.setChartData();
                }
            } catch (error) {
                console.error("Error fetching dashboard data:", error);
            }
        },
        setChartData() {
            // Ensure values are valid numbers before mapping
            this.dashboardData.topItems = this.dashboardData.topItems.map(
                (item) => {
                    return {
                        product_id: item.product_id,
                        product_name: item.product_name,
                        total_sales: item.total_sales,
                        amount: isNaN(Number(item.amount))
                            ? 0
                            : Number(item.amount),
                    };
                }
            );

            // Set the data for weekly, monthly, and yearly charts
            this.weeklySalesChartOptions = {
                chart: { type: "line" },
                series: [
                    {
                        name: "Weekly Sales",
                        data: this.dashboardData.weeklySales.length
                            ? this.dashboardData.weeklySales.map(
                                  (item) => item.total_sales
                              )
                            : [0],
                    },
                ],
                xaxis: {
                    categories: this.dashboardData.weeklySales.length
                        ? this.dashboardData.weeklySales.map(
                              (item) => `Week ${item.week}`
                          )
                        : ["Week 1"],
                },
            };

            this.monthlySalesChartOptions = {
                chart: { type: "bar" },
                series: [
                    {
                        name: "Monthly Sales",
                        data: this.dashboardData.salesChart.map(
                            (item) => item.total_sales
                        ),
                    },
                ],
                xaxis: {
                    categories: this.dashboardData.salesChart.map(
                        (item) => `${item.month} ${item.year}`
                    ),
                },
            };

            this.yearlySalesChartOptions = {
                chart: { type: "bar" },
                series: [
                    {
                        name: "Yearly Sales",
                        data: this.dashboardData.salesChart.map(
                            (item) => item.total_sales
                        ),
                    },
                ],
                xaxis: {
                    categories: this.dashboardData.salesChart.map(
                        (item) => `Year ${item.year}`
                    ),
                },
            };

            this.categoryChartOptions = {
                chart: { type: "pie" },
                series: this.dashboardData.categoryChart.map(
                    (item) => item.count
                ),
                labels: this.dashboardData.categoryChart.map(
                    (item) => item.category_name
                ),
            };
        },
    },
};
</script>

<style scoped>
.dashboard {
    padding: 20px;
}
.grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
.charts {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}
.filters {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}
.top-items {
    margin-top: 20px;
}
.top-items table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
.top-items th,
.top-items td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}
.top-items th {
    background-color: #f4f4f4;
}
</style>
