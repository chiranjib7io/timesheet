<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet" />

<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<!-- Include Required Prerequisites -->

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script>
$(document).ready(function() {
   
   $("#datepick").datepicker({ 
        dateFormat: 'dd-mm-yy',
        onSelect: function(dateText, inst) {
          var seldate = $(this).datepicker('getDate');
            seldate = seldate.toDateString();
            seldate = seldate.split(' ');
            var weekday=new Array();
                weekday['Mon']="Monday";
                weekday['Tue']="Tuesday";
                weekday['Wed']="Wednesday";
                weekday['Thu']="Thursday";
                weekday['Fri']="Friday";
                weekday['Sat']="Saturday";
                weekday['Sun']="Sunday";
            var dayOfWeek = weekday[seldate[0]];
          $('#wkday').html(dayOfWeek);
        }
   });
   
   
   
 });
</script>

<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<div style="color:red; padding:5px; margin-left: 25px;"><?php echo $this->Session->flash(); ?></div>
<div class="container-fluid">
  <h2>Verified List</h2>
<div class="box box-primary" style="float:left; padding: 20px 0;">

	<div class="col-md-12 col-sm-12 col-xs-12 " >
          
        <?php echo $this->Form->create('Timesheet',array('type'=>'get')); ?>
                <div class=" col-md-12 col-sm-12 col-xs-12">
                    <?php
				echo $this->Form->input('project_id', array('type' => 'hidden','value'=>$project_id));
			?>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Filter by Date
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('date', array('type' => 'text','id'=>'datepick','value'=>$date,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
                        <label id="wkday"></label>
					</div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <button type="submit" class="btn btn-success" style="margin-top:40px">
            			Show records
            		</button>
                    <a href="<?php echo $this->Html->url('/projects/verify_entry'); ?>" class="btn btn-danger" style="margin-top:40px">
            			Clear Filter
            		</a>
                    </div>
                </div>
            </form>
                <div class="clearfix"></div>
		
			
			<div class="container">
            
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                
                    <?php
                    if(!empty($res)){
                        foreach($res as $k=>$row){
                    ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="collapse-heading-<?php echo $k+1; ?>">
                                    <a role="button" class="panel-link" data-toggle="collapse" data-parent="#accordion" href="#collapse-category-<?php echo $k+1; ?>" aria-expanded="false" aria-controls="collapse-category-<?php echo $k+1; ?>">
                                        <b><?php echo date("M-d-Y",strtotime($row['Timesheet']['date'])); ?></b>
                                    </a>
                                    &nbsp;|&nbsp;
                                    <a href="<?php echo $this->Html->url('/users/view/'.$row['User']['id']); ?>">
                                        <?php echo $row['User']['fullname']; ?> - <?php echo $row['User']['position']; ?>
                                    </a>
                    <?php
                if($userData['user_type_id']==2 || $userData['user_type_id']==6){ 
                ?>
                                |
                                <a href="<?php echo $this->Html->url('/projects/edit_user_entry/'.$row['Timesheet']['id']); ?>">Verify/Edit Entry</a>
                <?php } ?>  |      
                                <?php
                                if($row['Timesheet']['verified']==1){
                                    echo '<i class="fa fa-check-circle text-success" title="Verified"></i>';
                                }elseif($row['Timesheet']['verified']==2){
                                    echo '<i class="fa fa-check-circle text-primary" title="Partial verified"></i>';
                                }else{
                                    echo '<i class="fa fa-times-circle text-warning" title="Not verified"></i>';
                                }
                                ?> 
                                
                                    <div style="float: right;">
                                        <span>Entered by <?php echo $this->Slt->get_user_name($row['Timesheet']['entered_by']); ?></span> | 
                                     <?php
                                     $v_res = $this->Slt->get_verification_detail($row['Timesheet']['id']);
                                    $arr=array();
                                    foreach($v_res as $vrow){
                                        if(!in_array($vrow['User']['fullname'],$arr))
                                            $arr[] = $vrow['User']['fullname'];
                                    }
                                     ?>   
                                        <span>Verified by <?php echo !empty($arr)?implode(' and ',$arr):'None'; ?></span>
                                    </div>         
                                </div>
                                
                                
                            </div>
                    <?php
                        }
                    }
                    ?>        
                                    
                                    
                                    
                </div>
            </div>
            
		
	</div>
</div>
</div>
