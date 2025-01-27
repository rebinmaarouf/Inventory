<template>
    <div>
        <!-- Search Bar -->
        <div class="mb-3">
            <input
                v-model="searchQuery"
                type="text"
                class="form-control search-bar"
                placeholder="Search categories..."
            />
        </div>

        <!-- Category Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="category in filteredCategories"
                        :key="category.id"
                    >
                        <td>{{ category.category_name }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-primary me-2"
                                @click="editCategory(category)"
                            >
                                Edit
                            </button>
                            <button
                                class="btn btn-sm btn-danger"
                                @click="deleteCategory(category.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Category Modal -->
        <div v-if="showEditModal" class="modal-backdrop">
            <div class="modal-content">
                <h2>Edit Category</h2>
                <form @submit.prevent="updateCategory">
                    <div class="mb-3">
                        <label>Category Name</label>
                        <input
                            v-model="editCategoryData.category_name"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="text-end">
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
            categories: [],
            searchQuery: "",
            showEditModal: false,
            editCategoryData: {
                id: null,
                category_name: "",
            },
        };
    },
    computed: {
        filteredCategories() {
            return this.categories.filter((category) => {
                return category.category_name
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase());
            });
        },
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        async fetchCategories() {
            try {
                const response = await axios.get("/api/categories");
                this.categories = response.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
            }
        },
        editCategory(category) {
            this.editCategoryData = { ...category };
            this.showEditModal = true;
        },
        async updateCategory() {
            try {
                await axios.put(
                    `/api/categories/${this.editCategoryData.id}`,
                    this.editCategoryData
                );
                this.showEditModal = false;
                this.fetchCategories();
                Swal.fire("Updated!", "Category has been updated.", "success");
            } catch (error) {
                console.error(
                    "Error updating category:",
                    error.response?.data || error.message
                );
            }
        },
        async deleteCategory(id) {
            try {
                await axios.delete(`/api/categories/${id}`);
                this.fetchCategories();
                Swal.fire("Deleted!", "Category has been deleted.", "success");
            } catch (error) {
                console.error(
                    "Error deleting category:",
                    error.response?.data || error.message
                );
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
    width: 400px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
