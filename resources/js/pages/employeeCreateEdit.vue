<template lang="">
    <div class="container col-md-6">
        <h2 class="text-danger my-3">
            Employee {{ isediting == "edit" ? "Update" : "Register" }}
        </h2>
        <form class="row g-3">
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Name</label>
                <input
                    autocomplete="off"
                    v-model="name"
                    type="text"
                    class="form-control"
                    id="inputEmail4"
                />
                <div
                    v-show="validationMsg.name"
                    class="text-danger"
                    style="font-size: 14px"
                >
                    {{ validationMsg.name }}
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputEmail" class="form-label">Email</label>
                <input
                    autocomplete="off"
                    type="email"
                    v-model="email"
                    class="form-control"
                    id="inputEmail"
                />
                <div
                    v-show="validationMsg.email"
                    class="text-danger"
                    style="font-size: 14px"
                >
                    {{ validationMsg.email }}
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputId" class="form-label">ID</label>
                <input
                    autocomplete="off"
                    type="type"
                    v-model="employee_id"
                    class="form-control"
                    id="inputId"
                />
                <div
                    v-show="validationMsg.employee_id"
                    class="text-danger"
                    style="font-size: 14px"
                >
                    {{ validationMsg.employee_id }}
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputState" class="form-label"
                    >Employment Status
                </label>
                <multiselect
                    v-model="statusValue"
                    :options="statusOptions"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    :custom-label="customLabel"
                    placeholder="Choose Status"
                ></multiselect>
                <div
                    v-show="validationMsg.statusValue"
                    class="text-danger"
                    style="font-size: 14px"
                >
                    {{ validationMsg.statusValue }}
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputState" class="form-label">Departments </label>
                <multiselect
                    v-model="departmentValue"
                    :options="departmentOptions"
                    :searchable="false"
                    :close-on-select="true"
                    :show-labels="false"
                    :custom-label="customLabel"
                    placeholder="Choose Department"
                ></multiselect>
                <div
                    v-show="validationMsg.departmentValue"
                    class="text-danger"
                    style="font-size: 14px"
                >
                    {{ validationMsg.departmentValue }}
                </div>
            </div>
            <div class="col-md-6">
                <label for="" class="form-label">File </label>
                <input type="file" @change="onChange" class="form-control" />
                <div
                    v-show="validationMsg.file"
                    class="text-danger"
                    style="font-size: 14px"
                >
                    {{ validationMsg.file }}
                </div>
            </div>
            <div class="col-12">
                <label for="inputAddress" class="form-label"
                    >Accommodation requests</label
                >
                <input
                    autocomplete="off"
                    v-model="accommodation_requests"
                    type="text"
                    class="form-control"
                    id="inputAddress"
                    placeholder=""
                />
                <div
                    v-show="validationMsg.accommodation_requests"
                    class="text-danger"
                    style="font-size: 14px"
                >
                    {{ validationMsg.accommodation_requests }}
                </div>
            </div>
            <div class="col-12">
                <button
                    @click.prevent="updateOrCreate"
                    type="submit"
                    class="btn btn-primary"
                >
                    {{ isediting == "edit" ? "Edit" : "Add" }}
                    Employee
                </button>
            </div>
        </form>
    </div>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        Multiselect,
    },
    name: "employeeCreateEdit",
    props: {
        isediting: String,
    },
    data() {
        return {
            id: "",
            name: "",
            email: "",
            employee_id: "",
            statusValue: { name: "Active", value: 1 },
            statusOptions: [
                { name: "Active", value: 1 },
                { name: "Inactive", value: 0 },
            ],
            departmentValue: "",
            departmentOptions: [],
            accommodation_requests: "",
            file: {},
            isValidationSuccess: false,
            validationMsg: {
                name: "",
                email: "",
                employee_id: "",
                statusValue: "",
                departmentValue: "",
                accommodation_requests: "",
                file: "",
            },
        };
    },
    methods: {
        customLabel(option) {
            return `${option.name}`;
        },
        onChange(event) {
            this.file = {
                name: event.target.files[0].name,
                data: event.target.files[0],
            };
        },
        formDataToSubmit() {
            let form_data = new FormData();

            if (this.isediting == "edit") {
                form_data.append("id", this.id);
            }
            form_data.append("name", this.name);
            form_data.append("email", this.email);
            form_data.append("employee_id", this.employee_id);
            form_data.append("status", this.statusValue.value);
            form_data.append("department_id", this.departmentValue.id);
            form_data.append(
                "accommodation_requests",
                this.accommodation_requests
            );

            if (Object.keys(this.file).length > 0) {
                form_data.append("file", this.file.data, this.file.name);
            }
            return form_data;
        },
        async updateOrCreate(e) {
            e.preventDefault;
            this.validateRules();
            if (
                this.name != "" &&
                this.email != "" &&
                this.employee_id != "" &&
                this.status != "" &&
                this.department_id != "" &&
                this.accommodation_requests != ""
            ) {
                let form_data = this.formDataToSubmit();
                let res = axios
                    .post(`/api/employees`, form_data)
                    .then((res) => {
                        this.$swal(
                            `${
                                this.isediting == "create"
                                    ? "Created!"
                                    : "Updated!"
                            }`,
                            `Employee has been ${this.isediting}d.`,
                            "success"
                        );
                        window.location.href = "/";
                    })
                    .catch((error) => {
                        // this.setApiLoading(false);
                        this.validateResError(error);
                    });
            } else {
                return;
            }
        },
        validateRules() {
            this.isValidationSuccess = true;
            this.resetvalidateRulesMsg();
            if (this.name === "") {
                this.validationMsg.name = "Name is required.";
                this.isValidationSuccess = false;
            }
            if (this.email === "") {
                this.validationMsg.email = "Email is required.";
                this.isValidationSuccess = false;
            }
            if (this.employee_id === "") {
                this.validationMsg.employee_id = "Employee Id is required.";
                this.isValidationSuccess = false;
            }

            if (this.statusValue == "") {
                this.validationMsg.statusValue = "Status is required.";
                this.isValidationSuccess = false;
            }
            if (this.departmentValue == "") {
                this.validationMsg.departmentValue = "Department is required.";
                this.isValidationSuccess = false;
            }

            if (this.accommodation_requests == "") {
                this.validationMsg.accommodation_requests =
                    "Accommodation requests is required.";
                this.isValidationSuccess = false;
            }
            if (
                Object.keys(this.file).length <= 0 &&
                this.isediting == "create"
            ) {
                this.validationMsg.file = "File required.";
                this.isValidationSuccess = false;
            }
        },
        resetvalidateRulesMsg() {
            this.validationMsg.name = "";
            this.validationMsg.email = "";
            this.validationMsg.employee_id = "";
            this.validationMsg.statusValue = "";
            this.validationMsg.departmentValue = "";
            this.validationMsg.accommodation_requests = "";
            this.validationMsg.file = "";
        },
        validateResError(error) {
            console.log("error");
            if (error.response.data.data.email[0] != null) {
                this.validationMsg.email = error.response.data.data.email[0];
            }
        },
        async getEmployeeDetail() {
            let uri = window.location.href.split("/");
            let uriLast = uri.length - 1;
            let res = await axios.get(`/api/employees/${uri[uriLast]}`);
            res = res.data.data;
            this.id = res.id;
            this.name = res.name;
            this.email = res.email;
            this.employee_id = res.employee_id;
            this.departmentValue = res.department;
            this.accommodation_requests = res.accommodation_requests;
            if (res.status == 1) {
                this.statusValue = { name: "Active", value: 1 };
            } else {
                this.statusValue = { name: "Inactive", value: 0 };
            }
        },
        async getDepartments() {
            let res = await axios.get(`/api/departments`);
            console.log("depr", res.data.data);
            this.departmentOptions = res.data.data;
        },
    },
    mounted() {
        if (this.isediting == "edit") {
            console.log("l");
            this.getEmployeeDetail();
        }
        this.getDepartments();
    },
};
</script>
<style lang=""></style>
