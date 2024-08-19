<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="?url=home" class="brand-link">
    <img src="assets/img/icon.png"
       alt="Logo"
       class="brand-image img-circle elevation-3"
       style="opacity: .8">
     <span class="brand-text font-weight-light">
      <?php echo ucfirst(Session::get('firstname'))." ".ucfirst(Session::get('lastname')); ?>
     </span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
         <li class="nav-item md-0 py-0">
          <a href="?url=home" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'dashboard'){echo "active";} ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>Drug Module <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="?url=home/view_drugs" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'view_drugs'){echo "active";} ?>">
                &nbsp;
                <i class="nav-icon fas fa-th"></i>
                <p>View Drugs</p>
              </a>
            </li>
            <?php if(Session::get('isAdmin') == true): ?>
              <li class="nav-item">
                <a href="?url=home/add_drug" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'add_drug'){echo "active";} ?>">
                  &nbsp;
                  <i class="nav-icon fas fa-plus-square"></i>
                  <p>Add Drug</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?url=dataimport" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'import_excel_sheet'){echo "active";} ?>">
                  &nbsp;
                  <i class="nav-icon fas fa-upload"></i>
                  <p>Import Excel Sheet</p>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </li>
        <li class="nav-header">STOCK MANAGEMENT</li>
        <li class="nav-item">
          <a href="?url=home/add_stock" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'add_stock'){echo "active";} ?>">
            <i class="nav-icon fas fa-cart-plus"></i>
            <p>Add Stock (Drugs)</p>
          </a>
        </li>
        <?php if(Session::get('isAdmin') == true): ?>
          <li class="nav-item">
            <a href="?url=home/list_purchases" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'purchases_records'){echo "active";} ?>">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                Stock Records
                <span id="notify-purchase" class="badge badge-info right"></span>
                <span id="notify-trash" class="badge badge-danger right"></span>
              </p>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-header">POINT OF SALES (POS)</li>
        <li class="nav-item">
          <a href="?url=home/pos" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'pos'){echo "active";} ?>">
            <i class="nav-icon fas fa-book"></i>
            <p>POS</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?url=home/sales" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'sales'){echo "active";} ?>">
            <i class="nav-icon fas fa-receipt"></i>
            <p>
              Sales
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="?url=home/list_expenses" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'expenses'){echo "active";} ?>">
            <i class="nav-icon fas fa-receipt"></i>
            <p>Expenses</p>
          </a>
        </li>
        <?php if(Session::get('isAdmin') == true): ?>
          <li class="nav-header">REPORTS</li>
          <li class="nav-item">
            <a href="?url=home/sales_report" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'sales_report'){echo "active";} ?>">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Sales Report
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?url=home/expenses_report" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'expenses_report'){echo "active";} ?>">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Expenses Report</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="?url=home/profitloss_report" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'profit_loss_report'){echo "active";} ?>">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Profit/Loss Report</p>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-header">SETTINGS</li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>My Account <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="?url=home/change_user_details" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'change_user_details'){echo "active";} ?>">
                &nbsp;
                <i class="nav-icon fas fa-file-alt mr-1"></i>
                <p>Account Details</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="?url=home/change_password" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'change_password'){echo "active";} ?>">
                &nbsp;
                <i class="nav-icon fas fa-lock"></i>
                <p>Change Password</p>
              </a>
            </li>
          </ul>
        </li>
        <?php if(Session::get('isAdmin') == true): ?>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>User Accounts <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="?url=home/addUserAccount" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'add_user_account'){echo "active";} ?>">
                  &nbsp;
                  <i class="nav-icon fas fa-edit"></i>
                  <p>Add New User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="?url=home/userAccounts" class="nav-link <?php if(isset($data['page_include']) && $data['page_include'] == 'user_accounts'){echo "active";} ?>">
                  &nbsp;
                  <i class="nav-icon fas fa-file-alt mr-1"></i>
                  <p>View User Accounts</p>
                </a>
              </li>
            </ul>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a href="?url=home/runLogOut" class="nav-link">
            <i class="nav-icon fas fa-key"></i>
            <p>
              Logout
            </p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
