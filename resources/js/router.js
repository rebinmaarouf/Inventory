import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/auth/login.vue';
import Register from './components/auth/register.vue';
import Forget from './components/auth/forget.vue';
import Dashboard from './components/Dashboard.vue';
import Reset from './components/auth/reset.vue';
import Home from './components/Home.vue';

// Employee Component 
import Add from './components/employees/add.vue';
import Index from './components/employees/index.vue';

// Supplier Component
import AddSupplier from './components/suppliers/AddSupplier.vue';
import IndexSupplier from './components/suppliers/IndexSupplier.vue';

// Categories Component
import AddCategory from './components/categories/AddCategory.vue';
import IndexCategory from './components/categories/IndexCategory.vue';

// Products Component
import AddProduct from './components/product/AddProduct.vue';
import AllProduct from './components/product/AllProduct.vue';
import ProductDetails from './components/product/ProductDetails.vue';


// Expenses Component
import AddExpense from './components/expenses/AddExpense.vue';
import AllExpenses from './components/expenses/AllExpenses.vue';

// Salary Component
import AddSalary from './components/salary/AddSalary.vue';
import AllSalary from './components/salary/AllSalary.vue';

// Customer Component
import AddCustomer from './components/customers/AddCustomer.vue';
import AllCustomer from './components/customers/AllCustomer.vue';

// POS Component
import Pointofsale from './components/POS/pointofsale.vue';

// Reports

import SalesReport from './components/reports/SalesReport.vue';
import InventoryReport from './components/reports/InventoryReport.vue';
import FinancialReport from './components/reports/FinancialReport.vue';

const routes = [
    {
        path: '/',
        name: 'login',
        component: Login,
        meta: { isPublic: true },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { isPublic: true },
    },
    {
        path: '/forget',
        name: 'forget',
        component: Forget,
        meta: { isPublic: true },
    },
    {
        path: '/home',
        name: 'home',
        component: Home,
        meta: { requiresAuth: true, breadcrumb: 'Dashboard' },
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true, breadcrumb: 'Dashboard' },
    },
    {
        path: '/reset/:token',
        name: 'reset',
        component: Reset,
        props: true,
        meta: { isPublic: true },
    },
    {
        path: '/auth/google',
        name: 'google-auth',
        component: Login,
        meta: { isPublic: true },
    },
    {
        path: '/auth/facebook',
        name: 'facebook-auth',
        component: Login,
        meta: { isPublic: true },
    },
    {
        path: '/auth/callback',
        name: 'oauth-callback',
        component: Login,
        meta: { isPublic: true },
    },
    {
        path: '/add-employee',
        name: 'add-employee',
        component: Add,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Employee' },
    },
    {
        path: '/all-employees',
        name: 'all-employees',
        component: Index,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'All Employees' },
    },
    {
        path: '/add-supplier',
        name: 'add-supplier',
        component: AddSupplier,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Supplier' },
    },
    {
        path: '/all-suppliers',
        name: 'all-suppliers',
        component: IndexSupplier,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'All Suppliers' },
    },
    {
        path: '/add-category',
        name: 'add-category',
        component: AddCategory,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Category' },
    },
    {
        path: '/all-categories',
        name: 'all-categories',
        component: IndexCategory,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'All Categories' },
    },
    {
        path: '/add-product',
        name: 'add-product',
        component: AddProduct,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Product' },
    },
    {
        path: '/all-products',
        name: 'all-products',
        component: AllProduct,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'All Products' },
    },
    {
        path: '/edit-product/:barcode',
        name: 'edit-product',
        component: AddProduct,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Edit Products' },
    },
    {
        path: '/product/:id',
        name: 'ProductDetails',
        component: ProductDetails,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Product Details' },
    },
    {
        path: '/add-expenses',
        name: 'add-expenses',
        component: AddExpense,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Expenses' },
    },
    {
        path: '/all-expenses',
        name: 'all-expenses',
        component: AllExpenses,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'All Expenses' },
    },
    {
        path: '/add-salary',
        name: 'add-salary',
        component: AddSalary,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Salary' },
    },
    {
        path: '/all-salaries',
        name: 'all-salaries',
        component: AllSalary,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'All Salaries' },
    },
    {
        path: '/edit-salary/:id',
        name: 'edit-salary',
        component: AddSalary,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Edit Salary' },
    },
    {
        path: '/add-customer',
        name: 'add-customer',
        component: AddCustomer,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Customer' },
    },
    {
        path: '/all-customers',
        name: 'all-customers',
        component: AllCustomer,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'All Customers' },
    }, {
        path: '/pos',
        name: 'pos',
        component: Pointofsale,
        meta: { requiresAuth: true, requiresSidebar: true, requiresTopbar: true, breadcrumb: 'Add Customer' },
    },
    {
        path: "/sales-report",
        name: "SalesReport",
        component: SalesReport, // Replace with your actual component
    },
    {
        path: "/inventory-report",
        name: "InventoryReport",
        component: InventoryReport, // Replace with your actual component
    },
    {
        path: "/financial-report",
        name: "FinancialReport",
        component: FinancialReport, // Replace with your actual component
    },
    // A
 
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('access_token');
    console.log("Navigation guard triggered");
    console.log("To route:", to.path);
    console.log("Token exists:", !!token);

    // Redirect to login if the route requires authentication and no token exists
    if (to.meta.requiresAuth && !token) {
        console.log("Redirecting to login");
        next({ path: '/', query: { redirect: to.fullPath } });
    }
    // Redirect authenticated users away from public routes (only for login, register, forget)
    else if (to.meta.isPublic && token && ['/', '/register', '/forget'].includes(to.path)) {
        console.log("Redirecting authenticated user away from public route");
        next('/home');
    }
    // Otherwise, proceed to the route
    else {
        console.log("Proceeding to route");
        next();
    }
});

export default router;