import Vue from 'vue'
import store from './store'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

import Defaultcontainer from './components/container/Main.vue'


import Login from './components/auth/Login'
import Logout from './components/auth/Logout'
import Register from './components/auth/Register'
import Dashboard from './components/Dashboard'
import Suppliers from './components/Suppliers'
import Products from './components/Products'
import Orders from './components/Orders'


let router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'open active',
    scrollBehavior: () => ({ y: 0 }),
    routes: [
      {
        path: '/',
        redirect: '/dashboard',
        name: 'Home',
        component: Defaultcontainer,
        meta: { 
          requiresAuth: true
        },
        children: [
          {
            path: 'dashboard',
            name: 'Dashboard',
            component: Dashboard
          },
          {
            path: 'suppliers',
            name: 'Suppliers',
            component: Suppliers
          },
          {
            path: 'products',
            name: 'Products',
            component: Products
          },
          {
            path: 'orders',
            name: 'orders',
            component: Orders
          },                        
        ]
      },
      {
        path: '/login',
        name: 'Login',
        component: Login
      },
      {
        path: '/register',
        name: 'Register',
        component: Register
      },
      {
        path: '/logout',
        name: 'Logout',
        component: Logout
      }
    ]
       
  });
  
router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
      if (store.getters.isLoggedIn) {
        next()
        return
      }
      next('/login') 
    } else {
      next() 
    }
  })
  
export default router
