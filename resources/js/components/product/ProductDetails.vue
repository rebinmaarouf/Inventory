<template>
    <div>
        <h2>Product Details</h2>
        <div v-if="loading">
            <p>Loading product details...</p>
        </div>
        <div v-else-if="error">
            <p class="text-danger">{{ error }}</p>
        </div>
        <div v-else-if="product">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img
                                :src="getProductImage(product.image)"
                                alt="Product Image"
                                class="img-fluid"
                            />
                        </div>
                        <div class="col-md-8">
                            <h3>{{ product.product_name }}</h3>
                            <p>
                                <strong>Barcode:</strong>
                                {{ product.product_code }}
                            </p>
                            <p>
                                <strong>Buying Price:</strong> ${{
                                    product.buying_price
                                }}
                            </p>
                            <p>
                                <strong>Selling Price:</strong> ${{
                                    product.selling_price
                                }}
                            </p>
                            <p>
                                <strong>Quantity:</strong>
                                {{ product.quantity }}
                            </p>
                            <p>
                                <strong>Buying Date:</strong>
                                {{ product.buying_date }}
                            </p>
                            <p>
                                <strong>Category:</strong>
                                {{
                                    product.category
                                        ? product.category.category_name
                                        : "N/A"
                                }}
                            </p>
                            <p>
                                <strong>Supplier:</strong>
                                {{
                                    product.supplier
                                        ? product.supplier.name
                                        : "N/A"
                                }}
                            </p>
                            <p><strong>Status:</strong> {{ product.status }}</p>
                            <p>
                                <strong>Notification Status:</strong>
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
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <p>No product details found.</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "ProductDetails",
    data() {
        return {
            product: null,
            loading: true,
            error: null,
        };
    },
    methods: {
        async fetchProductDetails() {
            const productId = this.$route.params.id;
            try {
                const response = await axios.get(`/api/products/${productId}`);
                this.product = response.data;
            } catch (error) {
                console.error("Error fetching product details:", error);
                this.error = "Failed to load product details.";
            } finally {
                this.loading = false;
            }
        },
        getProductImage(image) {
            return image
                ? `/storage/${image}`
                : "https://via.placeholder.com/150";
        },
    },
    mounted() {
        this.fetchProductDetails();
    },
    watch: {
        // Watch for changes in the route parameters
        "$route.params.id": {
            immediate: true, // Trigger the watcher immediately on component creation
            handler(newId, oldId) {
                if (newId !== oldId) {
                    this.fetchProductDetails(); // Re-fetch product details when the ID changes
                }
            },
        },
    },
};
</script>

<style scoped>
.card {
    margin-top: 20px;
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

.text-danger {
    color: red;
}
</style>
