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
                <a href="<?php echo $this->Html->url('/projects/edit_user_entry/'.$row['Timesheet']['id']); ?>">View Entry</a>
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
                </div>
                <div class="panel-collapse " id="collapse-category-<?php echo $k+1; ?>" role="tabpanel" aria-labelledby="collapse-heading-<?php echo $k+1; ?>">
                    <div class="panel-body">
                        <table class="table table-striped ">
                            <tr>
                                <th>APN #</th>
                                <th>PLC</th>
                                <th>Time In</th>
                                <th>Break (hrs)</th>
                                <th>Time Out</th>
                                
                                <th>Duration (hrs)</th>
                                <th>OT (hrs)</th>
                                <th>Double Rate Hours</th>
                            </tr>
                <?php
                    $duration = $break_time = $normal_hours = 0;
                    
                    $normal_hours = !empty($row['Timesheet']['normal_hours'])?$row['Timesheet']['normal_hours']:0;
                    $timedetails = $this->Slt->get_timesheet_detail($row['Timesheet']['id']);
                    foreach($timedetails as $row){ 
                            $rowsheet = $row['TimesheetDetail'];
                            $duration += $rowsheet['decimal_duration'];
                            $break_time += $rowsheet['break_hours'];
                ?>
                            <tr>
                                <td><?php echo $rowsheet['apn_no']; ?></td>
                                <td><?php echo $rowsheet['plc_no']; ?></td>
                                <td><?php echo $rowsheet['start_time']; ?></td>
                                <td><?php echo $rowsheet['break_hours']; ?></td>
                                <td><?php echo $rowsheet['end_time']; ?></td>
                                
                                <td><?php echo $rowsheet['decimal_duration']-$rowsheet['break_hours']; ?></td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                <?php
                    }
                    $extra_hours = $ot_hours = 0;
                    $duration = $duration-$break_time;
                    if(($row['Timesheet']['ot_eligible']==1)&&($duration-$normal_hours)>0){
                        if($duration>12){
                            $extra_hours = $duration - 12;
                            $ot_hours = $duration - $normal_hours-$extra_hours;
                        }else{
                            $extra_hours = 0;
                            $ot_hours = $duration - $normal_hours;
                        }
                    }
                ?>
                            <tr>
                                <td colspan="5">Total:</td>
                                <td><?php echo $duration; ?></td>
                                <td><?php echo $ot_hours; ?></td>
                                <td><?php echo $extra_hours; ?></td>
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