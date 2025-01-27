<template>
    <div class="container">
        <div class="row justify-content-center align-items-center vh">
            <div class="col-md-8 col-lg-12">
                <div
                    class="d-flex justify-content-between align-items-center mb-3"
                >
                    <h1 class="h4 text-gray-900">🧑‍💼 Employee Edit</h1>
                    <button class="btn btn-success" @click="goToEmployeeList">
                        <i class="fas fa-users"></i> All Employees
                    </button>
                </div>

                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form @submit.prevent="updateEmployee">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="employee.name"
                                        type="text"
                                        class="form-control"
                                        placeholder="Full Name"
                                        required
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <input
                                        v-model="employee.email"
                                        type="email"
                                        class="form-control"
                                        placeholder="Email Address"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="employee.phone"
                                        type="text"
                                        class="form-control"
                                        placeholder="Phone Number"
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <input
                                        v-model.number="employee.salary"
                                        type="number"
                                        class="form-control"
                                        placeholder="Salary"
                                        required
                                    />
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-6 mb-3">
                                    <input
                                        v-model="employee.nid"
                                        type="text"
                                        class="form-control"
                                        placeholder="NID Number"
                                    />
                                </div>
                                <div class="col-sm-6">
                                    <input
                                        v-model="employee.joining_date"
                                        type="date"
                                        class="form-control"
                                    />
                                </div>
                            </div>

                            <div class="form-group">
                                <textarea
                                    v-model="employee.address"
                                    class="form-control"
                                    placeholder="Address"
                                ></textarea>
                            </div>

                            <div class="form-group">
                                <input
                                    type="file"
                                    @change="handleFileUpload"
                                    class="form-control"
                                />
                            </div>

                            <div v-if="photoPreview" class="text-center mb-3">
                                <img
                                    :src="photoPreview"
                                    alt="Employee Photo"
                                    class="img-thumbnail"
                                    width="150"
                                />
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary btn-block"
                            >
                                Update
                            </button>
                        </form>
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
            employee: {
                id: null,
                name: "",
                email: "",
                phone: "",
                address: "",
                salary: "",
                nid: "",
                joining_date: "",
                photo: null,
            },
            photoPreview: null,
        };
    },
    created() {
        this.getEmployeeData();
    },
    methods: {
        async getEmployeeData() {
            const id = this.$route.params.id;
            try {
                const response = await axios.get(`/api/employees/${id}`);
                this.employee = response.data;
                this.photoPreview = `/storage/${this.employee.photo}`;
            } catch (error) {
                Swal.fire("Error", "Failed to fetch employee details", "error");
            }
        },
        async updateEmployee() {
            const formData = new FormData();
            Object.keys(this.employee).forEach((key) => {
                formData.append(key, this.employee[key]);
            });

            if (this.employee.photo instanceof File) {
                formData.append("photo", this.employee.photo);
            }

            try {
                await axios.post(
                    `/api/employees/${this.employee.id}`,
                    formData,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
                Swal.fire(
                    "Success",
                    "Employee updated successfully!",
                    "success"
                );
            } catch (error) {
                Swal.fire("Error", "Something went wrong!", "error");
            }
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.employee.photo = file;

            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.photoPreview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        goToEmployeeList() {
            this.$router.push("/all-employees");
        },
    },
};
</script>

<style scoped>
.card {
    background-color: #ffffff;
    border: 1px solid #ddd;
    border-radius: 10px;
}
.vh {
    height: 85vh;
    display: flex;
    align-items: center;
}
.img-thumbnail {
    border: 2px solid #007bff;
}
</style>
