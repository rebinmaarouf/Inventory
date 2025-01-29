<template>
    <div class="container-fluid">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">New Retail Invoice</h5>
            </div>
            <div class="card-body">
                <!-- Invoice Details -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="invoiceNumber">Invoice Number</label>
                        <input
                            v-model="invoice.invoiceNumber"
                            type="text"
                            class="form-control"
                            id="invoiceNumber"
                            placeholder="Invoice Number"
                        />
                    </div>
                    <div class="col-md-6">
                        <label for="invoiceDate">Invoice Date</label>
                        <input
                            v-model="invoice.invoiceDate"
                            type="date"
                            class="form-control"
                            id="invoiceDate"
                        />
                    </div>
                </div>

                <!-- Item Selection -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="itemSelect">Select Item</label>
                        <select
                            v-model="selectedItem"
                            class="form-control"
                            id="itemSelect"
                            @change="addItemToInvoice"
                        >
                            <option value="" disabled>Select Item</option>
                            <option
                                v-for="item in items"
                                :key="item.id"
                                :value="item"
                            >
                                {{ item.name }} - ${{ item.price }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- Item List -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in invoice.items"
                                    :key="index"
                                >
                                    <td>{{ item.name }}</td>
                                    <td>
                                        <input
                                            v-model="item.quantity"
                                            type="number"
                                            min="1"
                                            class="form-control"
                                            @change="updateTotals"
                                        />
                                    </td>
                                    <td>${{ item.price }}</td>
                                    <td>${{ item.price * item.quantity }}</td>
                                    <td>
                                        <button
                                            class="btn btn-sm btn-danger"
                                            @click="removeItem(index)"
                                        >
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="paymentMethod">Payment Method</label>
                        <select
                            v-model="invoice.paymentMethod"
                            class="form-control"
                            id="paymentMethod"
                        >
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                </div>

                <!-- Totals -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <p>
                            <strong>Sub Total:</strong> ${{
                                subTotal.toFixed(2)
                            }}
                        </p>
                        <p><strong>VAT (5%):</strong> ${{ vat.toFixed(2) }}</p>
                        <p><strong>Total:</strong> ${{ total.toFixed(2) }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-success" @click="saveInvoice">
                            Save
                        </button>
                        <button
                            class="btn btn-secondary"
                            @click="cancelInvoice"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            invoice: {
                invoiceNumber: "",
                invoiceDate: new Date().toISOString().split("T")[0],
                items: [],
                paymentMethod: "cash",
            },
            selectedItem: null,
            items: [
                { id: 1, name: "Item 1", price: 100 },
                { id: 2, name: "Item 2", price: 200 },
                { id: 3, name: "Item 3", price: 300 },
            ],
            subTotal: 0,
            vat: 0,
            total: 0,
        };
    },
    methods: {
        addItemToInvoice() {
            if (this.selectedItem) {
                const item = { ...this.selectedItem, quantity: 1 };
                this.invoice.items.push(item);
                this.selectedItem = null;
                this.updateTotals();
            }
        },
        removeItem(index) {
            this.invoice.items.splice(index, 1);
            this.updateTotals();
        },
        updateTotals() {
            this.subTotal = this.invoice.items.reduce(
                (sum, item) => sum + item.price * item.quantity,
                0
            );
            this.vat = this.subTotal * 0.05;
            this.total = this.subTotal + this.vat;
        },
        saveInvoice() {
            // Save the invoice logic here
            console.log("Invoice saved:", this.invoice);
            alert("Invoice saved successfully!");
        },
        cancelInvoice() {
            // Cancel the invoice logic here
            this.invoice = {
                invoiceNumber: "",
                invoiceDate: new Date().toISOString().split("T")[0],
                items: [],
                paymentMethod: "cash",
            };
            this.subTotal = 0;
            this.vat = 0;
            this.total = 0;
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

@media (max-width: 768px) {
    .row {
        flex-direction: column;
    }

    .col-md-8,
    .col-md-4 {
        width: 100%;
    }
}
</style>
