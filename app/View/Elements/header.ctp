<header class="main-header">
        <a href="<?= $this->Html->url('/dashboards') ?>" class="logo"><img src="<?php echo $this->webroot; ?>asset/dist/img/logo.png"></a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
		  
          <div class="navbar-custom-menu">
		  <!-- Back button -->
                <div class="btn-header transparent" style="">
                </div>
                <!-- End Back button -->
			<ul class="nav navbar-nav">
                <li><a href="javascript:void(0);">Cash balance(Rs.<?=$this->slt->get_fund(1)?>)</a></li>
				<?php
					$url = $this->here;
					$expl = explode('/',$url);
					//echo end($expl);
					if(end($expl)!='dashboard' && end($expl)!='lo_dashboard'){
                ?>
            	<li><button class="btn btn-default" onclick="goBack()" style="margin-top:8px;">Go Back</button></li>
				<?php } ?>
                <li><a href="<?= $this->Html->url('/logout') ?>">Logout</a></li>
            </ul>
          </div>
        </nav>
</header>
	  <?php //pr($userData); die; ?>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
<?php if($userData['user_type_id'] != 6) { ?>
			<li><a href="<?= $this->Html->url('/dashboards') ?>"><i class="fa fa-dashboard"></i>Dashboard</a></li>
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-pencil-square-o"></i>
                <span>Data Entry</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
				
                <!--<li><a href="<?= $this->Html->url('/entries/customer_level_data') ?>"><i class="fa fa-circle-o"></i> Customer Level Data</a></li> -->
                <li><a href="<?= $this->Html->url('/entries/collection_entry') ?>"><i class="fa fa-circle-o"></i> Collection Entry</a></li>
                <li><a href="<?= $this->Html->url('/organizations/income_expenditure_entry') ?>"><i class="fa fa-circle-o"></i>Income/Expenditure Entry</a></li>
              </ul>
            </li>
          <!--  
            <li class="treeview">
              <a href="#">
                <i class="fa fa-newspaper-o"></i>
                <span>Summary</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			  <?php if($userData['user_type_id'] == 2 || $userData['user_type_id'] == 4) { ?>
			    
				<?php } ?>
			<li><a href="<?= $this->Html->url('/branches/branch_details/1') ?>"><i class="fa fa-circle-o"></i> Branch Wise</a></li>
             </ul>
            </li>
            <li class="treeview">
              <a href="#">
                <i class="fa fa-list"></i>
                <span>List</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			  <?php if($userData['user_type_id'] == 2 || $userData['user_type_id'] == 4) { ?>
              
                <?php } ?>
                <li><a href="<?= $this->Html->url('/kendra_list') ?>"><i class="fa fa-circle-o"></i> Kendra List</a></li>
			 <li><a href="<?= $this->Html->url('/customer_list') ?>"><i class="fa fa-circle-o"></i> Customer List</a></li>
                <li><a href="<?= $this->Html->url('/loans/loan_list') ?>"><i class="fa fa-circle-o"></i> Loan List</a></li>
              </ul>
            </li>
            -->
			<?php if($userData['user_type_id'] == 2 || 4) { ?>
			<?php
             }
             ?>
           
           <!--
            <li class="treeview">
              <a href="#">
                <i class="fa fa-th"></i> <span>Collection</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
              
              
               <li><a href="<?= $this->Html->url('/kendra_loan_collections') ?>"><i class="fa fa-circle-o"></i> Bulk Loan Collection</a></li>
                <li><a href="<?= $this->Html->url('/amount_collection') ?>"><i class="fa fa-circle-o"></i> Amount Collection</a></li>
                <li><a href="<?= $this->Html->url('/kendra_saving_collections') ?>"><i class="fa fa-circle-o"></i> Saving Collection</a></li>
               
             
              </ul>
            </li>
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-eject"></i> <span>Withdrawal</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= $this->Html->url('/security_deposite_return') ?>"><i class="fa fa-circle-o"></i> Security Deposit</a></li>
                <li><a href="<?= $this->Html->url('/amount_withdraw') ?>"><i class="fa fa-circle-o"></i> Amount Withdraw</a></li>
              </ul>
            </li>
            -->
            <li class="treeview">
              <a href="#">
                <i class="fa fa-gears"></i> <span>Settings</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
			  <?php if($userData['user_type_id'] == 2) { ?>
					<li><a href="<?= $this->Html->url('/organization_edit') ?>"><i class="fa fa-circle-o"></i> Edit An Organization</a></li>
			 
                    <li><a href="<?= $this->Html->url('/organizations/income_expense_name_list') ?>"><i class="fa fa-indent"></i>Income and Expense name manage</a></li>
				<?php } ?>
                <li><a href="<?= $this->Html->url('/save_kendra') ?>"><i class="fa fa-circle-o"></i> Create Group/Kendra </a></li>
                <li><a href="<?= $this->Html->url('/change_password') ?>"><i class="fa fa-circle-o"></i> Change Password</a></li>
              </ul>
            </li>
			
            
<?php }else{
?>
            <li class="treeview active">
              <a href="#">
                <i class="fa fa-file-text-o"></i> <span>Data Entry</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                
                <li><a href="<?= $this->Html->url('/entries/collection_entry') ?>"><i class="fa fa-circle-o"></i> Collection Entry</a></li>
                <li><a href="<?= $this->Html->url('/organizations/income_expenditure_entry') ?>"><i class="fa fa-circle-o"></i>Income/Expenditure Entry</a></li>
              </ul>
            </li>
<?php
    }
?>
<?php if($userData['user_type_id'] == 2 || 4 ) { ?>
            
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-file-text-o"></i> <span>Reports</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="<?= $this->Html->url('/reports/receipt_payment_report') ?>"><i class="fa fa-circle-o"></i>Income & Expenditure Report</a></li>
                <li><a href="<?= $this->Html->url('/reports/profit_loss_report') ?>"><i class="fa fa-circle-o"></i>Profit & Loss Report</a></li>
                <li><a href="<?= $this->Html->url('/reports/all_kendra_report') ?>"><i class="fa fa-circle-o"></i>All kendra Summary</a></li>
            </ul>
            </li>
<?php } ?>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>