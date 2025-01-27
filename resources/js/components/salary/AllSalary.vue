<template>
    <div>
        <!-- Search Bar -->
        <div class="mb-3">
            <input
                v-model="searchQuery"
                type="text"
                class="form-control search-bar"
                placeholder="Search salaries..."
                @input="fetchSalaries"
            />
        </div>

        <!-- Salary Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="salary in salaries" :key="salary.id">
                        <td>
                            {{ salary.employee ? salary.employee.name : "N/A" }}
                        </td>
                        <td>{{ salary.amount }}</td>
                        <td>{{ salary.salary_date }}</td>
                        <td>{{ salary.salary_month }}</td>
                        <td>{{ salary.salary_year }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-primary me-2"
                                @click="editSalary(salary)"
                            >
                                Edit
                            </button>
                            <button
                                class="btn btn-sm btn-danger"
                                @click="deleteSalary(salary.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Salary Modal -->
        <div v-if="showEditModal" class="modal-backdrop">
            <div class="modal-content">
                <h2>Edit Salary</h2>
                <form @submit.prevent="updateSalary">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Employee</label>
                            <select
                                v-model="editSalaryData.employee_id"
                                class="form-control"
                                required
                            >
                                <option
                                    v-for="employee in employees"
                                    :key="employee.id"
                                    :value="employee.id"
                                >
                                    {{ employee.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Amount</label>
                            <input
                                v-model="editSalaryData.amount"
                                type="number"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Date</label>
                            <input
                                v-model="editSalaryData.salary_date"
                                type="date"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Month</label>
                            <input
                                v-model="editSalaryData.salary_month"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Year</label>
                            <input
                                v-model="editSalaryData.salary_year"
                                class="form-control"
                                required
                            />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary me-2"
                            @click="showEditModal = false"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </form>
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
            salaries: [],
            employees: [],
            searchQuery: "",
            showEditModal: false,
            editSalaryData: {
                id: null,
                employee_id: null,
                amount: null,
                salary_date: "",
                salary_month: "",
                salary_year: "",
            },
        };
    },
    created() {
        this.fetchSalaries();
        this.fetchEmployees();
    },
    methods: {
        async fetchSalaries() {
            try {
                const token = localStorage.getItem("access_token");
                if (!token) {
                    throw new Error("No access token found");
                }

                const endpoint = "/api/salaries/search";
                const response = await axios.get(endpoint, {
                    params: {
                        search: this.searchQuery, // General search term
                        amount: this.searchQuery, // Search by amount
                        date: this.searchQuery, // Search by date
                        month: this.searchQuery, // Search by month
                        year: this.searchQuery, // Search by year
                    },
                    headers: { Authorization: `Bearer ${token}` },
                });

                console.log("API Response:", response.data); // Log the response
                this.salaries = response.data;
            } catch (error) {
                console.error("Error fetching salaries:", error);
                Swal.fire(
                    "Error",
                    "Failed to fetch salaries. Please try again.",
                    "error"
                );
            }
        },
        async fetchEmployees() {
            try {
                const response = await axios.get("/api/employees");
                this.employees = response.data;
            } catch (error) {
                console.error("Error fetching employees:", error);
            }
        },
        editSalary(salary) {
            this.editSalaryData = { ...salary };
            this.showEditModal = true;
        },
        async updateSalary() {
            try {
                const payload = {
                    employee_id: this.editSalaryData.employee_id,
                    amount: this.editSalaryData.amount,
                    salary_date: this.editSalaryData.salary_date,
                    salary_month: this.editSalaryData.salary_month,
                    salary_year: this.editSalaryData.salary_year,
                };

                await axios.put(
                    `/api/salaries/${this.editSalaryData.id}`,
                    payload
                );
                this.showEditModal = false;
                this.fetchSalaries();
                Swal.fire("Updated!", "Salary has been updated.", "success");
            } catch (error) {
                console.error("Error updating salary:", error);
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        async deleteSalary(id) {
            try {
                const result = await Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                });

                if (result.isConfirmed) {
                    await axios.delete(`/api/salaries/${id}`);
                    this.fetchSalaries();
                    Swal.fire(
                        "Deleted!",
                        "Salary has been deleted.",
                        "success"
                    );
                }
            } catch (error) {
                console.error("Error deleting salary:", error);
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
    },
};
</script>

<style scoped>
.search-bar {
    max-width: 400px;
    margin-bottom: 20px;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 500px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-body {
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 16px;
    border-top: 1px solid #e9ecef;
}
</style>
