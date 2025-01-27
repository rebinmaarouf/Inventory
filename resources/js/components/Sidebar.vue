<template>
    <ul
        class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion"
        id="accordionSidebar"
    >
        <!-- Sidebar - Brand -->
        <a
            class="sidebar-brand d-flex align-items-center justify-content-center"
            href="index.html"
        >
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0" />

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <router-link class="nav-link" to="/home">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </router-link>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Nav Item - POS -->
        <li class="nav-item active">
            <router-link class="nav-link" to="/pos">
                <i class="fas fa-fw fa-tachometer-alt text-white"></i>
                <span class="text-white"><b>POS</b></span>
            </router-link>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading: Management -->
        <div class="sidebar-heading">Management</div>

        <!-- Nav Item - Employee Management -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseEmployee"
                aria-expanded="false"
                aria-controls="collapseEmployee"
            >
                <i class="fas fa-fw fa-users"></i>
                <span>Employee</span>
            </a>
            <div
                id="collapseEmployee"
                class="collapse"
                aria-labelledby="headingEmployee"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Employee Operations:</h6>
                    <router-link class="collapse-item" to="/add-employee"
                        >Add Employee</router-link
                    >
                    <router-link class="collapse-item" to="/all-employees"
                        >All Employees</router-link
                    >
                </div>
            </div>
        </li>

        <!-- Nav Item - Supplier Management -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseSupplier"
                aria-expanded="false"
                aria-controls="collapseSupplier"
            >
                <i class="fas fa-fw fa-truck"></i>
                <span>Suppliers</span>
            </a>
            <div
                id="collapseSupplier"
                class="collapse"
                aria-labelledby="headingSupplier"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Supplier Operations:</h6>
                    <router-link class="collapse-item" to="/add-supplier"
                        >Add Supplier</router-link
                    >
                    <router-link class="collapse-item" to="/all-suppliers"
                        >All Suppliers</router-link
                    >
                </div>
            </div>
        </li>

        <!-- Nav Item - Product Management -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseProduct"
                aria-expanded="false"
                aria-controls="collapseProduct"
            >
                <i class="fas fa-fw fa-box"></i>
                <span>Products</span>
            </a>
            <div
                id="collapseProduct"
                class="collapse"
                aria-labelledby="headingProduct"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Product Operations:</h6>
                    <router-link class="collapse-item" to="/add-product"
                        >Add Product</router-link
                    >
                    <router-link class="collapse-item" to="/all-products"
                        >All Products</router-link
                    >
                </div>
            </div>
        </li>

        <!-- Nav Item - Category Management -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseCategory"
                aria-expanded="false"
                aria-controls="collapseCategory"
            >
                <i class="fas fa-fw fa-list"></i>
                <span>Categories</span>
            </a>
            <div
                id="collapseCategory"
                class="collapse"
                aria-labelledby="headingCategory"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Category Operations:</h6>
                    <router-link class="collapse-item" to="/add-category"
                        >Add Category</router-link
                    >
                    <router-link class="collapse-item" to="/all-categories"
                        >All Categories</router-link
                    >
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading: Finance -->
        <div class="sidebar-heading">Finance</div>

        <!-- Nav Item - Expense Management -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseExpense"
                aria-expanded="false"
                aria-controls="collapseExpense"
            >
                <i class="fas fa-fw fa-money-bill"></i>
                <span>Expenses</span>
            </a>
            <div
                id="collapseExpense"
                class="collapse"
                aria-labelledby="headingExpense"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Expense Operations:</h6>
                    <router-link class="collapse-item" to="/add-expenses"
                        >Add Expense</router-link
                    >
                    <router-link class="collapse-item" to="/all-expenses"
                        >All Expenses</router-link
                    >
                </div>
            </div>
        </li>

        <!-- Nav Item - Salary Management -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseSalary"
                aria-expanded="false"
                aria-controls="collapseSalary"
            >
                <i class="fas fa-fw fa-money-bill"></i>
                <span>Salaries</span>
            </a>
            <div
                id="collapseSalary"
                class="collapse"
                aria-labelledby="headingSalary"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Salary Operations:</h6>
                    <router-link class="collapse-item" to="/add-salary"
                        >Add Salary</router-link
                    >
                    <router-link class="collapse-item" to="/all-salaries"
                        >All Salaries</router-link
                    >
                </div>
            </div>
        </li>
        <!-- Nav Item - Customers Management -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseCustomers"
                aria-expanded="false"
                aria-controls="collapseCustomers"
            >
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>
            <div
                id="collapseCustomers"
                class="collapse"
                aria-labelledby="headingCustomers"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Customer Operations:</h6>
                    <router-link class="collapse-item" to="/add-customer"
                        >Add Customer</router-link
                    >
                    <router-link class="collapse-item" to="/all-customers"
                        >All Customers</router-link
                    >
                </div>
            </div>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading: Reports -->
        <div class="sidebar-heading">Reports</div>

        <!-- Nav Item - Reports -->
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#collapseReports"
                aria-expanded="false"
                aria-controls="collapseReports"
            >
                <i class="fas fa-fw fa-folder"></i>
                <span>Reports</span>
            </a>
            <div
                id="collapseReports"
                class="collapse"
                aria-labelledby="headingReports"
                data-bs-parent="#accordionSidebar"
            >
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Report Types:</h6>
                    <router-link class="collapse-item" to="/sales-report"
                        >Sales Report</router-link
                    >
                    <router-link class="collapse-item" to="/inventory-report"
                        >Inventory Report</router-link
                    >
                    <router-link class="collapse-item" to="/financial-report"
                        >Financial Report</router-link
                    >
                </div>
            </div>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block" />

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
</template>

<script>
import { onMounted } from "vue";
import { Collapse } from "bootstrap";

export default {
    name: "Sidebar",
    setup() {
        onMounted(() => {
            // Manually initialize Bootstrap collapse
            document.querySelectorAll(".collapse").forEach((el) => {
                new Collapse(el, {
                    toggle: false, // Prevent automatic toggling
                });
            });

            // Sidebar toggle button functionality
            const sidebarToggleButton =
                document.getElementById("sidebarToggle");
            if (sidebarToggleButton) {
                sidebarToggleButton.addEventListener("click", function () {
                    document.body.classList.toggle("sidebar-toggled");
                    document
                        .querySelector(".sidebar")
                        .classList.toggle("toggled");
                });
            }
        });
    },
};
</script>

<style scoped>
/* Prevent the menu from disappearing abruptly */
.sidebar .collapse {
    transition: height 0.4s ease-in-out;
}

.collapse-item {
    padding: 0.5rem 1rem;
    display: block;
    color: #333;
}

.collapse-item:hover {
    color: #007bff;
    background-color: rgba(0, 123, 255, 0.1);
}

.collapse-inner {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>
