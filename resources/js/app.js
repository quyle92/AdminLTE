/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('admin-lte');

window.Vue = require('vue');
//router
import router from './router'

//https://vform.vercel.app/#bootstrap
import Form from 'vform'
window.Form = Form;//(1)
import {
  Button,
  HasError,
  AlertError,
  AlertErrors,
  AlertSuccess
} from 'vform/src/components/bootstrap4'

Vue.component(Button.name, Button)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)

//gate.js
import Gate from './gate.js'; 
Vue.prototype.$Gate = new Gate( window.userType );

//helper.js
import Helper from './helper';
Vue.prototype.$Helper = new Helper;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
//vue-progressbar
import VueProgressBar from 'vue-progressbar'
const options = {
  color: '#bffaf3',
  failedColor: '#874b4b',
  thickness: '5px',
  transition: {
    speed: '1s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}
Vue.use(VueProgressBar, options)

//VueSweetalert2 
import Swal from 'sweetalert2'
window.Swal =  Swal;

// Filters
Vue.filter('Upper', function (value) {
     return value.charAt(0).toUpperCase() + value.slice(1)
});

// vue-moment
Vue.use(require('vue-moment'));

//https://github.com/ndelvalle/v-blur
import vBlur from 'v-blur'
Vue.use(vBlur)

window.vm = new Vue();

//store
import store from './store'

//Pagination
Vue.component('pagination', require('laravel-vue-pagination'));

Vue.component('test', require('./components/Test.vue').default);



const app = new Vue({
    el: '#app',
    components: {
  test: () => import('./components/Test.vue')
},
    router,
    store,
    data() {
    	return {
    		term:''
    	}
    },
    computed: {
      isShowUser() { 
         return this.$store.state.viewPermission.User
      },
      isShowPost() { 
         return this.$store.state.viewPermission.Post
      }
    },
    methods: {
      search: _.debounce(function (){//(5)
          vm.$emit('search', this.term)
      }, 2000)
    }


});


/*
Note
 */
//(1) bất cứ components nào cũng sẽ xài đc Form
