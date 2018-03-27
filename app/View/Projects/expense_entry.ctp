<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>


<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Record User Expense</h1>
          <font color="green"><?=$this->Session->flash()?></font>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Expense Entry</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
          <div class="row"> 
            <!---step 1 starts----->
            <div class="col-xs-12 col-sm-12 col-md-12">
            <?php echo $this->Form->create('Expense',array('class'=>'register')); ?>
              <div class="box box-primary" style="float:left; padding-bottom: 20px;">
                <div class=" col-md-12 col-sm-12 col-xs-12 " style="margin-top:20px;">
                      
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="icon-align-justify">
							</i>
						</span>
						<h5>
							Expense Information
						</h5>
					</div>
				</div>
				
				<div class="widgetForm">
				
					<div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Project
						</label>
                        <?php
				echo $this->Form->input('Expense.project_id', array('type' => 'select','options' => $project_list,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>	
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							User
						</label>
                        <div id="assigned_users">
                        <?php
				echo $this->Form->input('Expense.user_id', array('type' => 'select','options' => $user_list,'empty'=>'Select user', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
						</div>	
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Date
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('Expense.date', array('type' => 'text','id'=>'datepick' ,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
					</div>
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Expense Type
						</label>
                        <?php
                
				echo $this->Form->input('Expense.expensetype_id', array('type' => 'select','options'=>$exp_list,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Amount
						</label>
                        <div id="assigned_users">
                        <?php
				echo $this->Form->input('Expense.amount', array('type' => 'text','value'=>0,'label'=>false,'div'=>false,'required'=>'required' ));
			?>
						</div>	
					</div>
					
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Notes
						</label>
                        <div id="assigned_users">
                        <?php
				echo $this->Form->input('Expense.notes', array('type' => 'textarea','value'=>'','label'=>false,'div'=>false ));
			?>
						</div>	
					</div>
                
			
			</div>
 
                </div>
                
            
            

            
            <div class="col-md-12 col-sm-12 col-xs-12">    
                <div class="buttonSec">
            		<button type="submit" class="btn btn-success">
            			Save
            		</button>
            		<a href="<?php echo $this->Html->url('/reports/expense_list'); ?>"><button type="button" class="btn btn-danger">
            			Cancel
            		</button>
                    </a>
            	</div>
             </div>
                
                </div>
                </form>
          </div>
                <!-- four end-->
              </div>
           
            <!---step 1 ends-----> 
            
          <!-- /.row --> 
        </section>
        <!-- /.content --> 

<script>


$(document).ready(function() {
    
   $("#datepick").datepicker({ 
        dateFormat: 'dd-mm-yy',
   });
   
   
   
 });
 

</script>