import Vue from "vue";
import $ from "jquery";
import VueResource from "vue-resource";
import VueProgressiveImage from "vue-progressive-image";

Vue.component("xray-upload", require("./components/XrayUpload.vue"));
Vue.component("image-upload", require("./components/ImageUpload.vue"));

Vue.use(VueResource);
Vue.use(VueProgressiveImage);

const app = new Vue({
    el: "#app"
});
