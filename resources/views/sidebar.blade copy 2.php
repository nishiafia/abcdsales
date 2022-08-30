<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
    <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
        @if(Auth::user()->usertype === 'superadmin')
            <div class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="sidebar-header fs-5 d-none d-sm-inline">
                    <?php print Auth::user()->name; $uId=Auth::user()->id; ?>
                </span>
            </div>
            <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">

            <li >
              <router-link to="/dashboard" class="nav-link align-middle px-0">
                <i class="fa fa-fw fa-dashboard"></i>
                <span class="ms-1 d-none d-sm-inline">Dashboard</span>
              </router-link>
            </li>
            <li>
              <router-link to="/districtlist" class="nav-link align-middle px-0">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline"> Manage District</span>
              </router-link>
          </li>
            <li>
              <router-link to="/thanalist" class="nav-link align-middle px-0">
                <i class="fa fa-eye" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline"> Manage Thana</span>
              </router-link>
          </li>
            <li>
                  <router-link to="/businesscategory" class="nav-link align-middle px-0">
                    <i class="fa fa-crosshairs" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Business Category</span>
                  </router-link>
            </li>
            <li>
                <router-link to="/newsignup" class="nav-link align-middle px-0">
                  <i class="fa fa-users nav-icon"></i>
                   <span class="ms-1 d-none d-sm-inline"> New Sign-up</span>
                </router-link>
            </li>
            <li>
                <router-link to="/userslist" class="nav-link align-middle px-0">
                  <i class="fa fa-users nav-icon"></i>
                  <span class="ms-1 d-none d-sm-inline">Users List</span>
                </router-link>
            </li>
          <li>
                <router-link to="/profile" class="nav-link align-middle px-0">
                    <i class="nav-icon fa fa-user orange"></i>
                     <span class="ms-1 d-none d-sm-inline">Profile</span>
                </router-link>
         </li>
         <li>
                <router-link to="/changepassword" class="nav-link align-middle px-0">
                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Change Password</span>
                </router-link>
         </li>
         <li>
          <a href="{{ url('/logout') }}" class="nav-link align-middle px-0">
            <i class="fa fa-fw fa-sign-out"></i>
            <span class="ms-1 d-none d-sm-inline">Logout</span>
          </a>
        </li>
        </ul>
        @endif
        @if(Auth::user()->usertype === 'basic' || Auth::user()->usertype === 'standard' || Auth::user()->usertype === 'professional')
            <div class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <span class="sidebar-header fs-5 d-none d-sm-inline">
                    <?php print Auth::user()->name; $uId=Auth::user()->id; ?>
                </span>
            </div>
        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
            <li >
                <router-link to="/dashboard" class="nav-link align-middle px-0">
                    <i class="fa fa-fw fa-dashboard"></i>
                    <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                </router-link>
            </li>
            <li>
            @if(Auth::user()->subscriptiondate === NULL)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Company</span>
              </router-link>
            @else
              <router-link :to="'/company'" class="nav-link align-middle px-0">
                <i class="fa fa-building-o" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Company</span>
              </router-link>
              @endif
            </li>
            <li>
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Team</span>
              </router-link>
            @else
              <router-link :to="'/team'" class="nav-link align-middle px-0">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                 <span class="ms-1 d-none d-sm-inline">Team</span>
              </router-link>
              @endif
            </li>
            <li>
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Business Partner</span>
              </router-link>
            @else
              <router-link :to="'/businesspartner'" class="nav-link align-middle px-0">
                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Business Partner</span>
              </router-link>
              @endif
            </li>
            <li >
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
            <i class="fa fa-commenting" aria-hidden="true"></i>
            <span class="ms-1 d-none d-sm-inline">Activity</span>
              </router-link>
            @else
              <router-link :to="'/activity'" class="nav-link align-middle px-0">
              <i class="fa fa-commenting" aria-hidden="true"></i>
              <span class="ms-1 d-none d-sm-inline">Activity</span>
              </router-link>
              @endif
            </li>
            <li>
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Inventory</span>
              </router-link>
            @else
              <router-link :to="'/product'" class="nav-link align-middle px-0">
                <i class="fa fa-hdd-o" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Inventory</span>
              </router-link>
              @endif
            </li>


           <li>
                <a to="#submenu1" data-bs-toggle="collapse" class="nav-link align-middle px-0">
                <i class="fs-4 bi-house"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span>
                </a>
                <ul class="collapse show nav flex-column ms-1" id="submenu1" data-bs-parent="#menu">
                    <li class="w-100">
                      <router-link to="/districtlist" class="nav-link px-0"> 
                      <i class="fa fa-eye"></i> <span class="d-none d-sm-inline">Item</span>
                      </router-link>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Item</span> 2 </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Orders</span></a>
            </li>
            <li>
                <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle ">
                    <i class="fs-4 bi-bootstrap"></i> <span class="ms-1 d-none d-sm-inline">Bootstrap</span></a>
                <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                    <li class="w-100">
                        <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Item</span> 1</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Item</span> 2</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi-grid"></i> <span class="ms-1 d-none d-sm-inline">Products</span> </a>
                    <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                    <li class="w-100">
                        <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Product</span> 1</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Product</span> 2</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Product</span> 3</a>
                    </li>
                    <li>
                        <a href="#" class="nav-link px-0"> <span class="d-none d-sm-inline">Product</span> 4</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" class="nav-link px-0 align-middle">
                    <i class="fs-4 bi-people"></i> <span class="ms-1 d-none d-sm-inline">Customers</span> </a>
            </li>
        </ul>
    @endif
    </div>
</div>