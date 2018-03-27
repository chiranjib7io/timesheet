<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
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
function print_this(){
    w=window.open();
    w.document.write($('#q_table').html());
    w.print();
    w.close();
}
</script>

<div style="color:red; padding:5px; margin-left: 25px;"><?php echo $this->Session->flash(); ?></div>
<div class="container-fluid">
	<div class="col-md-12 col-sm-12 col-xs-12">
        <?php echo $this->Form->create('Timesheet',array('type'=>'get')); ?>
                <div class=" col-md-12 col-sm-12 col-xs-12"  style="padding-left:0;">
                    <?php
				echo $this->Form->input('project_id', array('type' => 'hidden','value'=>$project_id));
			?>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12" style="padding-left:0;">
						<label>
							Please Select Date
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('date', array('type' => 'text','id'=>'datepick','value'=>$date,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
                        
					</div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <button type="submit" class="btn btn-success" style="margin-top:40px">
            			Show report
            		</button>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
    
    
        <button style="font-size:24px" onclick="print_this()"><i class="fa fa-print"></i></button>
		<div class="tableSec table-responsive" id="q_table">
			<h4>
				<?php echo "Cost & Earned Report"; ?>
                <?php if(!empty($start_date) && !empty($end_date)){
                        echo " from ".date("M/d/Y",strtotime($start_date))." To ".date("M/d/Y",strtotime($end_date));
                    }
                ?>
			</h4>
			<table class="table table1 table-hover">
				<thead>
					<tr>
						<th colspan="4" class="fstbg">
							General
						</th>
						<th colspan="3" class="secbg">
							Hours Details
						</th>
						<th colspan="6" class="thirBg">
							Charges Details
						</th>
                        <th class="fstbg">
							Profit/Loss
						</th>
						
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="fstbg text-left">
							Employee Name
						</td>
						<td class="fstbg text-left">
							Client Rate($)
						</td>
                        <td class="fstbg text-left">
							Normal Rate($)
						</td>
                        <td class="fstbg text-left">
							Overtime Rate($)
						</td>
                        
						
						<td class="secbg">
							Total Worked(hrs)
						</td>
						<td class="secbg">
							Normal Hours
						</td>
                        <td class="secbg">
							Overtime(hrs)
						</td>
						
                        
						<td class="thirBg">
							Normal Charge($)
						</td>
						<td class="thirBg">
							Overtime Charge($)
						</td>
                        <td class="thirBg">
							Double Rate Charge($)
						</td>
                        <td class="thirBg">
							Total Charge($)
						</td>
                        <td class="thirBg">
							Total Expense($)
						</td>
                        <td class="thirBg">
							Earned($)
						</td>
                        
                        <td class="fstbg text-left">
							Amount($)
						</td>
						
					</tr>
  <?php  
    $body = '';
    $date = $this->set('date', date("Y-m-d",strtotime($date)));
    //pr($res_data);die;
if(!empty($res_data)){
    
    $total_work = $total_normal_work = $total_ot_work = $total_earned = $total_normal_chrg = $total_ot_charge = $total_expense = $total_extra_charge = $total_profit = 0;
    foreach($res_data as $k => $row)
	{     
	   
    	$client_rate = number_format(!empty($row['Timesheet']['client_rate'])?$row['Timesheet']['client_rate']:0,2);
        $normal_rate = number_format(!empty($row['Timesheet']['normal_rate'])?$row['Timesheet']['normal_rate']:0,2);
        $ot_rate = number_format(($row['Timesheet']['ot_eligible']==1)?$row['Timesheet']['ot_rate']:0,2);
        $normal_hrs = !empty($row['Timesheet']['normal_hours'])?$row['Timesheet']['normal_hours']:0;
    
          $overtime_hrs = $normal_chrg = $ot_chrg = $earned = $expense = $extra_charge = $profit = 0;
          
          $expense = $this->Slt->get_expense_for_range($row['User']['id'],$start_date,$end_date);
          
           $arr = $this->Slt->calculate_hours_and_cost_for_range($row['User']['id'],$start_date,$end_date);
           $total_hours = $arr['total_hours']; 
           $earned = $arr['total_earned']; 
           $overtime_hrs = $arr['total_ot_hours']; 
           $ot_chrg = $arr['total_ot_charge']; 
           $normal_chrg = $arr['total_normal_charge']; 
           $extra_charge = $arr['total_extra_charge'];
           $profit = $earned-($normal_chrg+$ot_chrg+$expense+$extra_charge);
           $bgcol = ($profit>0)?'#d2f7be':'mistyrose'; 
              //pr($row);die;         
           $body .= '<tr>
						<td class="text-left">
                            <a href="'.$this->Html->url('/users/view/'.$row['User']['id']).'">
							'.$row['User']['fullname'].'
                            </a>
						</td>
						<td class="text-left">
							'.$client_rate.'
						</td>
						<td>
							'.$normal_rate.'
						</td>
						<td>
							'.$ot_rate.'
						</td>
						<td>
							'.number_format(($total_hours>0)?$total_hours:0,2).'
						</td>
                        <td>
							'.$arr['total_normal_hours'].'
						</td>
						<td>
							'.$overtime_hrs.'
						</td>
						<td>
							'.number_format($normal_chrg,2).'
						</td>
						<td>
							'.number_format($ot_chrg,2).'
						</td>
                        <td>
							'.number_format($extra_charge,2).'
						</td>
                        <td>
							'.number_format($normal_chrg+$ot_chrg+$extra_charge,2).'
						</td>
                        <td>
							'.number_format($expense,2).'
						</td>
						<td>
							'.number_format($earned,2).'
						</td>
                        <td style="background-color:'.$bgcol.'">
							'.number_format($profit,2).'
						</td>
                        
					</tr>';
                    
                    $total_work += $total_hours;
                    $total_normal_work += $arr['total_normal_hours'];
                    $total_ot_work += $overtime_hrs;
                    $total_earned += $earned;
                    $total_normal_chrg += $normal_chrg;
                    $total_ot_charge += $ot_chrg;
                    $total_expense += $expense;
                    $total_extra_charge += $extra_charge;
     }
            $total_profit = $total_earned-($total_normal_chrg+$total_ot_charge+$total_expense+$total_extra_charge);
            $bgcol = ($total_profit>0)?'#d2f7be':'mistyrose';      
            echo '<tr>
						
						<td colspan="4" class="text-left">
							<strong>
								TOTAL:
							</strong>
						</td>
						<td style="background-color:#f2f2f2">
							<strong>
								'.number_format($total_work,2).'
							</strong>
						</td>
						<td style="background-color:#f2f2f2">
							<strong>
								'.number_format($total_normal_work,2).'
							</strong>
						</td>
						<td style="background-color:#f2f2f2">
							<strong>
								'.number_format($total_ot_work,2).'
							</strong>
						</td>
                        <td style="background-color:#fff">
							<strong>
								'.number_format($total_normal_chrg,2).'
							</strong>
						</td>
						<td style="background-color:#fff">
							<strong>
								'.number_format($total_ot_charge,2).'
							</strong>
						</td>
                        <td style="background-color:#fff">
							<strong>
								'.number_format($total_extra_charge,2).'
							</strong>
						</td>
						<td style="background-color:#fff">
							<strong>
								'.number_format($total_normal_chrg+$total_ot_charge+$total_extra_charge,2).'
							</strong>
						</td>
                        <td>
							<strong>
								'.number_format($total_expense,2).'
							</strong>
						</td>
						<td>
							<strong>
								'.number_format($total_earned,2).'
							</strong>
						</td>
						<td style="background-color:'.$bgcol.'">
							<strong>
								'.number_format($total_profit,2).'
							</strong>
						</td>
                        
					</tr>';

     echo $body;
     
}
?>
	
				</tbody>
			</table>
		</div>
	</div>
</div>

</script>