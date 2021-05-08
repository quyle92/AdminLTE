import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import Dashboard from './components/Dashboard'
import Profile from './components/Profile'
import NotFoundComponent from './components/NotFoundComponent'
import User from './components/User'

const routes = [
	{
		path:'/',
		component: Dashboard,
		name: 'dashboard'
	},
	{
		path: '/user', 
      	component: User,
      	name: 'user'
	},
	{
		path:'/profile',
		component: Profile,
		name: 'profile'
	},
	{
		path: '/:catchAll(.*)', 
      	component: NotFoundComponent,
      	name: 'NotFound'
	}//(1)

];

export default new Router({
	mode: 'history',
	routes,
	linkActiveClass:  "active"
});

/*
Note
 */
//(1) phải đặt ở cuối cùng ko thì những route ở sau nó sẽ ko chạy