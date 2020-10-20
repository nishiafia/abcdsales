import Vue from 'vue'
import Router from 'vue-router'
import Dashboard from './components/admin/DashboardComponent.vue'
import Profile from './components/admin/ProfileComponent.vue'
import User from './components/admin/UserComponent.vue'
import Login from './components/LoginComponent.vue'
import Signup from './components/SignupComponent.vue'
import Home from './components/HomeComponent.vue'
import Branch from './components/admin/BranchComponent.vue'
import Groupcode from './components/admin/GroupcodeComponent.vue'
import Businesscategory from './components/admin/CategoryComponent.vue'
import Productsize from './components/admin/ProductsizeComponent.vue'
import Productcolor from './components/admin/ProductcolorComponent.vue'
import Customer from './components/admin/CustomerComponent.vue'
import Customerorderlist from './components/admin/CustomerorderlistComponent.vue'


Vue.use(Router)

const router = new Router({
  routes: [
    {
        path:'/login',
        component:Login,
        props: true,
    },
    {
      path:'/signup',
      component:Signup,
      props: true,
    },
    {
        path:'/dashboard',
        component:Dashboard,
        props: true,
        meta: {
        requiresAuth: true
      }
    },
    {
      path:'/branch',
      component:Branch,
      props: true,
      meta: {
      requiresAuth: true
    }
  },
    {
        path:'/profile',
        component:Profile,
        props: true,
        meta: {
        requiresAuth: true
      }
    },
    { 
        path:'/users',
        component:User,
        props: true,
        meta: {
        requiresAuth: true
      }
    },
    { 
        path:'/',
        component:Home,
        props: true,
        meta: {
          requiresAuth: false
        }
    },
    { 
        path:'/groupcode',
        component:Groupcode,
        props: true,
        meta: {
        requiresAuth: true
      }
  },
  { 
        path:'/businesscategory',
        component:Businesscategory,
        props: true,
        meta: {
        requiresAuth: true
      }
  },
  { 
        path:'/productsize',
        component:Productsize,
        props: true,
        meta: {
        requiresAuth: true
      }
  },
  { 
      path:'/productcolor',
      component:Productcolor,
      props: true,
      meta: {
      requiresAuth: true
    }
  },
  { 
      path:'/customer',
      component:Customer,
      props: true,
      meta: {
      requiresAuth: true
    }
  },
  { 
    path:'/customerorderlist/:id',
    component:Customerorderlist,
    props: true,
    meta: {
    requiresAuth: true
  }
},
  ]
  })
  router.beforeEach((to, from, next) => {
    //alert("Hi")
    if (to.path !== '/') {
    //  Auth.check()
      }
   next()
   Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');
   
   console.log("router=",Vue.prototype.$userId)
  
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    console.log('Auth',requiresAuth)
    //console.log('currentUser',currentUser)
    if (requiresAuth && !Vue.prototype.$userId) next('/')
    else if (!requiresAuth && Vue.prototype.$userId) next('/dashboard')
    else next()
  })

export default router