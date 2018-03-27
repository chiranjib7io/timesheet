<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet" />

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
   <?php
   if($userData['user_type_id']==2 || $userData['user_type_id']==6){ 
    ?>
				<a href="<?php echo $this->Html->url('/projects/user_entry'); ?>"><button type="button" class="btn btn-danger">
            			Record User Time
            		</button>
                </a>
   <?php } ?>
			</div>
		</div>
	</div>
</header>
<div style="color:red; padding:5px; margin-left: 25px;"><?php echo $this->Session->flash(); ?></div>
<div class="container-fluid">
  <h2>Generate Invoice</h2>
<div class="box box-primary" style="float:left; padding: 20px 0;">

	<div class="col-md-12 col-sm-12 col-xs-12 " >
          
        <?php echo $this->Form->create('Timesheet',array('type'=>'get','url' => '/reports/generate_invoice', )); ?>
                <div class=" col-md-12 col-sm-12 col-xs-12">
                    <?php
				echo $this->Form->input('project_id', array('type' => 'hidden','value'=>$project_id));
			?>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Select Position
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('position', array('type' => 'select','options'=>$position_list,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
                        <label id="wkday"></label>
					</div>
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Select Date
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
            			Show Data
            		</button>
                    
                    </div>
                </div>
            </form>
                <div class="clearfix"></div>
		
			<div class="container">
        <?php
        $total_hours = $client_rate = 0;
        if(!empty($res)){ 
        ?>    
            <table class="table table-striped ">
                    <tr>
                        <th>Date of Service</th>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>APN</th>
                        <th>Time Card</th>
                        <th>Hours</th>
                        <th>Week Hours</th>
                    </tr>
            
            <?php
            foreach($res as $k=>$row){
                $client_rate = $row['Timesheet']['client_rate'];  
                $duration = $break_time = 0;
                $timedetails = $this->Slt->get_timesheet_detail($row['Timesheet']['id']);
                foreach($timedetails as $j=>$rows){
                    $rowsheet = $rows['TimesheetDetail'];
                    echo '<tr>';
                    if($j==0){
                        echo '<td>'.date("M d, Y",strtotime($row['Timesheet']['date'])).'</td>';
                        echo '<td>'.$row['User']['fullname'].'</td>';
                        echo '<td>'.$row['User']['position'].'</td>';
                    }else{
                        echo '<td></td>';
                        echo '<td></td>';
                        echo '<td></td>';
                    }
                    $start_time = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $rowsheet['start_time'])/100;
                    $end_time = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $rowsheet['end_time'])/100;
                    $time_pad = str_pad($start_time, 4, "0", STR_PAD_LEFT).'-'.str_pad($end_time, 4, "0", STR_PAD_LEFT);
                    $break = ($rowsheet['break_hours']>0)?'('.$rowsheet['break_hours'].')':'';
                    
                    echo '<td>'.$rowsheet['apn_no'].'</td>';
                    echo '<td>'.($time_pad.$break).'</td>';
                    echo '<td>'.($rowsheet['decimal_duration']-$rowsheet['break_hours']).'</td>';
                    echo '<td></td>';
                    
                    $duration += $rowsheet['decimal_duration'];
                    $break_time += $rowsheet['break_hours'];
                    echo '</tr>';
                }
                echo '<tr>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td>Total</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '<td>'.($duration-$break_time).'</td>';
                echo '</tr>';
                $total_hours += ($duration-$break_time);
            }
            echo '<tr>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td>'.$total_hours.' hours x</td>';
            echo '<td>$'.$client_rate.' =</td>';
            echo '<td>$'.number_format($total_hours*$client_rate,2).'</td>';
            echo '<td>'.($total_hours).'</td>';
            echo '</tr>';
            ?>
                </table>
                
                <div class="col-md-4 col-sm-6 col-xs-12">
                <a href="<?php echo $this->Html->url('/reports/download_excel_invoice/?project_id='.$project_id.'&position='.$position.'&date='.$date); ?>"><button type="button" class="btn btn-success" style="margin-top:40px">
            			Download Invoice
            		</button></a>
                </div>
        <?php
        }
        ?>
            
			
	</div>
</div>
</div>
