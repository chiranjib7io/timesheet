<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet" />

<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<!-- Include Required Prerequisites -->

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script>
$(document).ready(function() {
   var weekday=new Array(7);
    
    weekday[0]="Monday";
    weekday[1]="Tuesday";
    weekday[2]="Wednesday";
    weekday[3]="Thursday";
    weekday[4]="Friday";
    weekday[5]="Saturday"; 
    weekday[6]="Sunday";
   $("#datepick").datepicker({ 
        dateFormat: 'dd-mm-yy',
        onSelect: function(dateText, inst) {
          var date = $(this).datepicker('getDate');
          var dayOfWeek = weekday[date.getUTCDay()];
          $('#wkday').html(dayOfWeek);
        }
   });
   
   
   
 });
</script>

<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

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
				<a href="<?php echo $this->Html->url('/projects/user_entry'); ?>"><button type="button" class="btn btn-danger">
            			Record User Time
            		</button>
                </a>
			</div>
		</div>
	</div>
</header>
<div style="color:red; padding:5px; margin-left: 25px;"><?php echo $this->Session->flash(); ?></div>
<div class="container-fluid">
  <h2>Work Time Sheet</h2>
<div class="box box-primary" style="float:left; padding: 20px 0;">

	<div class="col-md-12 col-sm-12 col-xs-12 " >
          
        <?php echo $this->Form->create('Timesheet',array('type'=>'get')); ?>
                <div class=" col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Project
						</label>
                        <?php
				echo $this->Form->input('project_id', array('type' => 'select','default'=>$project_id,'options' => $project_list,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Date
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
                            <?php echo $row['User']['fullname']; ?> - <?php echo $row['User']['position']; ?>
                        </a>
                        
                        
                        </div>
                        <div class="panel-collapse " id="collapse-category-<?php echo $k+1; ?>" role="tabpanel" aria-labelledby="collapse-heading-<?php echo $k+1; ?>">
                            <div class="panel-body">
                                <table class="table table-striped ">
                                    <tr>
                                        <th>APN #</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Break hours</th>
                                        <th>Duration (hrs)</th>
                                        <th>OT (hrs)</th>
                                    </tr>
                        <?php
                            $duration = $break_time = $normal_hours = 0;
                            
                            $normal_hours = !empty($row['Timesheet']['normal_hours'])?$row['Timesheet']['normal_hours']:0;
                            foreach($row['TimesheetDetail'] as $rowsheet){ 
                                    $duration += $rowsheet['decimal_duration'];
                                    $break_time += $rowsheet['break_hours'];;
                        ?>
                                    <tr>
                                        <td><?php echo $rowsheet['apn_no']; ?></td>
                                        <td><?php echo $rowsheet['start_time']; ?></td>
                                        <td><?php echo $rowsheet['end_time']; ?></td>
                                        <td><?php echo $rowsheet['break_hours']; ?></td>
                                        <td><?php echo $rowsheet['decimal_duration']-$rowsheet['break_hours']; ?></td>
                                        <td>-</td>
                                    </tr>
                        <?php
                            }
                        ?>
                                    <tr>
                                        <td colspan="4">Total:</td>
                                        <td><?php echo $duration = ($duration-$break_time); ?></td>
                                        <td><?php echo (($duration-$normal_hours)>0)?($duration-$normal_hours):0; ?></td>
                                    </tr>
                                </table>
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
