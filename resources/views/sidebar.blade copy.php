<div class="sidebar">
    @if(Auth::user()->usertype === 'superadmin')
    <div class="sidebar-header">
      <h3><?php 
        //print_r(Auth::user()->remember_token);
        print Auth::user()->name; $uId=Auth::user()->id; //echo session('_token'); ?></h3>
    </div>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
              <router-link to="/dashboard" class="nav-link">
                <i class="fa fa-fw fa-dashboard"></i>
                  Dashboard
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/districtlist" class="nav-link">
                <i class="fa fa-eye" aria-hidden="true"></i>
                  Manage District
              </router-link>
          </li>
            <li class="nav-item">
              <router-link to="/thanalist" class="nav-link">
                <i class="fa fa-eye" aria-hidden="true"></i>
                  Manage Thana
              </router-link>
          </li>
            <li class="nav-item">
                  <router-link to="/businesscategory" class="nav-link">
                    <i class="fa fa-crosshairs" aria-hidden="true"></i>
                      Business Category
                  </router-link>
            </li>
            <li class="nav-item">
                <router-link to="/newsignup" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                 New Sign-up
                </router-link>
            </li>
            <li class="nav-item">
                <router-link to="/userslist" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                 Users List
                </router-link>
            </li>
          <li class="nav-item">
                <router-link to="/profile" class="nav-link">
                    <i class="nav-icon fa fa-user orange"></i>
                        Profile
                </router-link>
         </li>
         <li class="nav-item">
                <router-link to="/changepassword" class="nav-link">
                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                        Change Password
                </router-link>
         </li>
         <li class="nav-item has-treeview">
          <a href="{{ url('/logout') }}" class="nav-link">
            <i class="fa fa-fw fa-sign-out"></i>
            Logout
          </a>
        </li>

        </ul>
      </nav>
      @endif
      @if(Auth::user()->usertype === 'basic' || Auth::user()->usertype === 'standard' || Auth::user()->usertype === 'professional')
      <div class="sidebar-header">
        <h3><?php
          //print_r(Auth::user()->remember_token);
         // print Auth::user()->subscriptiondate;
          print Auth::user()->name; $uId=Auth::user()->id; //echo session('_token'); ?></h3>
      </div>
      <nav class="mt-2">
        <ul class="nav flex-column" data-widget="tree" role="menu" data-accordion="true">

            <li class="nav-item">
              <router-link :to="'/dashboard'" class="nav-link">
                <i class="fa fa-fw fa-dashboard"></i>
                  Dashboard
              </router-link>
            </li>
            <li class="nav-item">
            @if(Auth::user()->subscriptiondate === NULL)
            <router-link :to="'/dashboard'" class="nav-link">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Company
              </router-link>
            @else
              <router-link :to="'/company'" class="nav-link">
                <i class="fa fa-building-o" aria-hidden="true"></i>
                  Company
              </router-link>
              @endif
            </li>
            <li class="nav-item">
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Team
              </router-link>
            @else
              <router-link :to="'/team'" class="nav-link">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Team
              </router-link>
              @endif
            </li>
            <li class="nav-item">
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                Business Partner
              </router-link>
            @else
              <router-link :to="'/businesspartner'" class="nav-link">
                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                  Business Partner
              </router-link>
              @endif
            </li>
            <li class="nav-item">
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link">
            <i class="fa fa-commenting" aria-hidden="true"></i>
                Activity
              </router-link>
            @else
              <router-link :to="'/activity'" class="nav-link">
              <i class="fa fa-commenting" aria-hidden="true"></i>
                  Activity
              </router-link>
              @endif
            </li>
            <li class="nav-item">
            @if(Auth::user()->companyid === 0)
            <router-link :to="'/dashboard'" class="nav-link">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                Inventory
              </router-link>
            @else
              <router-link :to="'/product'" class="nav-link">
                <i class="fa fa-hdd-o" aria-hidden="true"></i>
                 Inventory
              </router-link>
              @endif
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-product-hunt" aria-hidden="true"></i>
                Purchase Order
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" >
                <li class="dropdown-item nav-item">
                  @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                    New Order
                  </router-link>
                  @else
                  <router-link :to="'/createpurchaseorder'" class="nav-link">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                     New Order
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Order List
                  </router-link>
                  @else
                  <router-link :to="'/purchaseorder'" class="nav-link">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                      Order List
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                Sales Order
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  New Order
                  </router-link>
                  @else
                  <router-link :to="'/newsalesorder'" class="nav-link">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                     New Order
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Order List
                  </router-link>
                  @else
                  <router-link :to="'/salesorder'" class="nav-link">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                      Order List
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-money" aria-hidden="true"></i>
                Transactions
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Incoming
                  </router-link>
                  @else
                  <router-link :to="'/incomingpayment'" class="nav-link">
                  <i class="fa fa-indent" aria-hidden="true"></i>
                     Incoming
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Outgoing
                  </router-link>
                  @else
                  <router-link :to="'/outgoingpayment'" class="nav-link">
                  <i class="fa fa-outdent" aria-hidden="true"></i>
                      Outgoing
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-bug" aria-hidden="true"></i>
                Reports
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                  Sales
                  </router-link>
                  @else
                  <router-link :to="'/salesreport'" class="nav-link">
                  <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                     Sales
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-product-hunt" aria-hidden="true"></i>
                  Purchase
                  </router-link>
                  @else
                  <router-link :to="'/purchaseorderreport'" class="nav-link">
                  <i class="fa fa-product-hunt" aria-hidden="true"></i>
                  Purchase
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Inventory
                  </router-link>
                  @else
                  <router-link :to="'/inventoryreport'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Inventory
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-money" aria-hidden="true"></i>
                  Transaction
                  </router-link>
                  @else
                  <router-link :to="'/transactionreport'" class="nav-link">
                  <i class="fa fa-money" aria-hidden="true"></i>
                  Transaction
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cloud" aria-hidden="true"></i>
                Master Variations
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Variation Label
                  </router-link>
                  @else
                  <router-link :to="'/variationlabel'" class="nav-link">
                  <i class="fa fa-cloud" aria-hidden="true"></i>
                     Variation Label
                  </router-link>
                  @endif
                </li>
                <li class="dropdown-item nav-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Variations
                  </router-link>
                  @else
                  <router-link :to="'/variation'" class="nav-link">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                     Variations
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink1" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog" aria-hidden="true"></i>
                 Settings
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink1" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <!--li class="nav-item dropdown-item">
                  <router-link to="/productcolor" class="nav-link">
                    <i class="fa fa-cloud" aria-hidden="true"></i>
                      Manage Color
                  </router-link>
              </li>
              <li class="nav-item dropdown-item">
                  <router-link to="/productsize" class="nav-link">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                      Manage Size
                  </router-link>
              </li-->
                <li class="nav-item dropdown-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Expense Category
                  </router-link>
                  @else
                  <router-link :to="'/expensecategory'" class="nav-link">
                    <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                     Expense Category
                  </router-link>
                  @endif
                </li>
                <li class="nav-item dropdown-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                    Group Code & Title
                  </router-link>
                  @else
                  <router-link :to="'/groupcode'" class="nav-link">
                    <i class="fa fa-creative-commons" aria-hidden="true"></i>
                      Group Code & Title
                  </router-link>
                  @endif
                </li>
                <li class="nav-item dropdown-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                     Discount
                  </router-link>
                  @else
                  <router-link :to="'/discount'" class="nav-link">
                    <i class="fa fa-gift" aria-hidden="true"></i>
                     Discount
                  </router-link>
                  @endif
                </li>
                <li class="nav-item dropdown-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                     Tax
                  </router-link>
                  @else
                  <router-link :to="'/tax'" class="nav-link">
                    <i class="fa fa-usd" aria-hidden="true"></i>
                     Tax
                  </router-link>
                  @endif
                </li>
                <li class="nav-item dropdown-item">
                @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                    Delivery Agent
                  </router-link>
                  @else
                  <router-link :to="'/deliveryagent'" class="nav-link">
                    <i class="fa fa-truck" aria-hidden="true"></i>
                    Delivery Agent
                  </router-link>
                  @endif
                </li>
              </ul>
            </li>
          <li class="nav-item">
          @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Profile
                  </router-link>
                  @else
                <router-link :to="'/profile'" class="nav-link">
                    <i class="nav-icon fa fa-user orange"></i>
                        Profile
                </router-link>
                @endif
         </li>
         <li class="nav-item">
          @if(Auth::user()->companyid === 0)
                  <router-link :to="'/dashboard'" class="nav-link">
                  <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                  Change Password
                  </router-link>
                  @else
                <router-link :to="'/changepassword'" class="nav-link">
                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                    Change Password
                </router-link>
                @endif
         </li>
         <li class="nav-item has-treeview">
          <a href="{{ url('/logout') }}" class="nav-link">
            <i class="fa fa-fw fa-sign-out"></i>
            Logout
          </a>
        </li>

        </ul>
        <div class="dropdown">
        </div>
      </nav>

      @endif

      @if(Auth::user()->usertype === 'team')
      <div class="sidebar-header">
        <h3><?php 
          //print_r(Auth::user()->remember_token);
          print Auth::user()->name; $uId=Auth::user()->id; //echo session('_token'); ?></h3>
      </div>
      <nav class="mt-2">
        <ul class="nav flex-column" data-widget="tree" role="menu" data-accordion="true">

            <li class="nav-item">
              <router-link :to="'/dashboard'" class="nav-link">
                <i class="fa fa-fw fa-dashboard"></i>
                  Dashboard
              </router-link>
            </li>
            <li class="nav-item">
              <router-link :to="'/businesspartner'" class="nav-link">
                <i class="fa fa-handshake-o" aria-hidden="true"></i>
                  Business Partner
              </router-link>
            </li>
            <li class="nav-item">
              <router-link :to="'/product'" class="nav-link">
                <i class="fa fa-hdd-o" aria-hidden="true"></i>
                 Inventory
              </router-link>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle  nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-product-hunt" aria-hidden="true"></i>
                Purchase Order
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                  <router-link :to="'/createpurchaseorder'" class="nav-link">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                     New Order
                  </router-link>
                </li>
                <li class="dropdown-item nav-item">
                  <router-link :to="'/purchaseorder'" class="nav-link">
                    <i class="fa fa-product-hunt" aria-hidden="true"></i>
                     Order List
                  </router-link>
                </li>
              </ul>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                Sales Order
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                  <router-link :to="'/newsalesorder'" class="nav-link">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                     New Order
                  </router-link>
                </li>
                <li class="dropdown-item nav-item">
                  <router-link :to="'/salesorder'" class="nav-link">
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                      Order List
                  </router-link>
                </li>
              </ul>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-money" aria-hidden="true"></i>
                Transactions
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                  <router-link :to="'/incomingpayment'" class="nav-link">
                  <i class="fa fa-indent" aria-hidden="true"></i>
                     Incoming
                  </router-link>
                </li>
                <li class="dropdown-item nav-item">
                  <router-link :to="'/outgoingpayment'" class="nav-link">
                  <i class="fa fa-outdent" aria-hidden="true"></i>
                      Outgoing
                  </router-link>
                </li>
              </ul>
            </li>
            <li class=" nav-item dropdown">
              <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" id="dropdownMenuLink" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-cloud" aria-hidden="true"></i>
                Master Variations
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <li class="dropdown-item nav-item">
                  <router-link :to="'/variationlabel'" class="nav-link">
                  <i class="fa fa-cloud" aria-hidden="true"></i>
                     Variation Label
                  </router-link>
                </li>
                <li class="dropdown-item nav-item">
                  <router-link :to="'/variation'" class="nav-link">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                     Variations
                  </router-link>
                </li>
              </ul>
            </li>
            <li class="nav-item dropdown">
              <a href="#" class="dropdown-toggle  nav-link" data-toggle="dropdown" id="dropdownMenuLink1" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-cog" aria-hidden="true"></i>
                Settings
              </a>
              <ul class="dropdown-menu " aria-labelledby="dropdownMenuLink1" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 60px, 0px); top: 0px; left: 0px; will-change: transform;" >
                <!--li class="nav-item dropdown-item">
                  <router-link to="/productcolor" class="nav-link">
                    <i class="fa fa-cloud" aria-hidden="true"></i>
                      Manage Color
                  </router-link>
              </li>
              <li class="nav-item dropdown-item">
                  <router-link to="/productsize" class="nav-link">
                    <i class="fa fa-eye" aria-hidden="true"></i>
                      Manage Size
                  </router-link>
              </li-->
                <li class="nav-item dropdown-item">
                  <router-link :to="'/expensecategory'" class="nav-link">
                    <i class="fa fa-sort-amount-asc" aria-hidden="true"></i>
                     Expense Category
                  </router-link>
                </li>
                <li class="nav-item dropdown-item">
                  <router-link :to="'/groupcode'" class="nav-link">
                    <i class="fa fa-creative-commons" aria-hidden="true"></i>
                      Group Code & Title
                  </router-link>
                </li>
                <li class="nav-item dropdown-item">
                  <router-link :to="'/discount'" class="nav-link">
                    <i class="fa fa-gift" aria-hidden="true"></i>
                     Discount
                  </router-link>
                </li>
                <li class="nav-item dropdown-item">
                  <router-link :to="'/tax'" class="nav-link">
                    <i class="fa fa-usd" aria-hidden="true"></i>
                     Tax
                  </router-link>
                </li>
                <li class="nav-item dropdown-item">
                  <router-link :to="'/deliveryagent'" class="nav-link">
                    <i class="fa fa-truck" aria-hidden="true"></i>
                    Delivery Agent
                  </router-link>
                </li>
              </ul>
            </li>
          <li class="nav-item">
                <router-link :to="'/profile'" class="nav-link">
                    <i class="nav-icon fa fa-user orange"></i>
                        Profile
                </router-link>
         </li>
         <li class="nav-item">
                <router-link :to="'/changepassword'" class="nav-link">
                <i class="fa fa-unlock-alt" aria-hidden="true"></i>
                        Change Password
                </router-link>
         </li>
         <li class="nav-item has-treeview">
          <a href="{{ url('/logout') }}" class="nav-link">
            <i class="fa fa-fw fa-sign-out"></i>
            Logout
          </a>
        </li>
        </ul>
        <div class="dropdown">
        </div>
      </nav>

      @endif
      @if(Auth::user()->usertype === 'business partner' )
      <script type="text/javascript">
        window.location = "{{ url('/logout') }}";//here double curly bracket
    </script>
      @endif
    </div>