<template>
    <div>
        <!-- Search Bar -->
        <div class="mb-3">
            <input
                v-model="searchQuery"
                type="text"
                class="form-control search-bar"
                placeholder="Search products..."
            />
            <input
                v-model="barcodeQuery"
                type="text"
                class="form-control search-bar mt-2"
                placeholder="Scan barcode..."
                @input="searchByBarcode"
            />
        </div>

        <!-- Product Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Barcode</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Quantity</th>
                        <th>Buying Date</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Notification Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in filteredProducts" :key="product.id">
                        <td>
                            <img
                                :src="getProductImage(product.image)"
                                alt="Product Image"
                                class="product-image"
                            />
                        </td>
                        <td>{{ product.product_name }}</td>
                        <td>{{ product.product_code }}</td>
                        <td>{{ product.buying_price }}</td>
                        <td>{{ product.selling_price }}</td>
                        <td :class="getStockStatusClass(product.quantity)">
                            {{ product.quantity }}
                        </td>
                        <td>{{ product.buying_date }}</td>
                        <td>
                            {{
                                product.category
                                    ? product.category.category_name
                                    : "N/A"
                            }}
                        </td>
                        <td>
                            {{
                                product.supplier ? product.supplier.name : "N/A"
                            }}
                        </td>
                        <td>
                            <span
                                v-if="product.quantity === 0"
                                class="badge badge-danger"
                                >Out of Stock</span
                            >
                            <span
                                v-else-if="product.quantity < 10"
                                class="badge badge-warning"
                                >Low Stock</span
                            >
                            <span v-else class="badge badge-success"
                                >Available</span
                            >
                        </td>
                        <td>
                            <button
                                class="btn btn-sm btn-primary me-2"
                                @click="editProduct(product)"
                            >
                                Edit
                            </button>
                            <button
                                class="btn btn-sm btn-danger"
                                @click="deleteProduct(product.product_code)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Product Modal -->
        <div v-if="showEditModal" class="modal-backdrop">
            <div class="modal-content">
                <h2>Edit Product</h2>
                <form @submit.prevent="updateProduct">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Product Name</label>
                            <input
                                v-model="editProductData.product_name"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Barcode</label>
                            <input
                                v-model="editProductData.product_code"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Buying Price</label>
                            <input
                                v-model="editProductData.buying_price"
                                type="number"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Selling Price</label>
                            <input
                                v-model="editProductData.selling_price"
                                type="number"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Quantity</label>
                            <input
                                v-model="editProductData.quantity"
                                type="number"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Root</label>
                            <input
                                v-model="editProductData.root"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Buying Date</label>
                            <input
                                v-model="editProductData.buying_date"
                                type="date"
                                class="form-control"
                                required
                            />
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea
                                v-model="editProductData.description"
                                class="form-control"
                            ></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Category</label>
                            <select
                                v-model="editProductData.category_id"
                                class="form-control"
                                required
                            >
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.category_name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Supplier</label>
                            <select
                                v-model="editProductData.supplier_id"
                                class="form-control"
                                required
                            >
                                <option
                                    v-for="supplier in suppliers"
                                    :key="supplier.id"
                                    :value="supplier.id"
                                >
                                    {{ supplier.name }}
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
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
            products: [],
            searchQuery: "",
            barcodeQuery: "",
            showEditModal: false,
            editProductData: {
                id: null,
                product_name: "",
                product_code: "",
                buying_price: 0,
                selling_price: 0,
                quantity: 0,
                root: "",
                buying_date: "",
                description: "",
                image: null,
                category_id: null,
                supplier_id: null,
            },
            categories: [],
            suppliers: [],
        };
    },
    computed: {
        filteredProducts() {
            return this.products.filter((product) =>
                product.product_name
                    .toLowerCase()
                    .includes(this.searchQuery.toLowerCase())
            );
        },
        lowStockProducts() {
            return this.products.filter((product) => product.quantity <= 10);
        },
        outOfStockProducts() {
            return this.products.filter((product) => product.quantity === 0);
        },
    },
    created() {
        this.fetchProducts();
        this.fetchCategories();
        this.fetchSuppliers();
    },
    methods: {
        async fetchProducts() {
            try {
                const response = await axios.get("/api/products");
                this.products = response.data;
                this.checkStockStatus();
            } catch (error) {
                console.error("Error fetching products:", error);
            }
        },
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
        editProduct(product) {
            this.editProductData = { ...product, image: null };
            this.showEditModal = true;
        },
        async updateProduct() {
            const formData = new FormData();
            formData.append("product_name", this.editProductData.product_name);
            formData.append("product_code", this.editProductData.product_code);
            formData.append("buying_price", this.editProductData.buying_price);
            formData.append(
                "selling_price",
                this.editProductData.selling_price
            );
            formData.append("quantity", this.editProductData.quantity);
            formData.append("root", this.editProductData.root);
            formData.append("buying_date", this.editProductData.buying_date);
            formData.append("description", this.editProductData.description);
            formData.append("category_id", this.editProductData.category_id);
            formData.append("supplier_id", this.editProductData.supplier_id);

            if (this.editProductData.image) {
                formData.append("image", this.editProductData.image);
            }

            try {
                await axios.post(
                    `/api/products/barcode/${this.editProductData.product_code}?_method=PUT`,
                    formData,
                    { headers: { "Content-Type": "multipart/form-data" } }
                );
                this.showEditModal = false;
                await this.fetchProducts();
                Swal.fire("Updated!", "Product has been updated.", "success");
                setTimeout(() => {
                    this.checkStockStatus(); // Delay checkStockStatus by 500ms
                }, 500);
            } catch (error) {
                console.error(
                    "Error updating product:",
                    error.response?.data || error.message
                );
                Swal.fire("Error", "Failed to update product.", "error");
            }
        },
        handleFileUpload(event) {
            this.editProductData.image = event.target.files[0];
        },
        async deleteProduct(barcode) {
            // Show a confirmation dialog before deleting
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

            // If the user confirms, proceed with deletion
            if (result.isConfirmed) {
                try {
                    // Show a loading spinner
                    Swal.fire({
                        title: "Deleting...",
                        text: "Please wait while we delete the product.",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    await axios.delete(`/api/products/barcode/${barcode}`);
                    await this.fetchProducts(); // Refresh the product list

                    // Close the loading spinner and show success message
                    Swal.close();
                    Swal.fire(
                        "Deleted!",
                        "Product has been deleted.",
                        "success"
                    );

                    // Check stock status after a delay
                    setTimeout(() => {
                        this.checkStockStatus();
                    }, 500);
                } catch (error) {
                    // Close the loading spinner and show error message
                    Swal.close();
                    console.error("Error deleting product:", error);
                    Swal.fire("Error", "Failed to delete product.", "error");
                }
            } else {
                // If the user cancels, show a message (optional)
                Swal.fire("Cancelled", "Your product is safe :)", "info");
            }
        },
        getProductImage(image) {
            return image
                ? `/storage/${image}`
                : "https://via.placeholder.com/50";
        },
        getStockStatusClass(quantity) {
            if (quantity === 0) {
                return "text-danger"; // Red for out-of-stock
            } else if (quantity <= 10) {
                return "text-warning"; // Yellow for low stock
            }
            return ""; // Default
        },
        checkStockStatus() {
            if (this.lowStockProducts.length > 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Low Stock Alert",
                    text: `${this.lowStockProducts.length} products are low in stock.`,
                });
            }
            if (this.outOfStockProducts.length > 0) {
                Swal.fire({
                    icon: "error",
                    title: "Out of Stock Alert",
                    text: `${this.outOfStockProducts.length} products are out of stock.`,
                });
            }
        },
        async searchByBarcode() {
            if (this.barcodeQuery) {
                try {
                    const response = await axios.get(
                        `/api/products/barcode/${this.barcodeQuery}`
                    );
                    this.products = [response.data];
                } catch (error) {
                    console.error("Error searching by barcode:", error);
                    Swal.fire("Error", "Product not found.", "error");
                }
            } else {
                this.fetchProducts();
            }
        },
        async fetchNotifications() {
            try {
                const response = await axios.get("/api/products");
                this.notifications = response.data
                    .filter((product) => product.quantity <= 10)
                    .map((product) => ({
                        id: product.id,
                        productId: product.id,
                        message: `${product.product_name} is ${
                            product.quantity === 0
                                ? "out of stock"
                                : "low in stock"
                        }.`,
                        date: new Date().toLocaleDateString(),
                        class:
                            product.quantity === 0 ? "bg-danger" : "bg-warning",
                    }));
            } catch (error) {
                console.error("Error fetching notifications:", error);
            }
        },
        startNotificationPolling() {
            setInterval(this.fetchNotifications, 60000); // Fetch notifications every minute
        },
    },
    mounted() {
        this.fetchNotifications();
        this.startNotificationPolling();
    },
};
</script>

<style scoped>
.search-bar {
    max-width: 400px;
    margin-bottom: 20px;
}

.product-image {
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

.text-danger {
    color: red;
}

.text-warning {
    color: orange;
}

.badge {
    padding: 5px 10px;
    border-radius: 5px;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
}

.badge-warning {
    background-color: #ffc107;
}

.badge-success {
    background-color: #28a745;
}
</style>
