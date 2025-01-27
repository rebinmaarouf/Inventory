<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-12">
                <!-- Header with button aligned to the right -->
                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >
                    <h1 class="h4 text-gray-900">📦 Add Expense</h1>
                    <button class="btn btn-success" @click="goToExpenseList">
                        <i class="fas fa-boxes"></i> All Expenses
                    </button>
                </div>

                <!-- Expense Form -->
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form @submit.prevent="saveExpense">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="expense.details"
                                        type="text"
                                        class="form-control"
                                        placeholder="Enter Details"
                                        required
                                    />
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="expense.amount"
                                        type="number"
                                        class="form-control"
                                        placeholder="Enter Amount"
                                        required
                                    />
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="expense.expenses_date"
                                        type="date"
                                        class="form-control"
                                        required
                                    />
                                </div>
                            </div>

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
            expense: {
                details: "",
                amount: 0,
                expenses_date: new Date().toISOString().split("T")[0], // Default to today's date
            },
        };
    },
    methods: {
        async saveExpense() {
            try {
                const response = await axios.post(
                    "/api/expenses",
                    this.expense
                );
                Swal.fire("Success", "Expense saved successfully!", "success");
                this.resetForm();
                this.goToExpenseList();
            } catch (error) {
                console.error("Error saving expense:", error);
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        resetForm() {
            this.expense = {
                details: "",
                amount: 0,
                expenses_date: new Date().toISOString().split("T")[0], // Reset to today's date
            };
        },
        goToExpenseList() {
            this.$router.push("/all-expenses");
        },
    },
};
</script>
