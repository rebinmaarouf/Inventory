<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">
                                Create an Account!
                            </h1>
                        </div>
                        <form @submit.prevent="handleRegister" class="user">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input
                                        type="text"
                                        v-model="firstName"
                                        class="form-control form-control-user"
                                        placeholder="First Name"
                                        required
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <input
                                        type="text"
                                        v-model="lastName"
                                        class="form-control form-control-user"
                                        placeholder="Last Name"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="form-group">
                                <input
                                    type="email"
                                    v-model="email"
                                    class="form-control form-control-user"
                                    placeholder="Email Address"
                                    required
                                />
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input
                                        type="password"
                                        v-model="password"
                                        class="form-control form-control-user"
                                        placeholder="Password"
                                        required
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <input
                                        type="password"
                                        v-model="confirmPassword"
                                        class="form-control form-control-user"
                                        placeholder="Repeat Password"
                                        required
                                    />
                                </div>
                            </div>
                            <button
                                type="submit"
                                class="btn btn-primary btn-user btn-block"
                            >
                                Register Account
                            </button>
                            <hr />
                            <button
                                type="button"
                                class="btn btn-google btn-user btn-block"
                                @click="handleOAuthRegister('google')"
                            >
                                <i class="fab fa-google fa-fw"></i>
                                Register with Google
                            </button>
                            <button
                                type="button"
                                class="btn btn-facebook btn-user btn-block"
                                @click="handleOAuthRegister('facebook')"
                            >
                                <i class="fab fa-facebook-f fa-fw"></i>
                                Register with Facebook
                            </button>
                        </form>
                        <hr />
                        <div class="text-center">
                            <router-link to="/forget" class="small">
                                Forgot Password?
                            </router-link>
                        </div>
                        <div class="text-center">
                            <router-link to="/" class="small">
                                Already have an account? Login!
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
    name: "register",
    data() {
        return {
            firstName: "",
            lastName: "",
            email: "",
            password: "",
            confirmPassword: "",
        };
    },
    methods: {
        async handleRegister() {
            if (this.password !== this.confirmPassword) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Passwords do not match!",
                });
                return;
            }

            try {
                const response = await axios.post("/api/auth/register", {
                    first_name: this.firstName,
                    last_name: this.lastName,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.confirmPassword,
                });

                if (response.data.access_token) {
                    localStorage.setItem(
                        "access_token",
                        response.data.access_token
                    );

                    // SweetAlert2 success message
                    Swal.fire({
                        icon: "success",
                        title: "Registration Successful!",
                        text: "You have successfully registered.",
                        showConfirmButton: false,
                        timer: 1500,
                    }).then(() => {
                        // Redirect to Home with a query parameter
                        this.$router.push({
                            path: "/Home",
                            query: { welcome: "true" },
                        });
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Registration Failed",
                        text: "No token received.",
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Registration Failed",
                    text: error.response.data.message || "Registration failed!",
                });
            }
        },
        async handleOAuthRegister(provider) {
            try {
                window.location.href = `/api/auth/${provider}`;
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "OAuth registration failed!",
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
