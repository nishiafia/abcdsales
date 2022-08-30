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
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Purchase Order</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @else
                  <router-link :to="'/createpurchaseorder'" class="nav-link align-middle px-0">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @else
                  <router-link :to="'/purchaseorder'" class="nav-link align-middle px-0">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline"> Sales Order</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @else
                  <router-link :to="'/newsalesorder'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @else
                  <router-link :to="'/salesorder'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                   <i class="fa fa-money" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Transactions</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-indent" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Incoming</span>
                  </router-link>
                  @else
                  <router-link :to="'/incomingpayment'" class="nav-link align-middle px-0">
                  <i class="fa fa-indent" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Incoming</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-outdent" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Outgoing</span>
                  </router-link>
                  @else
                  <router-link :to="'/outgoingpayment'" class="nav-link align-middle px-0">
                  <i class="fa fa-outdent" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Outgoing</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-bug" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Reports</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Sales</span>
                  </router-link>
                  @else
                  <router-link :to="'/salesreport'" class="nav-link align-middle px-0">
                   <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Sales</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-product-hunt" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Purchase</span>
                  </router-link>
                  @else
                  <router-link :to="'/purchaseorderreport'" class="nav-link align-middle px-0">
                  <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Purchase</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-money" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Inventory</span>
                  </router-link>
                  @else
                  <router-link :to="'/inventoryreport'" class="nav-link align-middle px-0">
                  <i class="fa fa-money" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Inventory</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-money" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Transaction</span>
                  </router-link>
                  @else
                  <router-link :to="'/transactionreport'" class="nav-link align-middle px-0">
                  <i class="fa fa-money" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Transaction</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cloud" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Master Variations</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Variation Label</span>
                  </router-link>
                  @else
                  <router-link :to="'/variationlabel'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Variation Label</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Variations</span>
                  </router-link>
                  @else
                  <router-link :to="'/variation'" class="nav-link align-middle px-0">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Variations</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Settings</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Expense Category</span>
                  </router-link>
                  @else
                  <router-link :to="'/expensecategory'" class="nav-link align-middle px-0">
                  <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Expense Category</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-creative-commons" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Group Code & Title</span>
                  </router-link>
                  @else
                  <router-link :to="'/groupcode'" class="nav-link align-middle px-0">
                  <i class="fa fa-creative-commons" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Group Code & Title</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-gift" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Discount</span>
                  </router-link>
                  @else
                  <router-link :to="'/discount'" class="nav-link align-middle px-0">
                  <i class="fa fa-gift" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Discount</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-usd" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Tax</span>
                  </router-link>
                  @else
                  <router-link :to="'/tax'" class="nav-link align-middle px-0">
                  <i class="fa fa-usd" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Tax</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-truck" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Delivery Agent</span>
                  </router-link>
                  @else
                  <router-link :to="'/deliveryagent'" class="nav-link align-middle px-0">
                  <i class="fa fa-truck" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Delivery Agent</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
            @if(Auth::user()->subscriptiondate === NULL)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
            <i class="nav-icon fa fa-user orange"></i>
                <span class="ms-1 d-none d-sm-inline">Profile</span>
              </router-link>
            @else
              <router-link :to="'/profile'" class="nav-link align-middle px-0">
              <i class="nav-icon fa fa-user orange"></i>
                <span class="ms-1 d-none d-sm-inline">Profile</span>
              </router-link>
              @endif
            </li>
            <li>
            @if(Auth::user()->subscriptiondate === NULL)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Change Password</span>
              </router-link>
            @else
              <router-link :to="'/changepassword'" class="nav-link align-middle px-0">
              <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Change Password</span>
              </router-link>
              @endif
            </li>
            <li>
                <a href="{{ url('/logout') }}" class="nav-link align-middle px-0">
                <i class="fa fa-fw fa-sign-out"></i>
                <span class="ms-1 d-none d-sm-inline">Logout</span>
                </a>
        </li>
        </ul>
    @endif
        @if(Auth::user()->usertype === 'team')
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
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Purchase Order</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @else
                  <router-link :to="'/createpurchaseorder'" class="nav-link align-middle px-0">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @else
                  <router-link :to="'/purchaseorder'" class="nav-link align-middle px-0">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline"> Sales Order</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @else
                  <router-link :to="'/newsalesorder'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">New Order</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @else
                  <router-link :to="'/salesorder'" class="nav-link align-middle px-0">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Order List</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                   <i class="fa fa-money" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Transactions</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-indent" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Incoming</span>
                  </router-link>
                  @else
                  <router-link :to="'/incomingpayment'" class="nav-link align-middle px-0">
                  <i class="fa fa-indent" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Incoming</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-outdent" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Outgoing</span>
                  </router-link>
                  @else
                  <router-link :to="'/outgoingpayment'" class="nav-link align-middle px-0">
                  <i class="fa fa-outdent" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Outgoing</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cloud" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Master Variations</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Variation Label</span>
                  </router-link>
                  @else
                  <router-link :to="'/variationlabel'" class="nav-link align-middle px-0">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Variation Label</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Variations</span>
                  </router-link>
                  @else
                  <router-link :to="'/variation'" class="nav-link align-middle px-0">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Variations</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
                <a href="#" class="dropdown-toggle nav-link align-middle px-0" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Settings</span>
                </a>
                <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Expense Category</span>
                  </router-link>
                  @else
                  <router-link :to="'/expensecategory'" class="nav-link align-middle px-0">
                  <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Expense Category</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-creative-commons" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Group Code & Title</span>
                  </router-link>
                  @else
                  <router-link :to="'/groupcode'" class="nav-link align-middle px-0">
                  <i class="fa fa-creative-commons" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Group Code & Title</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-gift" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Discount</span>
                  </router-link>
                  @else
                  <router-link :to="'/discount'" class="nav-link align-middle px-0">
                  <i class="fa fa-gift" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Discount</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-usd" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Tax</span>
                  </router-link>
                  @else
                  <router-link :to="'/tax'" class="nav-link align-middle px-0">
                  <i class="fa fa-usd" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Tax</span>
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
                  <i class="fa fa-truck" aria-hidden="true"></i>
                  <span class="ms-1 d-none d-sm-inline">Delivery Agent</span>
                  </router-link>
                  @else
                  <router-link :to="'/deliveryagent'" class="nav-link align-middle px-0">
                  <i class="fa fa-truck" aria-hidden="true"></i>
                    <span class="ms-1 d-none d-sm-inline">Delivery Agent</span>
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li>
            @if(Auth::user()->subscriptiondate === NULL)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
            <i class="nav-icon fa fa-user orange"></i>
                <span class="ms-1 d-none d-sm-inline">Profile</span>
              </router-link>
            @else
              <router-link :to="'/profile'" class="nav-link align-middle px-0">
              <i class="nav-icon fa fa-user orange"></i>
                <span class="ms-1 d-none d-sm-inline">Profile</span>
              </router-link>
              @endif
            </li>
            <li>
            @if(Auth::user()->subscriptiondate === NULL)
            <router-link :to="'/dashboard'" class="nav-link align-middle px-0">
            <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Change Password</span>
              </router-link>
            @else
              <router-link :to="'/changepassword'" class="nav-link align-middle px-0">
              <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                <span class="ms-1 d-none d-sm-inline">Change Password</span>
              </router-link>
              @endif
            </li>
            <li>
                <a href="{{ url('/logout') }}" class="nav-link align-middle px-0">
                <i class="fa fa-fw fa-sign-out"></i>
                <span class="ms-1 d-none d-sm-inline">Logout</span>
                </a>
        </li>
        </ul>
    @endif
    </div>
</div>
