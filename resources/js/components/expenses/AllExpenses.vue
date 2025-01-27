<template>
    <div>
        <div class="mb-3">
            <input
                v-model="searchQuery"
                type="text"
                class="form-control search-bar"
                placeholder="Search expenses..."
            />
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Details</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="expense in filteredExpenses" :key="expense.id">
                        <td>{{ expense.details }}</td>
                        <td>{{ expense.amount }}</td>
                        <td>{{ expense.expenses_date }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-primary me-2"
                                @click="editExpense(expense)"
                            >
                                Edit
                            </button>
                            <button
                                class="btn btn-sm btn-danger"
                                @click="deleteExpense(expense.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showEditModal" class="modal-backdrop">
            <div class="modal-content">
                <h2>Edit Expense</h2>
                <form @submit.prevent="updateExpense">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Details</label>
                            <input
                                v-model="editExpenseData.details"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Amount</label>
                            <input
                                v-model="editExpenseData.amount"
                                type="number"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Date</label>
                            <input
                                v-model="editExpenseData.expenses_date"
                                type="date"
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
            expenses: [],
            searchQuery: "",
            showEditModal: false,
            editExpenseData: {
                id: null,
                details: "",
                amount: 0,
                expenses_date: "",
            },
        };
    },
    computed: {
        filteredExpenses() {
            return this.expenses.filter((expense) => {
                const searchLower = this.searchQuery.toLowerCase();

                // Check if the search query matches details, amount, or date
                return (
                    expense.details.toLowerCase().includes(searchLower) || // Search by details
                    expense.amount.toString().includes(searchLower) || // Search by amount
                    expense.expenses_date.includes(searchLower) // Search by date
                );
            });
        },
    },
    created() {
        this.fetchExpenses();
    },
    methods: {
        async fetchExpenses() {
            try {
                const response = await axios.get("/api/expenses");
                this.expenses = response.data;
            } catch (error) {
                console.error("Error fetching expenses:", error);
            }
        },
        editExpense(expense) {
            this.editExpenseData = { ...expense };
            this.showEditModal = true;
        },
        async updateExpense() {
            try {
                await axios.put(
                    `/api/expenses/${this.editExpenseData.id}`,
                    this.editExpenseData
                );
                this.showEditModal = false;
                this.fetchExpenses();
                Swal.fire("Updated!", "Expense has been updated.", "success");
            } catch (error) {
                console.error("Error updating expense:", error);
            }
        },
        async deleteExpense(id) {
            try {
                await axios.delete(`/api/expenses/${id}`);
                this.fetchExpenses();
                Swal.fire("Deleted!", "Expense has been deleted.", "success");
            } catch (error) {
                console.error("Error deleting expense:", error);
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
