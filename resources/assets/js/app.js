require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('vue-resource'));
Vue.component('pagination', require('laravel-vue-pagination'));
//Vue.use(require('vue-moment'));
//Import Vue Filter
require('./filter');

//Import progressbar
require('./progressbar');

//Setup custom events
require('./customEvents');

//Import View Router
import VueRouter from 'vue-router';

//import VueTelInput from 'vue-tel-input';
/*import VueCurrencyInput from 'vue-currency-input'

Vue.use(VueCurrencyInput)*/


//Vue.use(VueTelInput)
//import Dropdown from 'vue-simple-search-dropdown';
//Vue.use(Dropdown);


Vue.use(VueRouter)

//Import Sweetalert2
import Swal from 'sweetalert2'
window.Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast

//Import v-from
import { Form, HasError, AlertError } from 'vform'
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)



//Routes
import router from './routes';

//import material-icon scss
import "font-awesome/css/font-awesome.min.css";


//Register Routes
/*const routers = new VueRouter({
    router, 
    mode: 'hash',

})*/
/*Vue.use(VueGlobalVariable, {
  globals: {
  user: 
  },
  });*/



//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
    router: router,
});

