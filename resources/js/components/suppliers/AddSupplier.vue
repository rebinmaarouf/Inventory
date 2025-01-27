<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-12">
                <!-- Insert Button -->
                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >
                    <h1 class="h4 text-gray-900">🧑‍💼 Supplier Insert</h1>
                    <button class="btn btn-success" @click="goToSupplierList">
                        <i class="fas fa-users"></i> All Suppliers
                    </button>
                </div>

                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form @submit.prevent="saveSupplier">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="supplier.name"
                                        type="text"
                                        class="form-control"
                                        placeholder="Full Name"
                                        required
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <input
                                        v-model="supplier.email"
                                        type="email"
                                        class="form-control"
                                        placeholder="Email Address"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="supplier.phone"
                                        type="text"
                                        class="form-control"
                                        placeholder="Phone Number"
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <input
                                        v-model="supplier.shop_name"
                                        type="text"
                                        class="form-control"
                                        placeholder="Shop Name"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea
                                    v-model="supplier.address"
                                    class="form-control"
                                    placeholder="Address"
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <input
                                    type="file"
                                    @change="handleFileUpload"
                                    class="form-control"
                                />
                            </div>

                            <div v-if="photoPreview" class="text-center mb-3">
                                <img
                                    :src="photoPreview"
                                    alt="Supplier Photo"
                                    class="img-thumbnail"
                                    width="150"
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
            supplier: {
                name: "",
                email: "",
                phone: "",
                shop_name: "",
                address: "",
                photo: null,
            },
            photoPreview: null,
        };
    },
    methods: {
        async saveSupplier() {
            const formData = new FormData();
            Object.keys(this.supplier).forEach((key) => {
                formData.append(key, this.supplier[key]);
            });

            if (this.supplier.photo) {
                formData.append("photo", this.supplier.photo);
            }

            try {
                await axios.post("/api/suppliers", formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });
                Swal.fire("Success", "Supplier added successfully!", "success");
                this.resetForm();
            } catch (error) {
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.supplier.photo = file;

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        resetForm() {
            this.supplier = {
                name: "",
                email: "",
                phone: "",
                shop_name: "",
                address: "",
                photo: null,
            };
            this.photoPreview = null;
        },
        goToSupplierList() {
            this.$router.push("/all-suppliers");
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
    height: 75vh;
    display: flex;
    align-items: center;
}
.img-thumbnail {
    border: 2px solid #007bff;
}
</style>
