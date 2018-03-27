<nav class="navbar navbar-default fixedElement">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
			<span class="sr-only">
				Toggle navigation
			</span>
			<span class="icon-bar">
			</span>
			<span class="icon-bar">
			</span>
			<span class="icon-bar">
			</span>
		</button>
		<a class="navbar-brand" href="<?= $this->Html->url('/') ?>"><img src="<?php echo $this->webroot; ?>front/images/logo.jpg" class="img-responsive"></a>
	</div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav navbar-right">
			<li>
				<a href="<?= $this->Html->url('/') ?>">Dashboard</a>
			</li>
   <?php
   if($userData['user_type_id']==2 || $userData['user_type_id']==6){ 
    ?>
			<li role="presentation" class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
				Data Entry<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo $this->Html->url('/projects/user_entry') ?>">Record User Time</a>
					</li>
                    <li>
						<a href="<?php echo $this->Html->url('/projects/expense_entry') ?>">Record User Expense</a>
					</li>
                    <li>
						<a href="<?php echo $this->Html->url('/projects/bulk_expense_entry') ?>">Bulk Expense Entry</a>
					</li>
					
				</ul>
			</li>
			
            <li role="presentation" class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
				Settings<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?= $this->Html->url('/users/user_list') ?>">Users list</a>
					</li>
                    <li>
						<a href="<?= $this->Html->url('/projects/index') ?>">Project list</a>
					</li>
                    <li>
						<a href="<?= $this->Html->url('/projects/verify_entry') ?>">Verify Entry</a>
					</li>
				</ul>
			</li>
  <?php
  }
  ?>
            <li role="presentation" class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="true">
				Reports<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo $this->Html->url('/reports/work_time_sheet') ?>">Work Time Sheet</a>
					</li>
                    <li>
						<a href="<?php echo $this->Html->url('/reports/expense_list') ?>">Expense List</a>
					</li>
                    
                    <li>
						<a href="<?php echo $this->Html->url('/reports/time_rate_report') ?>">Cost & Earned Report</a>
					</li>
                    <li>
						<a href="<?php echo $this->Html->url('/reports/generate_invoice') ?>">Generate Invoice(xls)</a>
					</li>
                    
				</ul>
			</li>
			<li>
				<a href="<?= $this->Html->url('/users/logout') ?>">Logout</a>
			</li>
		</ul>
	</div>
</nav>