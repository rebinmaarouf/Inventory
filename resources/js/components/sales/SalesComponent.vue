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
                        <!-- Product Search Bar and Category Filter -->
                        <div class="form-group mb-3">
                            <input
                                v-model="searchTerm"
                                type="text"
                                class="form-control"
                                placeholder="Search products..."
                            />
                        </div>
                        <div class="category-filter">
                            <select v-model="selectedCategory">
                                <option value="">All Categories</option>
                                <option
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="category.id"
                                >
                                    {{ category.category_name }}
                                </option>
                            </select>
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
                    <div class="card-body cart-section">
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
                                <tr
                                    v-for="(item, index) in cart"
                                    :key="item.product_id"
                                >
                                    <td>{{ item.product_name }}</td>
                                    <td>
                                        <input
                                            type="number"
                                            v-model.number="item.quantity"
                                            min="1"
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

                        <!-- Submit and Print Invoice Buttons -->
                        <button
                            class="btn btn-primary btn-block"
                            @click="submitSale"
                        >
                            Submit Sale
                        </button>
                        <button
                            class="btn btn-secondary btn-block mt-2"
                            @click="printInvoice"
                            :disabled="cart.length === 0"
                        >
                            Print Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hidden Invoice Content for Printing -->
        <div id="invoiceContent" style="display: none">
            <h2>Invoice</h2>
            <p><strong>Customer:</strong> {{ selectedCustomerName }}</p>
            <p><strong>Payment Method:</strong> {{ paymentMethod }}</p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in cart" :key="index">
                        <td>{{ item.product_name }}</td>
                        <td>{{ item.quantity }}</td>
                        <td>{{ item.selling_price }}</td>
                        <td>{{ item.selling_price * item.quantity }}</td>
                    </tr>
                </tbody>
            </table>
            <p><strong>Sub Total:</strong> {{ subTotal.toFixed(2) }}</p>
            <p><strong>VAT (5%):</strong> {{ vat.toFixed(2) }}</p>
            <p><strong>Total:</strong> {{ total.toFixed(2) }}</p>
        </div>

        <!-- Sale Report Section -->
        <div class="row mt-4 sale-report">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div
                        class="card-header bg-info text-white d-flex justify-content-between align-items-center"
                    >
                        <h5 class="card-title mb-0">Sale Report</h5>
                        <button
                            class="btn btn-light btn-sm"
                            @click="printReport"
                        >
                            <i class="fas fa-print"></i> Print Report
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Sale Search Bar -->
                        <div class="form-group mb-3">
                            <input
                                v-model="saleSearchTerm"
                                type="text"
                                class="form-control"
                                placeholder="Search sales by ID, customer, or payment method..."
                            />
                        </div>

                        <!-- Sale Table -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sale ID</th>
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
                                    v-for="sale in filteredSales"
                                    :key="sale.id"
                                >
                                    <td>{{ sale.id }}</td>
                                    <td>
                                        {{
                                            sale.customer
                                                ? sale.customer.name
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>{{ sale.payment_method }}</td>
                                    <td>
                                        {{
                                            sale.sub_total
                                                ? sale.sub_total.toFixed(2)
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            sale.vat
                                                ? sale.vat.toFixed(2)
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>
                                        {{
                                            sale.total
                                                ? sale.total.toFixed(2)
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>{{ sale.status }}</td>
                                    <td>
                                        {{
                                            sale.created_at
                                                ? new Date(
                                                      sale.created_at
                                                  ).toLocaleString()
                                                : "N/A"
                                        }}
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-warning"
                                            @click="handleReturn(sale)"
                                            :disabled="
                                                sale.status === 'returned'
                                            "
                                        >
                                            Return
                                        </button>
                                        <button
                                            class="btn btn-sm btn-danger"
                                            @click="handleDelete(sale)"
                                        >
                                            Delete
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
            sales: [],
            isFetchingSales: false,
            saleSearchTerm: "",
            categories: [], // Ensure this is populated with your category data
            selectedCategory: "",
        };
    },
    computed: {
        filteredProducts() {
            let filtered = this.products.filter((product) =>
                product.product_name
                    .toLowerCase()
                    .includes(this.searchTerm.toLowerCase())
            );

            if (this.selectedCategory) {
                filtered = filtered.filter(
                    (product) => product.category_id === this.selectedCategory
                );
            }

            return filtered;
        },
        filteredSales() {
            if (!Array.isArray(this.sales)) {
                return [];
            }
            return this.sales
                .map((sale) => {
                    // Ensure numeric values are properly formatted
                    sale.sub_total = parseFloat(sale.sub_total) || 0;
                    sale.vat = parseFloat(sale.vat) || 0;
                    sale.total = parseFloat(sale.total) || 0;
                    return sale;
                })
                .filter((sale) => {
                    const searchTerm = this.saleSearchTerm.toLowerCase();
                    return (
                        sale.id.toString().includes(searchTerm) ||
                        (sale.customer &&
                            sale.customer.name
                                .toLowerCase()
                                .includes(searchTerm)) ||
                        sale.payment_method.toLowerCase().includes(searchTerm)
                    );
                });
        },
        // Add this computed property to get the selected customer's name
        selectedCustomerName() {
            if (!this.selectedCustomer) return "";
            const customer = this.customers.find(
                (customer) => customer.id === this.selectedCustomer
            );
            return customer ? customer.name : "";
        },
    },
    created() {
        this.fetchProducts();
        this.fetchCustomers();
        this.fetchSales();
        this.fetchCategories(); // Fetch categories when the component is created
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
            const existingItem = this.cart.find(
                (item) => item.product_id === product.id
            );

            if (existingItem) {
                if (existingItem.quantity + 1 > product.quantity) {
                    Swal.fire(
                        "Warning",
                        `Not enough stock for product: ${product.product_name}. Available: ${product.quantity}`,
                        "warning"
                    );
                    return;
                }
                existingItem.quantity += 1;
            } else {
                if (1 > product.quantity) {
                    Swal.fire(
                        "Warning",
                        `Not enough stock for product: ${product.product_name}. Available: ${product.quantity}`,
                        "warning"
                    );
                    return;
                }
                this.cart.push({
                    product_id: product.id,
                    product_name: product.product_name,
                    selling_price: product.selling_price,
                    quantity: 1,
                    category_id: product.category_id, // Include category_id in the cart
                });
            }

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
        // Add this method to submit the sale
        async submitSale() {
            if (this.cart.length === 0) {
                Swal.fire("Error", "Your cart is empty.", "error");
                return;
            }

            if (!this.selectedCustomer) {
                Swal.fire("Error", "Please select a customer.", "error");
                return;
            }

            // Validate cart items before submitting the sale
            const validation = this.validateCartItems();
            if (!validation.valid) {
                Swal.fire("Warning", validation.message, "warning");
                return;
            }

            const saleData = {
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
                const response = await axios.post("/api/sales", saleData);
                if (response.status === 201) {
                    Swal.fire(
                        "Success",
                        "Sale created successfully!",
                        "success"
                    );
                    this.cart = []; // Clear the cart
                    this.selectedCustomer = ""; // Reset customer selection
                    this.paymentMethod = "cash"; // Reset payment method
                    this.subTotal = 0;
                    this.vat = 0;
                    this.total = 0;
                    await this.fetchSales(); // Refresh the sales list
                }
            } catch (error) {
                console.error("Error submitting sale:", error);
                Swal.fire("Error", "Failed to submit sale.", "error");
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
        async fetchSales() {
            if (this.isFetchingSales) return;
            this.isFetchingSales = true;

            try {
                const response = await axios.get("/api/sales");
                this.sales = Array.isArray(response.data) ? response.data : [];
            } catch (error) {
                console.error("Error fetching sales:", error);
                Swal.fire(
                    "Error",
                    "Failed to fetch sales. Please try again later.",
                    "error"
                );
            } finally {
                this.isFetchingSales = false;
            }
        },
        async handleReturn(sale) {
            if (!sale.id) {
                Swal.fire("Error", "Invalid sale ID.", "error");
                return;
            }

            const result = await Swal.fire({
                title: "Return Sale",
                text: "Are you sure you want to return this sale?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, return it!",
                cancelButtonText: "No, cancel!",
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.post(
                        `/api/sales/${sale.id}/return`
                    );
                    if (response.status === 200) {
                        Swal.fire(
                            "Success",
                            "Sale returned successfully!",
                            "success"
                        );
                        await this.fetchSales();
                        await this.fetchProducts();
                    }
                } catch (error) {
                    console.error("Error returning sale:", error);
                    Swal.fire("Error", "Failed to return sale.", "error");
                }
            }
        },
        // Add this method to handle sale deletion
        async handleDelete(sale) {
            if (!sale.id) {
                Swal.fire("Error", "Invalid sale ID.", "error");
                return;
            }

            const result = await Swal.fire({
                title: "Delete Sale",
                text: "Are you sure you want to delete this sale? This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            });

            if (result.isConfirmed) {
                try {
                    const response = await axios.delete(
                        `/api/sales/${sale.id}`
                    );
                    if (response.status === 200) {
                        Swal.fire(
                            "Success",
                            "Sale deleted successfully!",
                            "success"
                        );
                        await this.fetchSales(); // Refresh the sales list
                    }
                } catch (error) {
                    console.error("Error deleting sale:", error);
                    Swal.fire("Error", "Failed to delete sale.", "error");
                }
            }
        },
        async fetchCategories() {
            try {
                const response = await axios.get("/api/categories");
                this.categories = response.data;
            } catch (error) {
                console.error("Error fetching categories:", error);
                Swal.fire("Error", "Failed to fetch categories.", "error");
            }
        },
        printReport() {
            const printContents =
                document.querySelector(".sale-report").innerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        },
        // Add this method to print the invoice
        printInvoice() {
            if (this.cart.length === 0) {
                Swal.fire("Error", "Your cart is empty.", "error");
                return;
            }

            const invoiceContent = `
        <div class="invoice">
            <h2>Invoice</h2>
            <p><strong>Customer:</strong> ${this.selectedCustomerName}</p>
            <p><strong>Payment Method:</strong> ${this.paymentMethod}</p>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${this.cart
                        .map(
                            (item) => `
                        <tr>
                            <td>${item.product_name}</td>
                            <td>${
                                this.categories.find(
                                    (cat) => cat.id === item.category_id
                                )?.category_name || "N/A"
                            }</td>
                            <td>${item.quantity}</td>
                            <td>${item.selling_price}</td>
                            <td>${item.selling_price * item.quantity}</td>
                        </tr>
                    `
                        )
                        .join("")}
                </tbody>
            </table>
            <p><strong>Subtotal:</strong> ${this.subTotal}</p>
            <p><strong>VAT (5%):</strong> ${this.vat}</p>
            <p><strong>Total:</strong> ${this.total}</p>
        </div>
    `;

            const printWindow = window.open("", "_blank");
            printWindow.document.write(`
        <html>
            <head>
                <title>Invoice</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .invoice { width: 100%; max-width: 800px; margin: 0 auto; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
                    h2 { text-align: center; }
                </style>
            </head>
            <body>
                ${invoiceContent}
            </body>
        </html>
    `);
            printWindow.document.close();
            printWindow.print();
        },
    },
};
</script>

<style scoped>
/* General Styles */
.container-fluid {
    padding: 20px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-header {
    border-radius: 10px 10px 0 0;
    background-color: #4e73df;
    color: white;
    padding: 15px;
}

.card-title {
    margin: 0;
    font-size: 1.25rem;
}

.card-body {
    padding: 20px;
}

/* Category Filter Dropdown */
.category-filter {
    margin-bottom: 20px;
}

.category-filter select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    background-color: white;
    cursor: pointer;
}

.category-filter select:focus {
    border-color: #4e73df;
    outline: none;
}

/* Product List */
.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.2s ease-in-out;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-card img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.product-card .card-body {
    padding: 15px;
}

.product-card .card-title {
    font-size: 1rem;
    margin-bottom: 10px;
}

.product-card .card-text {
    font-size: 0.9rem;
    color: #666;
}

.product-card .btn {
    width: 100%;
}

/* Cart Section */
.cart-section {
    max-height: 70vh;
    overflow-y: auto;
    padding: 15px;
}

.cart-section table {
    width: 100%;
    margin-bottom: 15px;
}

.cart-section table th,
.cart-section table td {
    padding: 10px;
    text-align: center;
}

.cart-section table th {
    background-color: #f8f9fa;
}

.cart-section table td input {
    width: 60px;
    text-align: center;
}

.cart-summary {
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin-top: 15px;
}

.cart-summary p {
    margin: 0;
    font-size: 0.9rem;
}

.cart-summary strong {
    font-weight: 600;
}

/* Buttons */
.btn-block {
    margin-top: 15px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .row {
        flex-direction: column;
    }

    .col-md-8,
    .col-md-4 {
        width: 100%;
    }

    .cart-section {
        max-height: 50vh;
    }
}

@media print {
    .navbar,
    .sidebar,
    .card-header button,
    .card-header h5,
    .col-md-8,
    .col-md-4 {
        display: none;
    }

    .col-md-12 {
        width: 100%;
    }

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
