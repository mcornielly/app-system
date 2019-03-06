
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

Vue.component('category-component', require('./components/CategoryComponent.vue'));
Vue.component('product-component', require('./components/ProductComponent.vue'));
Vue.component('client-component', require('./components/ClientComponent.vue'));
Vue.component('provider-component', require('./components/ProviderComponent.vue'));
Vue.component('role-component', require('./components/RoleComponent.vue'));
Vue.component('user-component', require('./components/UserComponent.vue'));
Vue.component('income-component', require('./components/IncomeComponent.vue'));
Vue.component('sale-component', require('./components/SaleComponent.vue'));
Vue.component('dashboard-component', require('./components/DashboardComponent.vue'));
Vue.component('consultincome-component', require('./components/ConsultIncomeComponent.vue'));
Vue.component('consultsale-component', require('./components/ConsultSaleComponent.vue'));
Vue.component('notification-component', require('./components/NotificationComponent.vue'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data:{
    	menu: 0, 
    	notifications: []
    },
    created() {
    	let me = this;
    	axios.post('notification/get').then(function(response){
    		// console.log(response.data);
    		me.notifications = response.data;
    	}).catch(function(error){
    		console.log(error);
    	});

    	var userId = $('meta[name="userId"]').attr('content');

    	Echo.private('App.User.' + userId).notification((notification) => {
    		me.notifications.unshift(notification);
    	});
    }
});
