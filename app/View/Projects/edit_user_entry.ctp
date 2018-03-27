<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>


<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>Record User Time</h1>
          <font color="green"><?=$this->Session->flash()?></font>
          <ol class="breadcrumb">
            <li><a href="<?= $this->Html->url('/users/index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Time Sheet Entry</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
          <div class="row"> 
            <!---step 1 starts----->
            <div class="col-xs-12 col-sm-12 col-md-12">
            <?php echo $this->Form->create('Timesheet',array('class'=>'register')); ?>
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
							Project
						</label>
                        <?php
				echo $this->Form->input('project_id', array('type' => 'select','options' => $project_list,'default'=>$this->request->data['Timesheet']['project_id'],'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							User
						</label>
                        <div id="assigned_users">
                        <?php
				echo $this->Form->input('user_id', array('type' => 'select','options'=>$user_list,'default'=>$this->request->data['Timesheet']['user_id'],'onchange'=>'get_user_rates(this.value)','empty'=>'Select user', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
						</div>	
					</div>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Date
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('date', array('type' => 'text','value'=>$this->request->data['Timesheet']['date'],'id'=>'datepick' ,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
                        
					</div>
					
                    <div class="col-md-8 col-sm-6 col-xs-12">
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label>Hourly Rate: <span id="hrs_rate"><?php echo $this->request->data['Timesheet']['normal_rate']; ?></span></label>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label>OT Eligible: <span id="ot_elg"><?php echo ($this->request->data['Timesheet']['ot_eligible']==1)?'YES':'NO'; ?></span></label>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <label>OT Rate: <span id="ot_rate"></span><?php echo ($this->request->data['Timesheet']['ot_eligible']==1)?$this->request->data['Timesheet']['ot_rate']:0; ?></label>
                            
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label id="wkday"><?php echo date('l', strtotime($this->request->data['Timesheet']['date'])); ?></label>
                        <div id="verify_sec" style="float: right;">
                            <?php
                            
                            echo "<p>Data entered by ".$this->Slt->get_user_name($this->request->data['Timesheet']['entered_by'])." on ".date("M-d-Y",strtotime($this->request->data['Timesheet']['created_on']))."</p>";
                            $v_res = $this->Slt->get_verification_detail($this->request->data['Timesheet']['id']);
                            foreach($v_res as $vrow){
                                echo "<p>Data checked by ".$vrow['User']['full_name']." on ".date("M-d-Y",strtotime($vrow['Verifylog']['verified_on']))."</p>";
                            }
                            
                            
                            if($this->request->data['Timesheet']['verified']==1){
                                echo '<span class="text-success"><i class="fa fa-check-circle" title="Verified"></i> Verified</span>';
                            }elseif($this->request->data['Timesheet']['verified']==2){
                                $v_log = $this->Slt->get_verification_log($this->request->data['Timesheet']['id'],$userData['id']);
                                
                                if(!empty($v_log)&&($v_log['Verifylog']['verify_status']==2)&&(strtotime($v_log['Verifylog']['verified_on'])<strtotime("-5 days"))){
                                    echo "<button type=\"button\" onclick=\"verify(1,".$this->request->data['Timesheet']['id'].")\" class=\"btn btn-primary\">Final Verify</button>";
                                }else{
                                    if(((($this->request->data['Timesheet']['entered_by']==$userData['id']))&&(strtotime($this->request->data['Timesheet']['created_on'])<strtotime("-5 days")))||(empty($v_log))){
                                        echo "<button type=\"button\" onclick=\"verify(1,".$this->request->data['Timesheet']['id'].")\" class=\"btn btn-primary\">Final Verify</button>";
                                    }else{
                                        echo '<span class="text-primary"><i class="fa fa-check-circle" title="Partial verified"></i> Partial verified</span>';
                                    }
                                }
                                
                            }else{
                                if(($this->request->data['Timesheet']['entered_by']!=$userData['id'])||(strtotime($this->request->data['Timesheet']['created_on'])<strtotime("-5 days"))){
                                   echo "<button type=\"button\" onclick=\"verify(2,".$this->request->data['Timesheet']['id'].")\" class=\"btn btn-warning\">Verify This</button>"; 
                                }else{
                                   echo '<span class="text-warning"><i class="fa fa-times-circle" title="Not verified"></i> Not verified</span>'; 
                                }
                                
                            }
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
                            
							<h5>Work Details</h5>
                            
                            
						</div>
					</div>
				
				<div class="table-responsive no-padding projects">
				
					<table id="inv_tbl" class="table table-striped ">
                    <tr>
                        <th>Time In</th>
                        <th>Break</th>
                        <th>Time Out</th>
                        <th>Task</th>
                        <th>Sub Task(APN)</th>
                        <th>PLC</th>
                        
                        
                        
                        
                    </tr>
        <?php 
            $j = $k = 0;
            $timedetails = $this->Slt->get_timesheet_detail($this->request->data['Timesheet']['id']);
        if(!empty($timedetails)){
            
            foreach($timedetails as $j=>$rowsheet){
                $row = $rowsheet['TimesheetDetail'];
         ?>
                    <tr>
                        <td><input name="data[times][<?php echo $j; ?>][start_time]" value="<?php echo $row['start_time']; ?>" required="required" class="timein" type="text" id="times<?php echo $j; ?>StartTime"></td>        
                        <td><input name="data[times][<?php echo $j; ?>][break_hours]" value="<?php echo $row['break_hours']; ?>" required="required" class="break_hours" maxlength='5' type="text" id="times<?php echo $j; ?>breakHours"></td> 
                        <td><input name="data[times][<?php echo $j; ?>][end_time]" value="<?php echo $row['end_time']; ?>" required="required" class="timeout" type="text" id="times<?php echo $j; ?>EndTime"></td> 
                        <td><?php
                            echo $this->Form->input("times.$j.task_no", array('type' => 'text','value'=>$row['task_no'],'label'=>false,'div'=>false,'required'=>'required'  ));
                        ?></td>
                        <td><?php
            				echo $this->Form->input("times.$j.apn_no", array('type' => 'text','value'=>$row['apn_no'],'label'=>false,'div'=>false,'required'=>'required','class'=>'apn_no','maxlength'=>'13','placeholder'=>'xxx-x-xxx-xxx'));
            			?></td>
                        
                        <td><?php
                            echo $this->Form->input("times.$j.plc_no", array('type' => 'text','value'=>$row['plc_no'],'label'=>false,'div'=>false,'required'=>'required'  ));
                        ?></td>
                                    
                    </tr>
         
         <?php  
            } 
        }else{
            
        ?>
        <tr>
            <td><input name="data[times][<?php echo $j; ?>][start_time]" required="required" class="timein" type="text" id="times<?php echo $j; ?>StartTime"></td>        
            <td><input name="data[times][<?php echo $j; ?>][break_hours]" required="required" value="0" class="break_hours" maxlength='5' type="text" id="times<?php echo $j; ?>breakHours"></td> 
            <td><input name="data[times][<?php echo $j; ?>][end_time]" required="required" class="timeout" type="text" id="times<?php echo $j; ?>EndTime"></td> 
            <td><?php
                echo $this->Form->input("times.$j.task_no", array('type' => 'text','label'=>false,'div'=>false,'required'=>'required'  ));
            ?></td>
            <td><?php
				echo $this->Form->input("times.$j.apn_no", array('type' => 'text','label'=>false,'div'=>false,'required'=>'required','class'=>'apn_no','maxlength'=>'13','placeholder'=>'xxx-x-xxx-xxx'));
			?></td>
            
            <td><?php
                echo $this->Form->input("times.$j.plc_no", array('type' => 'text','label'=>false,'div'=>false,'required'=>'required'  ));
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
                <div class="buttonSec" style="float: right;">
                <?php
                if($this->request->data['Timesheet']['verified']!=1){ ?>
            		<button type="submit" class="btn btn-success">
            			Update
            		</button>
              <?php } ?>
                    <a href="<?php echo $this->Html->url('/projects/timesheet_delete/'.$this->request->data['Timesheet']['id']); ?>" onclick="return confirm('Are you sure to delete?')" ><button type="button" class="btn btn-danger">
            			Delete
            		</button>
                    </a>
            		<a href="<?php echo ($this->Session->check('current_url'))?$this->Session->read('current_url'):$this->Html->url('/reports/work_time_sheet'); ?>"><button type="button" class="btn btn-info">
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
    var taskNo = $('#times0TaskNo').val();
    var plcNo = $('#times0PlcNo').val();
    if(taskNo == "" || plcNo == ""){
        alert('Please must fill Task and PLC Number.');
        return false;
    }else{
        count=$('.projects table tr').length;
        var data = '<tr>';
        data += '<td><input name="data[times]['+i+'][start_time]" required="required" class="timeins" type="text" id="times'+i+'StartTime"></td>';
        data += '<td><input name="data[times]['+i+'][break_hours]" required="required" value="0" type="text" class="break_hours" maxlength="5" id="times'+i+'break_hours"></td>';
        data += '<td><input name="data[times]['+i+'][end_time]" required="required" class="timeouts" type="text" id="times'+i+'EndTime"></td>';
        data += '<td><input name="data[times]['+i+'][task_no]" required="required" type="text" value="'+taskNo+'" id="times'+i+'taskNo" readonly></td>';
        data += '<td><input name="data[times]['+i+'][apn_no]" required="required" type="text" id="times'+i+'ApnNo" class="apn_no" maxlength="13" placeholder="xxx-x-xxx-xxx"></td>';
        data += '<td><input name="data[times]['+i+'][plc_no]" required="required" type="text" value="'+plcNo+'" id="times'+i+'plcNo" readonly></td>';
        data += '</tr>';
        $('.projects table').append(data);
        i++;
    }
});

$(".delete").on('click', function() {
    var rowCount = $('#inv_tbl tr').length;
    if(rowCount>2){
	   $('.projects table tr:last').remove();
    }
});

function get_user_rates(id){
    $.post("<?php echo $this->Html->url('/projects/ajax_get_users_rate/'); ?>"+id, function(data, status){
            var json = $.parseJSON(data);
            $('#hrs_rate').html(json.normal_rate);
            if(json.ot_eligible=='1'){
                $('#ot_elg').html('YES');
                $('#ot_rate').html(json.ot_rate);
            }else{
                $('#ot_elg').html('NO');
                $('#ot_rate').html('0');
            }
            
        });
}

$(document).ready(function() {
   
   $("#datepick").datepicker({ 
        dateFormat: 'yy-mm-dd',
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.62/jquery.inputmask.bundle.js"></script>
<script type="text/javascript">
$(document).on("keypress", ".apn_no", function() {
    var apnNo = [{ "mask": "###-#-###-###"}];
    $(".apn_no").each(function(){
        $(this).inputmask({ 
            mask: apnNo, 
            greedy: false, 
            definitions: { '#': { validator: "[0-9]", cardinality:1}} 
        });
    });
        
    
});
$(document).ready(function() {
   
    $(".timein").each(function(){
        $(this).inputmask({
            mask: "h:s",
            placeholder: "HH:MM", 
            insertMode: false, 
            showMaskOnHover: false,
            hourFormat: 24
          });
    });
    
    
});
$(document).ready(function() {
   
    $(".timeout").each(function(){
        $(this).inputmask({
            mask: "h:s",
            placeholder: "HH:MM", 
            insertMode: false, 
            showMaskOnHover: false,
            hourFormat: 24
          });
    });
    
    
});
$(document).ready(function() {
    $(".break_hours").each(function(){
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
});

$(document).on("keypress", ".timeins", function() {
   
    $(".timeins").each(function(){
        $(this).inputmask({
            mask: "h:s",
            placeholder: "HH:MM", 
            insertMode: false, 
            showMaskOnHover: false,
            hourFormat: 24
          });
    });
    
    
});
$(document).on("keypress", ".timeouts", function() {
   
    $(".timeouts").each(function(){
        $(this).inputmask({
            mask: "h:s",
            placeholder: "HH:MM", 
            insertMode: false, 
            showMaskOnHover: false,
            hourFormat: 24
          });
    });
    
    
});

function verify(status,id){
    if(confirm("Have you checked data properly?")){
        $(this).prop('disabled', true);
        $.post("<?php echo $this->Html->url('/projects/ajax_data_verify/'); ?>"+id+'/'+status, function(data, status){
            //$('#verify_sec').html(data);
            <?php if($this->Session->check('current_url')){ ?>
                window.location.href = "<?php echo $this->Session->read('current_url'); ?>";
            <?php }else{ ?>
                window.location.href = "<?php echo $this->Html->url('/projects/verify_entry/'); ?>";
            <?php } ?>
        });
    }
    
}

</script>