<template>
    <div>
        <!-- Search Bar -->
        <div class="mb-3">
            <input
                v-model="searchQuery"
                type="text"
                class="form-control search-bar"
                placeholder="Search employees..."
            />
        </div>

        <!-- Employee Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="employee in filteredEmployees"
                        :key="employee.id"
                    >
                        <td>
                            <img
                                :src="getEmployeePhoto(employee.photo)"
                                alt="Employee Photo"
                                class="employee-photo"
                            />
                        </td>
                        <td>{{ employee.name }}</td>
                        <td>{{ employee.phone }}</td>
                        <td>${{ employee.salary }}</td>
                        <td>{{ employee.joining_date }}</td>
                        <td>
                            <button
                                class="btn btn-sm btn-primary me-2"
                                @click="editEmployee(employee)"
                            >
                                Edit
                            </button>
                            <button
                                class="btn btn-sm btn-danger"
                                @click="deleteEmployee(employee.id)"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Edit Employee Modal -->
        <div v-if="showEditModal" class="modal-backdrop">
            <div class="modal-content">
                <h2>Edit Employee</h2>
                <form @submit.prevent="updateEmployee">
                    <div class="mb-3">
                        <label>Name</label>
                        <input
                            v-model="editEmployeeData.name"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input
                            v-model="editEmployeeData.phone"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label>Salary</label>
                        <input
                            v-model="editEmployeeData.salary"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label>Joining Date</label>
                        <input
                            v-model="editEmployeeData.joining_date"
                            class="form-control"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label>Photo</label>
                        <input
                            type="file"
                            @change="handlePhotoUpload"
                            class="form-control"
                        />
                    </div>
                    <div class="text-end">
                        <button
                            type="button"
                            class="btn btn-secondary me-2"
                            @click="showEditModal = false"
                        >
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </form>
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
            employees: [],
            searchQuery: "",
            showEditModal: false,
            editEmployeeData: {
                id: null,
                name: "",
                phone: "",
                salary: "",
                joining_date: "",
                photo: null,
            },
        };
    },
    computed: {
        filteredEmployees() {
            return this.employees.filter((employee) => {
                return (
                    employee.name
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) ||
                    employee.phone
                        .toLowerCase()
                        .includes(this.searchQuery.toLowerCase()) ||
                    employee.salary.toString().includes(this.searchQuery)
                );
            });
        },
    },
    created() {
        this.fetchEmployees();
    },
    methods: {
        async fetchEmployees() {
            try {
                const response = await axios.get("/api/employees");
                this.employees = response.data;
            } catch (error) {
                console.error("Error fetching employees:", error);
            }
        },
        editEmployee(employee) {
            this.editEmployeeData = { ...employee, photo: null };
            this.showEditModal = true;
        },
        async updateEmployee() {
            const formData = new FormData();
            formData.append("name", this.editEmployeeData.name);
            formData.append("phone", this.editEmployeeData.phone);
            formData.append("salary", this.editEmployeeData.salary);
            formData.append("joining_date", this.editEmployeeData.joining_date);

            if (this.editEmployeeData.photo) {
                formData.append("photo", this.editEmployeeData.photo);
            }

            try {
                await axios.post(
                    `/api/employees/${this.editEmployeeData.id}?_method=PUT`,
                    formData,
                    { headers: { "Content-Type": "multipart/form-data" } }
                );
                this.showEditModal = false;
                this.fetchEmployees();
                Swal.fire("Updated!", "Employee has been updated.", "success");
            } catch (error) {
                console.error(
                    "Error updating employee:",
                    error.response?.data || error.message
                );
            }
        },
        handlePhotoUpload(event) {
            this.editEmployeeData.photo = event.target.files[0];
        },
        async deleteEmployee(id) {
            try {
                await axios.delete(`/api/employees/${id}`);
                this.fetchEmployees();
                Swal.fire("Deleted!", "Employee has been deleted.", "success");
            } catch (error) {
                console.error(
                    "Error deleting employee:",
                    error.response?.data || error.message
                );
            }
        },
        getEmployeePhoto(photo) {
            return photo
                ? `/storage/${photo}`
                : "https://greekherald.com.au/wp-content/uploads/2020/07/default-avatar.png";
        },
    },
};
</script>

<style scoped>
.search-bar {
    max-width: 400px;
    margin-bottom: 20px;
}

.employee-photo {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
