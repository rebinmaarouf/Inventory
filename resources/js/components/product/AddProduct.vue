<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-12">
                <!-- Header and Button -->
                <div
                    class="d-flex justify-content-between align-items-center mb-4"
                >
                    <h1 class="h4 text-gray-900">📦 Product Insert</h1>
                    <button class="btn btn-success" @click="goToProductList">
                        <i class="fas fa-boxes"></i> All Products
                    </button>
                </div>

                <!-- Product Form -->
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form @submit.prevent="saveProduct">
                            <!-- Product Information Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Product Information</h2>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="productName"
                                            >Product Name</label
                                        >
                                        <input
                                            v-model="product.product_name"
                                            type="text"
                                            class="form-control"
                                            id="productName"
                                            placeholder="e.g., Apple iPhone 12"
                                            required
                                        />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="barcode">Barcode</label>
                                        <input
                                            v-model="product.product_code"
                                            type="text"
                                            class="form-control"
                                            id="barcode"
                                            placeholder="e.g., 123456789012"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Pricing</h2>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="buyingPrice"
                                            >Buying Price</label
                                        >
                                        <input
                                            v-model="product.buying_price"
                                            type="number"
                                            class="form-control"
                                            id="buyingPrice"
                                            placeholder="e.g., 500"
                                            required
                                        />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="sellingPrice"
                                            >Selling Price</label
                                        >
                                        <input
                                            v-model="product.selling_price"
                                            type="number"
                                            class="form-control"
                                            id="sellingPrice"
                                            placeholder="e.g., 700"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Inventory</h2>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="quantity">Quantity</label>
                                        <input
                                            v-model="product.quantity"
                                            type="number"
                                            class="form-control"
                                            id="quantity"
                                            placeholder="e.g., 100"
                                            required
                                        />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="root">Root</label>
                                        <input
                                            v-model="product.root"
                                            type="text"
                                            class="form-control"
                                            id="root"
                                            placeholder="e.g., A1"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Additional Information</h2>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="buyingDate"
                                            >Buying Date</label
                                        >
                                        <input
                                            v-model="product.buying_date"
                                            type="date"
                                            class="form-control"
                                            id="buyingDate"
                                            required
                                        />
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="description"
                                            >Description</label
                                        >
                                        <textarea
                                            v-model="product.description"
                                            class="form-control"
                                            id="description"
                                            placeholder="Enter a brief description"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Category and Supplier Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Category & Supplier</h2>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <label for="category">Category</label>
                                        <select
                                            v-model="product.category_id"
                                            class="form-control"
                                            id="category"
                                            required
                                        >
                                            <option value="" disabled>
                                                Select Category
                                            </option>
                                            <option
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id"
                                            >
                                                {{ category.category_name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="supplier">Supplier</label>
                                        <select
                                            v-model="product.supplier_id"
                                            class="form-control"
                                            id="supplier"
                                            required
                                        >
                                            <option value="" disabled>
                                                Select Supplier
                                            </option>
                                            <option
                                                v-for="supplier in suppliers"
                                                :key="supplier.id"
                                                :value="supplier.id"
                                            >
                                                {{ supplier.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload Section -->
                            <div class="mb-4">
                                <h2 class="h5 mb-3">Product Image</h2>
                                <div class="form-group">
                                    <label for="productImage"
                                        >Upload Image</label
                                    >
                                    <input
                                        type="file"
                                        @change="handleFileUpload"
                                        class="form-control"
                                        id="productImage"
                                    />
                                </div>
                                <div
                                    v-if="photoPreview"
                                    class="text-center mt-3"
                                >
                                    <img
                                        :src="photoPreview"
                                        alt="Product Image"
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
            product: {
                product_name: "",
                product_code: "",
                buying_price: 0,
                selling_price: 0,
                quantity: 0,
                root: "",
                description: "",
                buying_date: "",
                image: null,
                category_id: null,
                supplier_id: null,
            },
            categories: [],
            suppliers: [],
            photoPreview: null,
        };
    },
    created() {
        this.fetchCategories();
        this.fetchSuppliers();
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
        async fetchSuppliers() {
            try {
                const response = await axios.get("/api/suppliers");
                this.suppliers = response.data;
            } catch (error) {
                console.error("Error fetching suppliers:", error);
            }
        },
        async saveProduct() {
            const formData = new FormData();
            Object.keys(this.product).forEach((key) => {
                formData.append(key, this.product[key]);
            });

            if (this.product.image) {
                formData.append("image", this.product.image);
            }

            try {
                const url = this.product.id
                    ? `/api/products/barcode/${this.product.product_code}?_method=PUT`
                    : "/api/products";

                await axios.post(url, formData, {
                    headers: { "Content-Type": "multipart/form-data" },
                });

                Swal.fire("Success", "Product saved successfully!", "success");
                this.resetForm();
                this.goToProductList();
            } catch (error) {
                console.error(
                    "Error saving product:",
                    error.response?.data || error.message
                );
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.product.image = file;

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        resetForm() {
            this.product = {
                product_name: "",
                product_code: "",
                buying_price: 0,
                selling_price: 0,
                quantity: 0,
                root: "",
                description: "",
                buying_date: "",
                image: null,
                category_id: null,
                supplier_id: null,
            };
            this.photoPreview = null;
        },
        goToProductList() {
            this.$router.push("/all-products");
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
