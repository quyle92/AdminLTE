import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)

import Dashboard from './components/Dashboard'
import Post from './components/Post'
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
      	name: 'user',
      	beforeEnter: function(to, from, next){
	      if( window.viewPermission.User !== true ){
	        Swal.fire(
              'Stop!',
              'You\'re not allowed to go here!!!',
              'error'
            ).then( () => {
                setTimeout(() => {
                     window.history.back();
                })
            });
	      }
	      next();
	    }
	},
	{
		path:'/post',
		component: Post,
		name: 'post'
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