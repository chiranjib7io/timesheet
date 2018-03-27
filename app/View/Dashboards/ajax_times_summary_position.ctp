   
    <?php
    if(!empty($res)){
        $k=0;
        foreach($res as $position=>$row){
            
            if(!empty($row)){
    ?>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="collapse-heading-<?php echo $k+1; ?>">
                    <a role="button" class="panel-link" data-toggle="collapse" data-parent="#accordion" href="#collapse-category-<?php echo $k+1; ?>" aria-expanded="false" aria-controls="collapse-category-<?php echo $k+1; ?>">
                    <?php echo $position; ?>
                </a>
                
                
                </div>
                <div class="panel-collapse " id="collapse-category-<?php echo $k+1; ?>" role="tabpanel" aria-labelledby="collapse-heading-<?php echo $k+1; ?>">
                    <div class="panel-body">
                        <table class="table table-striped ">
                            <tr>
                                <th>Name</th>
                                <th>Normal Hours</th>
                                <th>Normal Cost</th>
                                <th>OT Hours</th>
                                <th>OT Cost</th>
                                <th>Other Cost</th>
                                <th>Total Cost</th>
                                <th>Total Earning</th>
                            </tr>
                <?php
                $total_cost = $total_earned = 0;
                foreach($row as $rowsheet){
                    $res = $this->Slt->calculate_hours_and_cost($rowsheet);
                    $other_cost = 0;
                    $cost = $res['total_cost']+$other_cost;
                    
                    $total_cost += $cost;
                    $total_earned += $res['total_earned'];
                ?>
                            <tr>
                                <td><?php echo $rowsheet['User']['fullname']; ?></td>
                                <td><?php echo $rowsheet['Timesheet']['normal_hours']; ?></td>
                                <td><?php echo number_format($rowsheet['Timesheet']['normal_hours']*$rowsheet['Timesheet']['normal_rate']); ?></td>
                                <td><?php echo $res['total_ot_hours']; ?></td>
                                <td><?php echo number_format($res['total_ot_charge']); ?></td>
                                <td><?php echo number_format($other_cost); ?></td>
                                <td><?php echo number_format($cost); ?></td>
                                <td><?php echo number_format($res['total_earned']); ?></td>
                                
                            </tr>
                <?php
                }
                ?>
                
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                
                                <td colspan="2">Total Cost:<b><?php echo number_format($total_cost); ?></b></td>
                                <td>Total Earned: <b><?php echo number_format($total_earned); ?></b></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
    <?php
    $k++;
        }
    
        }
    }
    ?>        