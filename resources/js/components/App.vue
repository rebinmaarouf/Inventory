<template>
    <div id="wrapper" :class="{ toggled: sidebarToggled }">
        <!-- Sidebar -->
        <Sidebar v-if="showLayout && currentPageClass === 'home-page'" />

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <Topbar v-if="showLayout && currentPageClass === 'home-page'" />

                <!-- Page Content -->
                <div class="container-fluid">
                    <!-- Updated Breadcrumb Navigation -->
                    <div
                        class="breadcrumb-navigation"
                        v-if="showLayout && currentPageClass === 'home-page'"
                    >
                        <span class="breadcrumb-item">
                            <router-link to="/">Dashboard</router-link>
                        </span>
                        <span class="breadcrumb-divider">/</span>
                        <span class="breadcrumb-item active">
                            {{ currentBreadcrumb }}
                        </span>
                    </div>

                    <!-- Dynamic Page Content -->
                    <router-view></router-view>
                </div>
            </div>
        </div>

        <!-- Scroll to Top Button -->
        <a
            class="scroll-to-top rounded"
            @click="scrollToTop"
            v-if="showScrollToTop"
        >
            <i class="fas fa-angle-up"></i>
        </a>
    </div>
</template>

<script>
import Sidebar from "./Sidebar.vue";
import Topbar from "./Topbar.vue";

export default {
    components: {
        Sidebar,
        Topbar,
    },
    computed: {
        showLayout() {
            return this.$route.meta && !this.$route.meta.isPublic;
        },
        currentPageClass() {
            const homePages = [
                "home",
                "dashboard",
                "add-employee",
                "all-employees",
                "add-supplier",
                "all-suppliers",
                "add-category",
                "all-categories",
                "add-product",
                "all-products",
                "ProductDetails", // Ensure this is included
                "add-expenses",
                "all-expenses",
                "add-salary",
                "all-salaries",
                "edit-salary",
                "add-customer",
                "all-customers",
                "pos",
            ];
            return homePages.includes(this.$route.name) ? "home-page" : "";
        },
        currentBreadcrumb() {
            return this.$route.meta?.breadcrumb || "Dashboard";
        },
    },
    data() {
        return {
            sidebarToggled: false,
            showScrollToTop: false,
        };
    },
    mounted() {
        window.addEventListener("scroll", this.toggleScrollToTopButton);
    },
    beforeDestroy() {
        window.removeEventListener("scroll", this.toggleScrollToTopButton);
    },
    methods: {
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        },
        toggleScrollToTopButton() {
            this.showScrollToTop = window.scrollY > 100;
        },
    },
};
</script>

<style scoped>
/* Breadcrumb Navigation Styles */
.breadcrumb-navigation {
    font-size: 14px;
    color: #6c757d; /* Gray color for breadcrumb text */
    margin-bottom: 1rem; /* Space below the breadcrumb */
}

.breadcrumb-item {
    color: #007bff; /* Blue color for links */
    text-decoration: none;
}

.breadcrumb-item.active {
    color: #6c757d; /* Gray color for active item */
    font-weight: bold; /* Bold text for active item */
}

.breadcrumb-divider {
    margin: 0 8px; /* Space around the divider */
    color: #6c757d; /* Gray color for the divider */
}

.breadcrumb-item a {
    color: #007bff; /* Blue color for links */
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline; /* Underline on hover */
}

/* Scroll to Top Button Styles */
.scroll-to-top {
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: none;
    width: 40px;
    height: 40px;
    text-align: center;
    line-height: 40px;
    background-color: #4e73df;
    color: white;
    border-radius: 50%;
    text-decoration: none;
    z-index: 1000;
    cursor: pointer;
}

.scroll-to-top:hover {
    background-color: #2e59d9;
}

.scroll-to-top {
    display: block;
}
</style>
