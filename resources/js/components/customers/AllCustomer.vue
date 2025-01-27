<template>
    <div>
        <!-- Search Bar -->
        <div class="mb-3">
            <input
                v-model="searchQuery"
                type="text"
                class="form-control search-bar"
                placeholder="Search customers..."
            />
        </div>

        <!-- Customer Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="customer in filteredCustomers"
                        :key="customer.id"
                    >
                        <td>
                            <img
                                :src="getCustomerPhoto(customer.photo)"
                                alt="Customer Photo"
                                class="customer-image"
                            />
                        </td>
                        <td>{{ customer.name }}</td>
                        <td>{{ customer.email }}</td>
                        <td>{{ customer.phone }}</td>
                        <td>{{ customer.address }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-primary me-2"
                                @click="editCustomer(customer)"
                            >
                                Edit
                            </button>
                            <button
                                class="btn btn-sm btn-danger"
                                @click="deleteCustomer(customer.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Customer Modal -->
        <div v-if="showEditModal" class="modal-backdrop">
            <div class="modal-content">
                <h2>Edit Customer</h2>
                <form @submit.prevent="updateCustomer">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input
                                v-model="editCustomerData.name"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input
                                v-model="editCustomerData.email"
                                type="email"
                                class="form-control"
                            />
                        </div>
                        <div class="mb-3">
                            <label>Phone</label>
                            <input
                                v-model="editCustomerData.phone"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Address</label>
                            <input
                                v-model="editCustomerData.address"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Photo</label>
                            <input
                                type="file"
                                @change="handleFileUpload"
                                class="form-control"
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
            customers: [],
            searchQuery: "",
            showEditModal: false,
            editCustomerData: {
                id: null,
                name: "",
                email: "",
                phone: "",
                address: "",
                photo: null,
            },
        };
    },
    computed: {
        filteredCustomers() {
            return this.customers.filter((customer) =>
                customer.name
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase())
            );
        },
    },
    created() {
        this.fetchCustomers();
    },
    methods: {
        async fetchCustomers() {
            try {
                const response = await axios.get("/api/customers");
                this.customers = response.data;
            } catch (error) {
                console.error("Error fetching customers:", error);
            }
        },
        editCustomer(customer) {
            this.editCustomerData = { ...customer, photo: null };
            this.showEditModal = true;
        },
        async updateCustomer() {
            const formData = new FormData();
            formData.append("name", this.editCustomerData.name);
            formData.append("email", this.editCustomerData.email);
            formData.append("phone", this.editCustomerData.phone);
            formData.append("address", this.editCustomerData.address);

            if (this.editCustomerData.photo) {
                formData.append("photo", this.editCustomerData.photo);
            }

            try {
                await axios.post(
                    `/api/customers/${this.editCustomerData.id}?_method=PUT`,
                    formData,
                    { headers: { "Content-Type": "multipart/form-data" } }
                );
                this.showEditModal = false;
                await this.fetchCustomers();
                Swal.fire("Updated!", "Customer has been updated.", "success");
            } catch (error) {
                console.error(
                    "Error updating customer:",
                    error.response?.data || error.message
                );
                Swal.fire("Error", "Failed to update customer.", "error");
            }
        },
        handleFileUpload(event) {
            this.editCustomerData.photo = event.target.files[0];
        },
        async deleteCustomer(id) {
            const result = await Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            });

            if (result.isConfirmed) {
                try {
                    Swal.fire({
                        title: "Deleting...",
                        text: "Please wait while we delete the customer.",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    await axios.delete(`/api/customers/${id}`);
                    await this.fetchCustomers();

                    Swal.close();
                    Swal.fire(
                        "Deleted!",
                        "Customer has been deleted.",
                        "success"
                    );
                } catch (error) {
                    Swal.close();
                    console.error("Error deleting customer:", error);
                    Swal.fire("Error", "Failed to delete customer.", "error");
                }
            } else {
                Swal.fire("Cancelled", "Your customer is safe :)", "info");
            }
        },
        getCustomerPhoto(photo) {
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

.customer-image {
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
