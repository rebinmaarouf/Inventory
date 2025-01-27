<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                        </div>
                        <div v-if="errorMessage" class="alert alert-danger">
                            {{ errorMessage }}
                        </div>
                        <form @submit.prevent="loginUser" class="user">
                            <div class="form-group">
                                <input
                                    type="email"
                                    v-model="form.email"
                                    class="form-control form-control-user"
                                    placeholder="Enter Email Address..."
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <input
                                    type="password"
                                    v-model="form.password"
                                    class="form-control form-control-user"
                                    placeholder="Password"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <div
                                    class="custom-control custom-checkbox small"
                                >
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="customCheck"
                                    />
                                    <label
                                        class="custom-control-label"
                                        for="customCheck"
                                    >
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <button
                                type="submit"
                                :disabled="isSubmitting"
                                class="btn btn-primary btn-user btn-block"
                            >
                                Login
                            </button>
                        </form>
                        <hr />
                        <!-- Socialite Login Buttons -->
                        <button
                            @click="redirectToSocialite('github')"
                            class="btn btn-github btn-user btn-block"
                        >
                            <i class="fab fa-github"></i> Login with GitHub
                        </button>

                        <button
                            @click="redirectToSocialite('google')"
                            class="btn btn-google btn-user btn-block"
                        >
                            <i class="fab fa-google"></i> Login with Google
                        </button>

                        <button
                            @click="redirectToSocialite('facebook')"
                            class="btn btn-facebook btn-user btn-block"
                        >
                            <i class="fab fa-facebook"></i> Login with Facebook
                        </button>
                        <hr />
                        <div class="text-center">
                            <router-link to="/forget" class="small">
                                Forgot Password?
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
import { Ziggy } from "@/ziggy"; // Ensure the correct path
import { route } from "ziggy-js";

export default {
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            isSubmitting: false,
            errorMessage: null,
        };
    },
    methods: {
        loginUser() {
            this.isSubmitting = true;
            this.errorMessage = null;

            axios
                .post("http://localhost:8000/api/auth/login", this.form)
                .then((response) => {
                    if (response.data.access_token) {
                        localStorage.setItem(
                            "access_token",
                            response.data.access_token
                        );
                        axios.defaults.headers.common[
                            "Authorization"
                        ] = `Bearer ${response.data.access_token}`;

                        // SweetAlert2 success message
                        Swal.fire({
                            icon: "success",
                            title: "Login Successful!",
                            text: "You have successfully logged in.",
                            showConfirmButton: false,
                            timer: 1500,
                        })
                            .then(() => {
                                // Show Welcome SweetAlert message
                                Swal.fire({
                                    icon: "success",
                                    title: "Welcome!",
                                    text: "Welcome back! You will be redirected to the dashboard.",
                                    showConfirmButton: false,
                                    timer: 2000, // Automatically close after 2 seconds
                                });
                            })
                            .then(() => {
                                // Redirect to Home.vue after the SweetAlert dialog is closed
                                this.$router.push({ name: "home" });
                            });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Login Failed",
                            text: "No access token received!",
                        });
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        icon: "error",
                        title: "Login Failed",
                        text: error.response?.data?.message || "Login failed!",
                    });
                })
                .finally(() => {
                    this.isSubmitting = false;
                });
        },
        redirectToSocialite(provider) {
            try {
                const socialiteUrl = route(
                    "socialite.auth",
                    { provider },
                    false,
                    Ziggy
                );
                window.location.href = socialiteUrl;
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to redirect to social login.",
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
@media (max-width: 768px) {
    .card-body {
        padding: 3rem;
    }
}

/* Socialite Button Styles */
.btn-github {
    background-color: #333;
    color: white;
}

.btn-github:hover {
    background-color: #222;
}

.btn-google {
    background-color: #dd4b39;
    color: white;
}

.btn-google:hover {
    background-color: #c23321;
}

.btn-facebook {
    background-color: #3b5998;
    color: white;
}

.btn-facebook:hover {
    background-color: #2d4373;
}
</style>
