<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">
                                Forgot Password
                            </h1>
                        </div>
                        <form
                            @submit.prevent="handlePasswordReset"
                            class="user"
                        >
                            <div class="form-group">
                                <input
                                    type="email"
                                    v-model="email"
                                    class="form-control form-control-user"
                                    placeholder="Enter Email Address..."
                                    required
                                />
                            </div>
                            <button
                                type="submit"
                                class="btn btn-primary btn-user btn-block"
                            >
                                Reset Password
                            </button>
                        </form>
                        <hr />
                        <div class="text-center">
                            <router-link to="/" class="small">
                                Back to Login
                            </router-link>
                        </div>
                        <div class="text-center">
                            <router-link to="/register" class="small">
                                Create an Account!
                            </router-link>
                        </div>
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
    name: "forgot-password",
    data() {
        return {
            email: "",
        };
    },
    methods: {
        async handlePasswordReset() {
            try {
                const response = await axios.post(
                    "http://localhost:8000/api/auth/forgot-password",
                    {
                        email: this.email,
                    }
                );

                if (response.data.message) {
                    // SweetAlert2 success message
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.data.message,
                    }).then(() => {
                        this.$router.push({
                            name: "reset",
                            params: { email: this.email },
                        });
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Failed to send password reset link.",
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text:
                        error.response.data.error ||
                        "An error occurred. Please try again.",
                });
            }
        },
    },
};
</script>

<style scoped>
.card {
    background-color: transparent;
    border: none;
}
.custom-vh {
    height: 90vh; /* Adjust this value as needed */
    display: flex;
    align-items: center;
}
</style>
