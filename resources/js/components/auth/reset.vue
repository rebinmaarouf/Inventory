<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">
                                Reset Password
                            </h1>
                        </div>
                        <form
                            @submit.prevent="handleResetPassword"
                            class="user"
                        >
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
                                    placeholder="New Password"
                                    required
                                />
                            </div>
                            <div class="form-group">
                                <input
                                    type="password"
                                    v-model="form.password_confirmation"
                                    class="form-control form-control-user"
                                    placeholder="Confirm Password"
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
                            <router-link to="/login" class="small">
                                Back to Login
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
    data() {
        return {
            form: {
                email: this.$route.query.email || "",
                password: "",
                password_confirmation: "",
                token: this.$route.params.token || "",
            },
        };
    },
    methods: {
        async handleResetPassword() {
            try {
                const response = await axios.post(
                    "/api/auth/reset-password",
                    this.form
                );

                // SweetAlert2 success message
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.data.message,
                }).then(() => {
                    this.$router.push("/");
                });
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text:
                        error.response.data.message ||
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
