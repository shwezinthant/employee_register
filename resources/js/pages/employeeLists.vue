<template lang="">
    <div class="col-md-8 offset-md-2 mt-5">
        <div class="row mb-3">
            <div class="col-md-8">
                <input
                    v-model="search"
                    @input="getEmployeeLists"
                    type="text"
                    class="form-control shadow col-md-4"
                    placeholder="Search"
                />
            </div>
            <div class="col-md-4">
                <multiselect
                    v-model="statusValue"
                    :options="statusOptions"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    :custom-label="customLabel"
                    placeholder="Choose Status"
                    @input="getEmployeeLists"
                ></multiselect>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Id</th>
                    <th scope="col">Departments</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(employee, index) in employees" :key="index">
                    <th scope="row">{{ ++index }}</th>
                    <td>{{ employee.name }}</td>
                    <td>{{ employee.email }}</td>
                    <td>{{ employee.employee_id }}</td>
                    <td>{{ employee.department.name }}</td>
                    <td>{{ employee.status ? "active" : "inactive" }}</td>
                    <td>
                        <a @click="onEdit(employee.id)" class="btn btn-warning"
                            >Edit</a
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import axios from "axios";
import Multiselect from "vue-multiselect";

export default {
    components: {
        Multiselect,
    },
    name: "employeeLists",
    data() {
        return {
            search: "",
            employees: [],
            statusValue: { name: "Active", value: 1 },
            statusOptions: [
                { name: "Active", value: 1 },
                { name: "Inactive", value: 0 },
            ],
        };
    },
    methods: {
        onEdit(employee_id) {
            window.location.href = `/edit/${employee_id}`;
        },
        customLabel(option) {
            return `${option.name}`;
        },
        async getEmployeeLists() {
            let res = await axios.get(
                `/api/employees?search=${this.search}&status=${this.statusValue.value}`
            );
            this.employees = res.data.data;
        },
    },
    mounted() {
        this.getEmployeeLists();
    },
};
</script>
<style lang=""></style>
