<template>
    <div id="app">
        <router-view></router-view>
        <button @click="logoutUser" class="btn btn-danger">Logout</button>
    </div>
</template>

<script>
import axios from "axios";
import Swal from "sweetalert2";

export default {
    name: "dashboard",
    data() {
        return {
            refreshInterval: null, // Store the interval ID
        };
    },
    methods: {
        refreshToken() {
            axios
                .post("/api/auth/refresh")
                .then((response) => {
                    localStorage.setItem(
                        "access_token",
                        response.data.access_token
                    );
                    console.log("Token refreshed:", response.data.access_token);
                })
                .catch((error) => {
                    console.error("Token refresh failed:", error);
                    localStorage.removeItem("access_token");
                    this.$router.push("/"); // Redirect to login if refresh fails
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
                    console.log("Token retrieved:", token); // Debugging

                    // Validate token format
                    if (!token || token.split(".").length !== 3) {
                        Swal.fire({
                            icon: "error",
                            title: "Logout Failed",
                            text: "Invalid token. Please log in again.",
                        });
                        return;
                    }

                    axios
                        .post(
                            "http://localhost:8000/api/auth/logout",
                            {},
                            {
                                headers: {
                                    Authorization: `Bearer ${token}`,
                                },
                            }
                        )
                        .then((response) => {
                            console.log("Logout response:", response.data); // Debugging
                            localStorage.removeItem("access_token");
                            clearInterval(this.refreshInterval); // Clear the refresh interval
                            this.$router.push("/"); // Redirect to login after logout
                        })
                        .catch((error) => {
                            console.error("Logout failed:", error);
                            if (error.response) {
                                console.error(
                                    "Backend response:",
                                    error.response.data
                                );
                                Swal.fire({
                                    icon: "error",
                                    title: "Logout Failed",
                                    text:
                                        error.response.data.message ||
                                        "An error occurred while logging out. Please try again.",
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Logout Failed",
                                    text: "An error occurred while logging out. Please try again.",
                                });
                            }
                        });
                }
            });
        },
    },
    mounted() {
        this.refreshInterval = setInterval(() => {
            this.refreshToken();
        }, 300000); // 5 minutes
    },
};
</script>

<style scoped>
/* Add your styles here */
</style>
