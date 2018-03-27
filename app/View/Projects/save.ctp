
<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Manage Project</h1>
          <font color="green"><?=$this->Session->flash()?></font>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/users/index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Project</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
          <div class="row"> 
            <!---step 1 starts----->
            <div class="col-xs-12 col-sm-12 col-md-12">
            <?php echo $this->Form->create('Project',array('class'=>'register')); ?>
              <div class="box box-primary" style="float:left; padding-bottom: 20px;">
                <div class=" col-md-12 col-sm-12 col-xs-12 " style="margin-top:20px;">
                      
				<div class="widget-box">
					<div class="widget-title">
						<span class="icon">
							<i class="icon-align-justify">
							</i>
						</span>
						<h5>
							Project Information
						</h5>
					</div>
				</div>
				
				<div class="widgetForm">
				
					<div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Title
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('project_title', array('type' => 'text', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Address
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('address', array('type' => 'text', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Client
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('client', array('type' => 'text', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
					</div>
					
                    
                
			
			</div>
                      
                   
                </div>
            
                <div class="col-md-12 col-sm-12 col-xs-12">
				
					<div class="widget-box">
						<div class="widget-title">
							<span class="icon">
								<i class="icon-align-justify">
								</i>
							</span>
							<h5>
								Users working with this Project
							</h5>
						</div>
					</div>
				
				<div class="table-responsive no-padding projects">
				
					<table id="inv_tbl" class="table table-striped ">
                    <tr>
                        <th>User Name</th>
                        <th>Client Rate</th>
                        <th>Normal Rate</th>
                        <th>OT Rate</th>
                        <th>Normal hrs</th>
                    </tr>
        <?php 
            $j = $k = 0;
        if(!empty($this->request->data['User'])){
            
            foreach($this->request->data['User'] as $j=>$row){
         ?>
                    <tr>
                        <td><?php
				echo $this->Form->input("users.$j.user_id", array('type' => 'select','default'=>$row['UsersProject']['user_id'],'options' => $user_list,'empty'=>'Select user','label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.client_rate", array('type' => 'text','value'=>$row['UsersProject']['client_rate'], 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.normal_rate", array('type' => 'text','value'=>$row['UsersProject']['normal_rate'], 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.ot_rate", array('type' => 'text','value'=>$row['UsersProject']['ot_rate'], 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.normal_hours", array('type' => 'text','value'=>$row['UsersProject']['normal_hours'], 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                    </tr>
         
         <?php  
            } 
        }else{
            
        ?>
        
        
        
                    <tr>
                        <td><?php
				echo $this->Form->input("users.$j.user_id", array('type' => 'select','options' => $user_list,'empty'=>'Select user','label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.client_rate", array('type' => 'text', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.normal_rate", array('type' => 'text', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.ot_rate", array('type' => 'text', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                        <td><?php
				echo $this->Form->input("users.$j.normal_hours", array('type' => 'text', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?></td>
                    </tr>
       <?php             
          }
        
        ?>          
                    
                    
                    </table>
                    <div class="gapBtn">
                    <button type="button" class='delete'>- Delete</button>
                    <button type="button" class='addmore'>+ Add Row</button>
                </div>
                </div>
                
			</div>
            <div class="col-md-12 col-sm-12 col-xs-12">    
                <div class="buttonSec">
            		<button type="submit" class="btn btn-success">
            			Save
            		</button>
            		<a href="<?php echo $this->Html->url('/projects/index'); ?>"><button type="button" class="btn btn-danger">
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
var i= parseInt('<?php echo $j+1; ?>');
$(".addmore").on('click',function(){
	count=$('.projects table tr').length;
    
    var data = "<tr><td><select name='data[users]["+i+"][user_id]' required='required' id='users"+i+"UserId'><option value=''>---  Select User ---</option>";
    <?php
        foreach($user_list as $k=>$sub){
    ?>
           data += "<option value='<?=$k?>'><?=$sub?></option>"; 
    <?php
        }
    ?>
    data += "</select></td>";
    
    data += '<td><input name="data[users]['+i+'][client_rate]" required="required" type="text" id="users'+i+'ClientRate"></td>';
    data += '<td><input name="data[users]['+i+'][normal_rate]" required="required" type="text" id="users'+i+'NormalRate"></td>';
    data += '<td><input name="data[users]['+i+'][ot_rate]" required="required" type="text" id="users'+i+'OtRate"></td>';
    data += '<td><input name="data[users]['+i+'][normal_hours]" required="required" type="text" id="users'+i+'NormalHours"></td></tr>';
    
	$('.projects table').append(data);
	i++;
});

$(".delete").on('click', function() {
    var rowCount = $('#inv_tbl tr').length;
    if(rowCount>2){
	   $('.projects table tr:last').remove();
    }

});



</script>