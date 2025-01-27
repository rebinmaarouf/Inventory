<template>
    <nav
        :class="[
            'navbar',
            'navbar-expand',
            'navbar-light',
            'bg-white',
            'topbar',
            'mb-4',
            'static-top',
            'shadow',
            { 'topbar-hidden': isTopbarHidden },
        ]"
        id="topbar"
    >
        <!-- Sidebar Toggle (Topbar) -->
        <button
            id="sidebarToggleTop"
            class="btn btn-link d-md-none rounded-circle mr-3"
        >
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Search -->
        <form
            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"
            @submit.prevent="searchProduct"
        >
            <div class="input-group">
                <input
                    type="text"
                    v-model="searchQuery"
                    class="form-control bg-light border-0 small"
                    placeholder="Search for..."
                    aria-label="Search"
                    aria-describedby="basic-addon2"
                />
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="alertsDropdown"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <i class="fas fa-bell fa-fw"></i>
                    <span class="badge badge-danger badge-counter">{{
                        notifications.length
                    }}</span>
                </a>
                <!-- Dropdown - Alerts -->
                <div
                    class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="alertsDropdown"
                >
                    <h6 class="dropdown-header">Alerts Center</h6>
                    <a
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="dropdown-item d-flex align-items-center"
                        @click="viewProductDetails(notification.productId)"
                    >
                        <div class="mr-3">
                            <div
                                class="icon-circle"
                                :class="notification.class"
                            >
                                <i
                                    class="fas fa-exclamation-triangle text-white"
                                ></i>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">
                                {{ notification.date }}
                            </div>
                            <span class="font-weight-bold">{{
                                notification.message
                            }}</span>
                        </div>
                    </a>
                    <a
                        class="dropdown-item text-center small text-gray-500"
                        href="#"
                        >Show All Alerts</a
                    >
                </div>
            </li>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="userDropdown"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"
                        >Valerie Luna</span
                    >
                    <img
                        :src="postingPhoto"
                        class="img-fluid px-3 px-sm-4 mt-3 mb-4"
                        style="width: 10rem"
                        alt="Posting Photo"
                    />
                </a>
                <!-- Dropdown - User Information -->
                <div
                    class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown"
                >
                    <a class="dropdown-item" href="#">
                        <i
                            class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"
                        ></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i
                            class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"
                        ></i>
                        Settings
                    </a>
                    <a class="dropdown-item" href="#">
                        <i
                            class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"
                        ></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a
                        class="dropdown-item"
                        href="#"
                        id="logoutLink"
                        @click="logoutUser"
                    >
                        <i
                            class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"
                        ></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";
const postingPhoto = "/backend/img/flag.png";

export default {
    name: "Topbar",
    data() {
        return {
            refreshInterval: null,
            postingPhoto,
            isTopbarHidden: false,
            lastScrollPosition: 0,
            searchQuery: "",
            notifications: [],
        };
    },
    methods: {
        fetchNotifications() {
            axios
                .get("/api/products")
                .then((response) => {
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
                                product.quantity === 0
                                    ? "bg-danger"
                                    : "bg-warning",
                        }));
                })
                .catch((error) => {
                    console.error("Error fetching notifications:", error);
                });
        },
        startNotificationPolling() {
            // setInterval(this.fetchNotifications, 60000); // Fetch notifications every minute
            this.notificationInterval = setInterval(
                this.fetchNotifications,
                60000
            );
        },
        viewProductDetails(productId) {
            this.$router.push({
                name: "ProductDetails",
                params: { id: productId },
            });
            this.fetchNotifications(); // Refresh notifications after navigation
        },
        searchProduct() {
            axios
                .get(`/api/products/search?barcode=${this.searchQuery}`)
                .then((response) => {
                    this.$router.push({
                        name: "ProductDetails",
                        params: { id: response.data.id },
                    });
                })
                .catch((error) => {
                    console.error("Error searching product:", error);
                });
        },
        refreshToken() {
            axios
                .post("/api/auth/refresh")
                .then((response) => {
                    localStorage.setItem(
                        "access_token",
                        response.data.access_token
                    );
                })
                .catch((error) => {
                    console.error("Token refresh failed:", error);
                    localStorage.removeItem("access_token");
                    this.$router.push("/");
                });
        },
        logoutUser() {
            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, log out!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = localStorage.getItem("access_token");
                    axios
                        .post(
                            "api/auth/logout",
                            {},
                            {
                                headers: { Authorization: `Bearer ${token}` },
                            }
                        )
                        .then((response) => {
                            localStorage.clear();
                            if (this.refreshInterval) {
                                clearInterval(this.refreshInterval);
                                this.refreshInterval = null;
                            }
                            this.$router.push("/");
                        })
                        .catch((error) => {
                            console.error("Logout failed:", error);
                            Swal.fire({
                                icon: "error",
                                title: "Logout Failed",
                                text:
                                    error.response.data.message ||
                                    "An error occurred while logging out. Please try again.",
                            });
                        });
                }
            });
        },
        onScroll() {
            const currentScrollPosition =
                window.pageYOffset || document.documentElement.scrollTop;
            if (currentScrollPosition < 0) return;
            if (Math.abs(currentScrollPosition - this.lastScrollPosition) < 60)
                return;
            this.isTopbarHidden =
                currentScrollPosition > this.lastScrollPosition;
            this.lastScrollPosition = currentScrollPosition;
        },
    },
    mounted() {
        this.refreshInterval = setInterval(() => {
            this.refreshToken();
        }, 300000);
        window.addEventListener("scroll", this.onScroll);
        this.fetchNotifications();
        this.startNotificationPolling();
    },
    beforeDestroy() {
        window.removeEventListener("scroll", this.onScroll);
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }
        clearInterval(this.notificationInterval);
    },
};
</script>

<style scoped>
.topbar {
    transition: transform 0.3s ease-in-out;
}

.topbar-hidden {
    transform: translateY(-100%);
}
</style>
