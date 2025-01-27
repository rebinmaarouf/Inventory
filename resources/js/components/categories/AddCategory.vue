<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-12">
                <!-- Insert Button -->
                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >
                    <h1 class="h4 text-gray-900">📦 Category Insert</h1>
                    <button class="btn btn-success" @click="goToCategoryList">
                        <i class="fas fa-list"></i> All Categories
                    </button>
                </div>

                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form @submit.prevent="saveCategory">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input
                                    v-model="category.category_name"
                                    type="text"
                                    class="form-control"
                                    placeholder="Category Name"
                                    required
                                />
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

                <div class="text-muted text-center mt-3">
                    Updated yesterday at 11:59 PM
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
            category: {
                category_name: "",
            },
        };
    },
    methods: {
        async saveCategory() {
            try {
                await axios.post("/api/categories", this.category);
                Swal.fire("Success", "Category added successfully!", "success");
                this.resetForm();
            } catch (error) {
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        resetForm() {
            this.category = {
                category_name: "",
            };
        },
        goToCategoryList() {
            this.$router.push("/all-categories");
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
    height: 40vh;
    display: flex;
    align-items: center;
}
</style>
