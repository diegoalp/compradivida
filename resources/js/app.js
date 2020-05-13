import Vue from 'vue'
import VueMask from 'v-mask'
import Swal from 'sweetalert2'
import JwPagination from 'jw-vue-pagination'

require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('vue-moment'));
Vue.use(VueMask);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('jw-pagination', JwPagination);
Vue.component('propostas-component', require('./components/PropostasComponent.vue').default);
Vue.component('proposta-component', require('./components/PropostaComponent.vue').default);
Vue.component('saldo-component', require('./components/SaldoComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
