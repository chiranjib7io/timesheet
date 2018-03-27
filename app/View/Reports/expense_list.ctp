<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js">
	</script>

<header class="container-fluid">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<h6>
					Hello, <?php echo $userData['username']; ?>
				</h6>
			</div>
            <div class="col-md-4 col-sm-4 col-xs-12">
            
            </div>
			<div class="col-md-2 col-sm-2 col-xs-12">
   <?php
   if($userData['user_type_id']==2 || $userData['user_type_id']==6){ 
    ?>
				<a href="<?php echo $this->Html->url('/projects/expense_entry'); ?>"><button type="button" class="btn btn-danger">
            			Record Expense
            		</button>
                </a>
   <?php } ?>
			</div>
		</div>
	</div>
</header>
<div style="color:red; padding:5px; margin-left: 25px;"><?php echo $this->Session->flash(); ?></div>
<div class="container-fluid">
  <h2>Expense List</h2>
<div class="box box-primary" style="float:left; padding: 20px 0;">

	<div class="col-md-12 col-sm-12 col-xs-12 " >
          
			<div class="container">
            
                <table class="table table-striped " id="exptable">
                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Employee</th>
                                        <th>Expense Type</th>
                                        <th>Amount ($)</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                    </thead>
                    <tbody>
                        <?php
                            
                            foreach($exp_list as $row){ 
                                    
                        ?>
                                    <tr>
                                        <td><span style="display: none;"><?php echo date("Ymd",strtotime($row['Expense']['date'])); ?></span><?php echo date("d-m-Y",strtotime($row['Expense']['date'])); ?></td>
                                        <td><?php echo $row['User']['fullname']; ?></td>
                                        <td><?php echo $row['Expensetype']['type_name']; ?></td>
                                        <td><?php echo $row['Expense']['amount']; ?></td>
                                        <td><?php echo $row['Expense']['notes']; ?></td>
                                        <td>
                                        <?php
                                        if(!empty($row['Expense']['expentry_id'])){
                                            echo $this->Html->link(    "Edit",   array('controller'=>'projects','action'=>'edit_bulk_expense_entry', $row['Expense']['expentry_id']) ); 
                                            echo ' | ';
                                            echo $this->Html->link(    "delete",   array('controller'=>'projects','action'=>'delete_bulk_expense_entry', $row['Expense']['expentry_id']),['onclick'=>'return confirm("Data will be deleted as bulk.")'] ); 
                                        }else{
                                            echo $this->Html->link(    "Edit",   array('controller'=>'projects','action'=>'edit_expense_entry', $row['Expense']['id']) ); 
                                            echo ' | ';
                                            echo $this->Html->link(    "delete",   array('controller'=>'projects','action'=>'delete_expense_entry', $row['Expense']['id']),['onclick'=>'return confirm("Are you sure to delete?")'] ); 
                                        }
                                        ?>
                                        
                                        </td>
                                    </tr>
                        <?php
                            }
                        ?>
                    </tbody>
                                    
                </table>
            </div>
            
		
	</div>
</div>
</div>
<script>
$(document).ready(function() {
    $('#exptable').DataTable( {
        "order": [[ 0, "desc" ]]
    } );
} );
</script>