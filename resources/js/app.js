require("./bootstrap");
import Vue from "vue";
import VueSweetalert2 from "vue-sweetalert2";

// import { store } from "./Store";
// import "tw-elements";

Vue.component("employeeLists", require("./pages/employeeLists.vue").default);
Vue.component(
    "employeeCreateEdit",
    require("./pages/employeeCreateEdit.vue").default
);

Vue.use(VueSweetalert2);

const app = new Vue({
    el: "#app",
});
