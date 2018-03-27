<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
<script>
$(function() {
    $('#datepick').daterangepicker();
});
</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script type="text/javascript">
    $(function(){
        $("#ExpenseUserId").multiselect("refresh");
    });
</script>


<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Bulk Expense Entry</h1>
          <font color="green"><?=$this->Session->flash()?></font>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Bulk Expense Entry</li>
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
				echo $this->Form->input('', array('type' => 'select','name'=>'project_id','options' => $project_list,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>	
					</div>
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Date
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('', array('type' => 'text','name'=>'date','id'=>'datepick' ,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
					</div>
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Notes
						</label>
                        <div id="assigned_users">
                        <?php
				echo $this->Form->input('', array('type' => 'textarea','name'=>'notes','value'=>'','label'=>false,'div'=>false ));
			?>
						</div>	
					</div>
					
                    
                    
               </div>
				
				<div class="widgetForm">     
                    
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Expense Type
						</label>
                        <?php
                
				echo $this->Form->input('', array('type' => 'select','name'=>'expensetype_id','options'=>$exp_list,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							User
						</label>
                        <div id="assigned_users">
                        <?php
				echo $this->Form->input('', array('type' => 'select','name'=>'users[]','id'=>'ExpenseUserId','options' => $user_list, 'label'=>false,'div'=>false,'required'=>'required','multiple'  ));
			?>
						</div>	
					</div>
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Amount
						</label>
                        <div id="assigned_users">
                        <?php
				echo $this->Form->input('', array('type' => 'text','name'=>'amount','value'=>0,'label'=>false,'div'=>false,'required'=>'required' ));
			?>
						</div>	
					</div>
					
                    
                
			
			</div>
 
                </div>
                
            
            

            
            <div class="col-md-4 col-sm-12 col-xs-12">    
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

