<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-12">
                <!-- Header and Button -->
                <div
                    class="d-flex justify-content-between align-items-center mb-4"
                >
                    <h1 class="h4 text-gray-900">👤 Customer Insert</h1>
                    <button class="btn btn-success" @click="goToCustomerList">
                        <i class="fas fa-users"></i> All Customers
                    </button>
                </div>

                <!-- Customer Form -->
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form @submit.prevent="saveCustomer">
                            <!-- Customer Information Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Customer Information</h2>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="name">Name</label>
                                        <input
                                            v-model="customer.name"
                                            type="text"
                                            class="form-control"
                                            id="name"
                                            placeholder="e.g., John Doe"
                                            required
                                        />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="email">Email</label>
                                        <input
                                            v-model="customer.email"
                                            type="email"
                                            class="form-control"
                                            id="email"
                                            placeholder="e.g., john.doe@example.com"
                                        />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="phone">Phone</label>
                                        <input
                                            v-model="customer.phone"
                                            type="text"
                                            class="form-control"
                                            id="phone"
                                            placeholder="e.g., +1234567890"
                                            required
                                        />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="address">Address</label>
                                        <input
                                            v-model="customer.address"
                                            type="text"
                                            class="form-control"
                                            id="address"
                                            placeholder="e.g., 123 Main St"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Customer Photo</h2>
                                <div class="form-group">
                                    <label for="photo">Upload Photo</label>
                                    <input
                                        type="file"
                                        @change="handleFileUpload"
                                        class="form-control"
                                        id="photo"
                                    />
                                </div>
                                <div
                                    v-if="photoPreview"
                                    class="text-center mt-3"
                                >
                                    <img
                                        :src="photoPreview"
                                        alt="Customer Photo"
                                        class="img-thumbnail"
                                        width="150"
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
            customer: {
                name: "",
                email: "",
                phone: "",
                address: "",
                photo: null,
            },
            photoPreview: null,
        };
    },
    methods: {
        async saveCustomer() {
            const formData = new FormData();
            Object.keys(this.customer).forEach((key) => {
                formData.append(key, this.customer[key]);
            });

            if (this.customer.photo) {
                formData.append("photo", this.customer.photo);
            }

            try {
                const url = "/api/customers";
                await axios.post(url, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                Swal.fire("Success", "Customer saved successfully!", "success");
                this.resetForm();
                this.goToCustomerList();
            } catch (error) {
                console.error(
                    "Error saving customer:",
                    error.response?.data || error.message
                );
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.customer.photo = file;

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        resetForm() {
            this.customer = {
                name: "",
                email: "",
                phone: "",
                address: "",
                photo: null,
            };
            this.photoPreview = null;
        },
        goToCustomerList() {
            this.$router.push("/all-customers");
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
.img-thumbnail {
    border: 2px solid #007bff;
}
</style>
