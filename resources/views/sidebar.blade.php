<div class="sidebar">
    <div class="sidebar-header">
      <h3><?php print Auth::user()->name; ?>( <?php print Auth::user()->usertype; ?> )</h3>
      
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
              <router-link to="/groupcode" class="nav-link">
                <i class="fa fa-creative-commons" aria-hidden="true"></i>
                  Manage Group Code & Title
              </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/branch" class="nav-link">
                <i class="fa fa-fw fa-arrows"></i>
                  Manage Branch
              </router-link>
            </li>
           
           
            <li class="nav-item">
                <router-link to="/productcolor" class="nav-link">
                  <i class="fa fa-cloud" aria-hidden="true"></i>
                    Manage Color
                </router-link>
            </li>
            <li class="nav-item">
                <router-link to="/productsize" class="nav-link">
                  <i class="fa fa-eye" aria-hidden="true"></i>
                    Manage Size
                </router-link>
            </li>
            <li class="nav-item">
              <router-link to="/customer" class="nav-link">
                <i class="fa fa-user-plus" aria-hidden="true"></i>
                  Manage Customer
              </router-link>
          </li>
            <li class="nav-item">
                  <router-link to="/businesscategory" class="nav-link">
                    <i class="fa fa-crosshairs" aria-hidden="true"></i>
                      Manage Business Category
                  </router-link>
            </li>
            <li class="nav-item">
                <router-link to="/users" class="nav-link">
                  <i class="fa fa-users nav-icon"></i>
                 Users
                </router-link>
            </li>
    
    
          <li class="nav-item">
                <router-link to="/profile" class="nav-link">
                    <i class="nav-icon fa fa-user orange"></i>
                        Profile
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
    </div>
