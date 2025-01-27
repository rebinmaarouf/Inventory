<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-12">
                <!-- Header and Button -->
                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >
                    <h1 class="h4 text-gray-900">💰 Add Salary</h1>
                    <button class="btn btn-success" @click="goToSalaryList">
                        <i class="fas fa-list"></i> All Salaries
                    </button>
                </div>

                <!-- Salary Form -->
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form @submit.prevent="saveSalary">
                            <!-- Employee Dropdown -->
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <label>Employee</label>
                                    <select
                                        v-model="salary.employee_id"
                                        class="form-control"
                                        required
                                    >
                                        <option value="" disabled>
                                            Select Employee
                                        </option>
                                        <option
                                            v-for="employee in employees"
                                            :key="employee.id"
                                            :value="employee.id"
                                        >
                                            {{ employee.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label>Amount</label>
                                    <input
                                        v-model="salary.amount"
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Amount"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- Salary Date and Month -->
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <label>Salary Date</label>
                                    <input
                                        v-model="salary.salary_date"
                                        type="date"
                                        class="form-control"
                                        required
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <label>Salary Month</label>
                                    <input
                                        v-model="salary.salary_month"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Month"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- Salary Year -->
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <label>Salary Year</label>
                                    <input
                                        v-model="salary.salary_year"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Year"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    data() {
        return {
            salary: {
                employee_id: null,
                amount: null,
                salary_date: "",
                salary_month: "",
                salary_year: "",
            },
            employees: [],
        };
    },
    created() {
        this.fetchEmployees();
    },
    methods: {
        async fetchEmployees() {
            try {
                const response = await axios.get("/api/employees");
                this.employees = response.data;
            } catch (error) {
                console.error("Error fetching employees:", error);
            }
        },
        async saveSalary() {
            try {
                await axios.post("/api/salaries", this.salary);
                Swal.fire("Success", "Salary saved successfully!", "success");
                this.resetForm();
                this.goToSalaryList();
            } catch (error) {
                console.error("Error saving salary:", error);
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        resetForm() {
            this.salary = {
                employee_id: null,
                amount: null,
                salary_date: "",
                salary_month: "",
                salary_year: "",
            };
        },
        goToSalaryList() {
            this.$router.push("/all-salaries");
        },
    },
};
</script>

<style scoped>
.card {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
}
.vh {
    height: 50vh;
    display: flex;
    align-items: center;
}
</style>
