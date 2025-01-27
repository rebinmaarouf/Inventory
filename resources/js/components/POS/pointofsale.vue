<template>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Side: Product Search and Selection -->
            <div class="col-md-8">
                <div class="card shadow-lg mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Products</h5>
                    </div>
                    <div class="card-body">
                        <!-- Product Search Bar -->
                        <div class="form-group mb-3">
                            <input
                                v-model="searchTerm"
                                type="text"
                                class="form-control"
                                placeholder="Search products..."
                            />
                        </div>

                        <!-- Product List -->
                        <div class="row">
                            <div
                                v-for="product in filteredProducts"
                                :key="product.id"
                                class="col-md-4 mb-3"
                            >
                                <div class="card h-100">
                                    <img
                                        :src="getProductImage(product.image)"
                                        class="card-img-top"
                                        alt="Product Image"
                                    />
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            {{ product.product_name }}
                                        </h6>
                                        <p class="card-text">
                                            Price: {{ product.selling_price }} |
                                            Stock: {{ product.quantity }}
                                        </p>
                                        <button
                                            class="btn btn-sm btn-primary"
                                            @click="addToCart(product)"
                                        >
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Cart and Customer Details -->
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0">Cart</h5>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in cart" :key="index">
                                    <td>{{ item.product_name }}</td>
                                    <td>
                                        <input
                                            v-model="item.quantity"
                                            type="number"
                                            min="1"
                                            class="form-control"
                                            @change="updateCartTotal"
                                        />
                                    </td>
                                    <td>{{ item.selling_price }}</td>
                                    <td>
                                        {{ item.selling_price * item.quantity }}
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-danger"
                                            @click="removeFromCart(index)"
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Cart Summary -->
                        <div class="mt-3">
                            <p>
                                <strong>Sub Total:</strong>
                                {{ subTotal.toFixed(2) }}
                            </p>
                            <p>
                                <strong>VAT (5%):</strong> {{ vat.toFixed(2) }}
                            </p>
                            <p>
                                <strong>Total:</strong> {{ total.toFixed(2) }}
                            </p>
                        </div>

                        <!-- Customer Selection -->
                        <div class="form-group mb-3">
                            <label for="customer">Customer</label>
                            <select
                                v-model="selectedCustomer"
                                class="form-control"
                                id="customer"
                            >
                                <option value="" disabled>
                                    Select Customer
                                </option>
                                <option
                                    v-for="customer in customers"
                                    :key="customer.id"
                                    :value="customer.id"
                                >
                                    {{ customer.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <div class="form-group mb-3">
                            <label for="paymentMethod">Pay By</label>
                            <select
                                v-model="paymentMethod"
                                class="form-control"
                                id="paymentMethod"
                            >
                                <option value="cash">Hand Cash</option>
                                <option value="card">Credit Card</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <button
                            class="btn btn-primary btn-block"
                            @click="submitOrder"
                        >
                            Submit Order
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Report Section -->
        <div class="row mt-4 order-report">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div
                        class="card-header bg-info text-white d-flex justify-content-between align-items-center"
                    >
                        <h5 class="card-title mb-0">Order Report</h5>
                        <button
                            class="btn btn-light btn-sm"
                            @click="printReport"
                        >
                            <i class="fas fa-print"></i> Print Report
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Order Search Bar -->
                        <div class="form-group mb-3">
                            <input
                                v-model="orderSearchTerm"
                                type="text"
                                class="form-control"
                                placeholder="Search orders by ID, customer, or payment method..."
                            />
                        </div>

                        <!-- Order Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Payment Method</th>
                                    <th>Sub Total</th>
                                    <th>VAT</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="order in filteredOrders"
                                    :key="order.id"
                                >
                                    <td>{{ order.id }}</td>
                                    <td>
                                        {{
                                            order.customer
                                                ? order.customer.name
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>{{ order.payment_method }}</td>
                                    <td>
                                        {{
                                            order.sub_total
                                                ? order.sub_total.toFixed(2)
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            order.vat
                                                ? order.vat.toFixed(2)
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            order.total
                                                ? order.total.toFixed(2)
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>{{ order.status }}</td>
                                    <td>
                                        {{
                                            order.created_at
                                                ? new Date(
                                                      order.created_at
                                                  ).toLocaleString()
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-warning"
                                            @click="handleReturn(order)"
                                            :disabled="
                                                order.status === 'returned'
                                            "
                                        >
                                            Return
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
            searchTerm: "",
            products: [],
            cart: [],
            customers: [],
            selectedCustomer: "",
            paymentMethod: "cash",
            subTotal: 0,
            vat: 0,
            total: 0,
            orders: [],
            isFetchingOrders: false,
            orderSearchTerm: "",
        };
    },
    computed: {
        filteredProducts() {
            return this.products.filter((product) =>
                product.product_name
                    .toLowerCase()
                    .includes(this.searchTerm.toLowerCase())
            );
        },
        filteredOrders() {
            if (!Array.isArray(this.orders)) {
                return [];
            }
            return this.orders
                .map((order) => {
                    // Ensure numeric values are properly formatted
                    order.sub_total = parseFloat(order.sub_total) || 0;
                    order.vat = parseFloat(order.vat) || 0;
                    order.total = parseFloat(order.total) || 0;
                    return order;
                })
                .filter((order) => {
                    const searchTerm = this.orderSearchTerm.toLowerCase();
                    return (
                        order.id.toString().includes(searchTerm) ||
                        (order.customer &&
                            order.customer.name
                                .toLowerCase()
                                .includes(searchTerm)) ||
                        order.payment_method.toLowerCase().includes(searchTerm)
                    );
                });
        },
    },
    created() {
        this.fetchProducts();
        this.fetchCustomers();
        this.fetchOrders();
    },
    methods: {
        // Add this method to handle product images
        getProductImage(image) {
            if (image && image.startsWith("http")) {
                return image; // Return the full URL if it's already valid
            } else if (image) {
                return `/storage/${image}`; // Assuming images are stored in the `storage` directory
            } else {
                return "https://via.placeholder.com/150"; // Fallback image if none is provided
            }
        },
        // Add this method to handle adding products to the cart
        addToCart(product) {
            // Check if the product is already in the cart
            const existingItem = this.cart.find(
                (item) => item.product_id === product.id
            );

            if (existingItem) {
                // If the product is already in the cart, check if the new quantity exceeds the available stock
                if (existingItem.quantity + 1 > product.quantity) {
                    Swal.fire(
                        "Warning",
                        `Not enough stock for product: ${product.product_name}. Available: ${product.quantity}`,
                        "warning"
                    );
                    return;
                }
                // If the product is already in the cart, increase the quantity
                existingItem.quantity += 1;
            } else {
                // If the product is not in the cart, check if the quantity exceeds the available stock
                if (1 > product.quantity) {
                    Swal.fire(
                        "Warning",
                        `Not enough stock for product: ${product.product_name}. Available: ${product.quantity}`,
                        "warning"
                    );
                    return;
                }
                // If the product is not in the cart, add it
                this.cart.push({
                    product_id: product.id,
                    product_name: product.product_name,
                    selling_price: product.selling_price,
                    quantity: 1,
                });
            }

            // Update the cart totals
            this.updateCartTotal();
        },
        // Add this method to update cart totals
        updateCartTotal() {
            // Validate cart items before updating totals
            for (const item of this.cart) {
                const product = this.products.find(
                    (product) => product.id === item.product_id
                );
                if (product && item.quantity > product.quantity) {
                    Swal.fire(
                        "Warning",
                        `Not enough stock for product: ${product.product_name}. Available: ${product.quantity}`,
                        "warning"
                    );
                    item.quantity = product.quantity; // Reset the quantity to the available stock
                }
            }

            // Update the cart totals
            this.subTotal = this.cart.reduce(
                (total, item) => total + item.selling_price * item.quantity,
                0
            );
            this.vat = this.subTotal * 0.05; // Assuming 5% VAT
            this.total = this.subTotal + this.vat;
        },
        // Add this method to remove items from the cart
        removeFromCart(index) {
            this.cart.splice(index, 1); // Remove the item at the specified index
            this.updateCartTotal(); // Update the cart totals
        },
        // Add this method to validate cart items against available stock
        validateCartItems() {
            for (const item of this.cart) {
                const product = this.products.find(
                    (product) => product.id === item.product_id
                );
                if (product && item.quantity > product.quantity) {
                    return {
                        valid: false,
                        message: `Not enough stock for product: ${product.product_name}. Available: ${product.quantity}`,
                    };
                }
            }
            return { valid: true };
        },
        // Add this method to submit the order
        async submitOrder() {
            if (this.cart.length === 0) {
                Swal.fire("Error", "Your cart is empty.", "error");
                return;
            }

            if (!this.selectedCustomer) {
                Swal.fire("Error", "Please select a customer.", "error");
                return;
            }

            // Validate cart items before submitting the order
            const validation = this.validateCartItems();
            if (!validation.valid) {
                Swal.fire("Warning", validation.message, "warning");
                return;
            }

            const orderData = {
                customer_id: this.selectedCustomer,
                payment_method: this.paymentMethod,
                items: this.cart.map((item) => ({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    price: item.selling_price,
                    total: item.selling_price * item.quantity,
                })),
                sub_total: this.subTotal,
                vat: this.vat,
                total: this.total,
            };

            try {
                const response = await axios.post("/api/orders", orderData);
                if (response.status === 201) {
                    Swal.fire(
                        "Success",
                        "Order created successfully!",
                        "success"
                    );
                    this.cart = []; // Clear the cart
                    this.selectedCustomer = ""; // Reset customer selection
                    this.paymentMethod = "cash"; // Reset payment method
                    this.subTotal = 0;
                    this.vat = 0;
                    this.total = 0;
                    await this.fetchOrders(); // Refresh the orders list
                }
            } catch (error) {
                console.error("Error submitting order:", error);
                Swal.fire("Error", "Failed to submit order.", "error");
            }
        },
        async fetchProducts() {
            try {
                const response = await axios.get("/api/products");
                this.products = response.data;
            } catch (error) {
                console.error("Error fetching products:", error);
                Swal.fire("Error", "Failed to fetch products.", "error");
            }
        },
        async fetchCustomers() {
            try {
                const response = await axios.get("/api/customers");
                this.customers = response.data;
            } catch (error) {
                console.error("Error fetching customers:", error);
                Swal.fire("Error", "Failed to fetch customers.", "error");
            }
        },
        async fetchOrders() {
            if (this.isFetchingOrders) return;
            this.isFetchingOrders = true;

            try {
                const response = await axios.get("/api/orders");
                this.orders = Array.isArray(response.data) ? response.data : [];
            } catch (error) {
                console.error("Error fetching orders:", error);
                Swal.fire("Error", "Failed to fetch orders.", "error");
            } finally {
                this.isFetchingOrders = false;
            }
        },
        async handleReturn(order) {
            if (!order.id) {
                Swal.fire("Error", "Invalid order ID.", "error");
                return;
            }

            const result = await Swal.fire({
                title: "Return Order",
                text: "Are you sure you want to return this order?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, return it!",
                cancelButtonText: "No, cancel!",
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.post(
                        `/api/orders/${order.id}/return`
                    );
                    if (response.status === 200) {
                        Swal.fire(
                            "Success",
                            "Order returned successfully!",
                            "success"
                        );
                        await this.fetchOrders();
                        await this.fetchProducts();
                    }
                } catch (error) {
                    console.error("Error returning order:", error);
                    Swal.fire("Error", "Failed to return order.", "error");
                }
            }
        },
        printReport() {
            const printContents =
                document.querySelector(".order-report").innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        },
    },
};
</script>

<style scoped>
.card {
    border-radius: 10px;
}

.card-header {
    border-radius: 10px 10px 0 0;
}

.table {
    margin-bottom: 0;
}

.btn-block {
    margin-top: 20px;
}

.card-img-top {
    height: 150px;
    object-fit: cover;
}

@media (max-width: 768px) {
    .row {
        flex-direction: column;
    }

    .col-md-8,
    .col-md-4 {
        width: 100%;
    }
}

@media print {
    /* Hide unnecessary elements when printing */
    .navbar,
    .sidebar,
    .card-header button,
    .card-header h5,
    .col-md-8,
    .col-md-4 {
        display: none;
    }

    /* Ensure the order report takes full width */
    .col-md-12 {
        width: 100%;
    }

    /* Add margins and padding for better print layout */
    .card {
        border: none;
        box-shadow: none;
    }

    .card-body {
        padding: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
}
</style>
