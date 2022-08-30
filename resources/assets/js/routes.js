import Vue from 'vue'
import Router from 'vue-router'
import Dashboard from './components/admin/DashboardComponent.vue'
import DashboardAdmin from './components/admin/DashboardAdminComponent.vue'
import Adminlogin from './components/admin/AdminloginComponent.vue'
import Profile from './components/admin/ProfileComponent.vue'
import Changepassword from './components/admin/changepasswordComponent.vue'
import User from './components/admin/UserComponent.vue'
import Login from './components/LoginComponent.vue'
import Signup from './components/SignupComponent.vue'
import Home from './components/HomeComponent.vue'
import Branch from './components/admin/BranchComponent.vue'
import Groupcode from './components/admin/GroupcodeComponent.vue'
import Businesscategory from './components/admin/CategoryComponent.vue'
//import Productsize from './components/admin/ProductsizeComponent.vue'
//import Productcolor from './components/admin/ProductcolorComponent.vue'
import Customer from './components/admin/CustomerComponent.vue'
import Customerorderlist from './components/admin/CustomerorderlistComponent.vue'
import Thanalist from './components/admin/ThanaComponent.vue'
import Districtlist from './components/admin/DistrictComponent.vue'
import Company from './components/admin/CompanyComponent.vue'
import Team from './components/admin/TeamComponent.vue'
import Partner from './components/admin/BusinesspartnerComponent.vue'
import Product from './components/admin/ProductComponent.vue'
import Productdetail from './components/admin/ProductdetailComponent.vue'
import Expensecategory from './components/admin/ExpensecategoryComponent.vue'
import Vendor from './components/admin/VendorComponent.vue'
import Createpurchaseorder from './components/admin/CreatepurchaseorderComponent.vue'
import Purchaseorderdetail from './components/admin/PurchaseorderdetailComponent.vue'
import Purchaseorder from './components/admin/PurchaseorderComponent.vue'
import Discount from './components/admin/DiscountComponent.vue'
import Tax from './components/admin/TaxComponent.vue'
import Deliveryagent from './components/admin/DeliveryagentComponent.vue'
import Newsalesorder from './components/admin/NewsalesorderComponent.vue'
import Salesorderdetail from './components/admin/SalesorderdetailComponent.vue'
import Salesorder from './components/admin/SalesorderComponent.vue'
import switchCompany from './components/admin/SwitchcompanyComponent.vue'
import VariationLabel from './components/admin/VariationlabelComponent.vue'
import Variation from './components/admin/VariationComponent.vue'
import Incomingpayment from './components/admin/IncomingpaymentComponent.vue'
import Outgoingpayment from './components/admin/OutgoingpaymentComponent.vue'
import Inventoryreport from './components/admin/InventoryComponent.vue'
import PurchaseOrderReport from './components/admin/PurchaseOrderReportComponent.vue'
import NewSignup from './components/admin/NewSignupComponent.vue'
import Activity from './components/admin/ActivityComponent.vue'
import ActivityDetail from './components/admin/ActivityDetailComponent.vue'
import AdminUserPanel from './components/admin/AdminAsUserComponent.vue'

Vue.use(Router)


const router = new Router({
  mode: 'hash',
  routes: [
    {
        path:'/login',
        component:Login,
        props: true,
    },
      {
          path:'/adminlogin',
          component:Adminlogin,
          props: true,
      },
      {
        path:'/adminuserloginpanel',
        component:AdminUserPanel,
        props: true,
    },
    {
      path:'/signup/:utype',
      component:Signup,
      props: true,
    },
    {
      path:'/newsignup',
      component:NewSignup,
      props: true,
      meta: {
      requiresAuth: true
    }
  },
    {
        path:'/dashboard',
        component:Dashboard,
        name: 'dashboard',
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
      path:'/changepassword',
      component:Changepassword,
      props: true,
      meta: {
      requiresAuth: true
    }
  },
    {
        path:'/userslist',
        name: 'userslist',
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
 /* {
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
  },*/
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
  {
    path:'/thanalist',
    component:Thanalist,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/districtlist',
    component:Districtlist,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/company',
    component:Company,
    props: true,
    meta: {
    requiresAuth: true
    }
  },
  {
    path:'/team',
    component:Team,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/businesspartner',
    component:Partner,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/product',
    component:Product,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/product/:pid',
    component:Productdetail,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/inventoryreport/:pid',
    component:Productdetail,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/expensecategory',
    component:Expensecategory,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/vendor',
    component:Vendor,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/createpurchaseorder',
    component:Createpurchaseorder,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/purchaseorder',
    component:Purchaseorder,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
   {
    path:'/purchaseorder/:poid',
    component:Purchaseorderdetail,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/purchaseorderreport/:poid',
    component:Purchaseorderdetail,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/newsalesorder',
    component:Newsalesorder,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/salesorder',
    component:Salesorder,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/salesorder/:soid',
    component:Salesorderdetail,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/discount',
    component:Discount,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/tax',
    component:Tax,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/deliveryagent',
    component:Deliveryagent,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/sw',
    component:switchCompany,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/variationlabel',
    component:VariationLabel,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/variation',
    component:Variation,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/incomingpayment',
    component:Incomingpayment,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/outgoingpayment',
    component:Outgoingpayment,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/inventoryreport',
    component:Inventoryreport,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/purchaseorderreport',
    component:PurchaseOrderReport,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/activity',
    component:Activity,
    props: true,
    meta: {
    requiresAuth: true
   }
  },
  {
    path:'/activitydetail/:vid',
    component:ActivityDetail,
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
   Vue.prototype.$userType = document.querySelector("meta[name='user-type']").getAttribute('content');
   console.log("router=",Vue.prototype.$userType)

    const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
    console.log('Auth',requiresAuth)
    //console.log('currentUser',currentUser)
    if (requiresAuth && !Vue.prototype.$userId) next('/')
    else if (!requiresAuth && Vue.prototype.$userId) next('/dashboard')
    else next()
  })

export default router