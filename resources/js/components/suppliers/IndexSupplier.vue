<template>
    <div>
        <!-- Search Bar -->
        <div class="mb-3">
            <input
                v-model="searchQuery"
                type="text"
                class="form-control search-bar"
                placeholder="Search suppliers..."
            />
        </div>

        <!-- Supplier Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Shop Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="supplier in filteredSuppliers"
                        :key="supplier.id"
                    >
                        <td>
                            <img
                                :src="getSupplierPhoto(supplier.photo)"
                                alt="Supplier Photo"
                                class="supplier-photo"
                            />
                        </td>
                        <td>{{ supplier.name }}</td>
                        <td>{{ supplier.email }}</td>
                        <td>{{ supplier.phone }}</td>
                        <td>{{ supplier.shop_name }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-primary me-2"
                                @click="editSupplier(supplier)"
                            >
                                Edit
                            </button>
                            <button
                                class="btn btn-sm btn-danger"
                                @click="deleteSupplier(supplier.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Supplier Modal -->
        <div v-if="showEditModal" class="modal-backdrop">
            <div class="modal-content">
                <h2>Edit Supplier</h2>
                <form @submit.prevent="updateSupplier">
                    <div class="mb-3">
                        <label>Name</label>
                        <input
                            v-model="editSupplierData.name"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input
                            v-model="editSupplierData.email"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input
                            v-model="editSupplierData.phone"
                            class="form-control"
                        />
                    </div>
                    <div class="mb-3">
                        <label>Shop Name</label>
                        <input
                            v-model="editSupplierData.shop_name"
                            class="form-control"
                        />
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea
                            v-model="editSupplierData.address"
                            class="form-control"
                        ></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Photo</label>
                        <input
                            type="file"
                            @change="handlePhotoUpload"
                            class="form-control"
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
            suppliers: [],
            searchQuery: "",
            showEditModal: false,
            editSupplierData: {
                id: null,
                name: "",
                email: "",
                phone: "",
                shop_name: "",
                address: "",
                photo: null,
            },
        };
    },
    computed: {
        filteredSuppliers() {
            return this.suppliers.filter((supplier) => {
                return (
                    supplier.name
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) ||
                    supplier.email
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) ||
                    supplier.phone
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) ||
                    supplier.shop_name
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase())
                );
            });
        },
    },
    created() {
        this.fetchSuppliers();
    },
    methods: {
        async fetchSuppliers() {
            try {
                const response = await axios.get("/api/suppliers");
                this.suppliers = response.data;
            } catch (error) {
                console.error("Error fetching suppliers:", error);
            }
        },
        editSupplier(supplier) {
            this.editSupplierData = { ...supplier, photo: null };
            this.showEditModal = true;
        },
        async updateSupplier() {
            const formData = new FormData();
            formData.append("name", this.editSupplierData.name);
            formData.append("email", this.editSupplierData.email);
            formData.append("phone", this.editSupplierData.phone);
            formData.append("shop_name", this.editSupplierData.shop_name);
            formData.append("address", this.editSupplierData.address);

            if (this.editSupplierData.photo) {
                formData.append("photo", this.editSupplierData.photo);
            }

            try {
                await axios.post(
                    `/api/suppliers/${this.editSupplierData.id}?_method=PUT`,
                    formData,
                    { headers: { "Content-Type": "multipart/form-data" } }
                );
                this.showEditModal = false;
                this.fetchSuppliers();
                Swal.fire("Updated!", "Supplier has been updated.", "success");
            } catch (error) {
                console.error(
                    "Error updating supplier:",
                    error.response?.data || error.message
                );
            }
        },
        handlePhotoUpload(event) {
            this.editSupplierData.photo = event.target.files[0];
        },
        async deleteSupplier(id) {
            try {
                await axios.delete(`/api/suppliers/${id}`);
                this.fetchSuppliers();
                Swal.fire("Deleted!", "Supplier has been deleted.", "success");
            } catch (error) {
                console.error(
                    "Error deleting supplier:",
                    error.response?.data || error.message
                );
            }
        },
        getSupplierPhoto(photo) {
            return photo
                ? `/storage/${photo}`
                : "https://via.placeholder.com/50";
        },
    },
};
</script>

<style scoped>
.search-bar {
    max-width: 400px;
    margin-bottom: 20px;
}

.supplier-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
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
