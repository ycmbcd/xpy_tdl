import Vue from 'vue';
import App from './App.vue';
import router from './router'
import store from './store'

// font-awesome
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon.vue'
Vue.component('v-icon', Icon)

// axios
import axios from 'axios'
Vue.prototype.$axios = axios

// ui
import HeyUI from "heyui"
require("heyui/themes/index.css")
Vue.use(HeyUI)

// 全局方法
import Global from './Global'
Vue.use(Global)

// Vue.prototype.$server = 'http://tdl.cc'
Vue.prototype.$server = ''

new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
})

Vue.config.productionTip = false;
