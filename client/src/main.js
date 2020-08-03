import Vue from "vue";
import App from "./staff/App.vue";
import router from "./staff/router/router";
import store from "./staff/store/store";
import vuetify from "./plugins/vuetify";
import axios from "axios";
import VueAxios from "vue-axios";
import VueMeta from "vue-meta";

Vue.config.productionTip = false;

Vue.use(VueAxios, axios);
Vue.use(vuetify);
Vue.use(VueMeta, { refreshOnceOnNavigation: true });

new Vue({
    router,
    vuetify,
    store,
    render: h => h(App)
}).$mount("#app");
