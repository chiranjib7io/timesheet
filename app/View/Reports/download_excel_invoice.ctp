<?php 
$header = array(
            'Date of Service',
            'Employee',
            'Position',
            'APN',
            'Time Card',
            'Hours',
            'Week Hours'
            );

$xls_content_row = '';
$filename = $position."_ending_".date("m-d-Y",strtotime($end_date)).".xls";
header("Content-type: text/plain; charset=UTF-8");
header("Content-Disposition: attachment; filename=$filename");
header ("Content-Type:application/vnd.ms-excel"); 
header("Pragma: no-cache");
header("Expires: 0");
$xls_content_header = implode("\t", array_values($header));
$total_hours = $client_rate = 0;
if(!empty($res)){
    foreach($res as $k=>$row){
        $client_rate = $row['Timesheet']['client_rate'];  
        $duration = $break_time = 0;
        $timedetails = $this->Slt->get_timesheet_detail($row['Timesheet']['id']);
        foreach($timedetails as $j=>$rws){
            $rowsheet = $rws['TimesheetDetail'];
            $rows = array();
            if($j==0){
                $rows[]= date("M d, Y",strtotime($row['Timesheet']['date']));
                $rows[]= $row['User']['fullname'];
                $rows[]= $row['User']['position']; 
            }else{
                $rows[] = '';
                $rows[] = '';
                $rows[] = '';
            }
            $start_time = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $rowsheet['start_time'])/100;
            $end_time = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $rowsheet['end_time'])/100;
            $time_pad = str_pad($start_time, 4, "0", STR_PAD_LEFT).'-'.str_pad($end_time, 4, "0", STR_PAD_LEFT);
            $break = ($rowsheet['break_hours']>0)?'('.$rowsheet['break_hours'].')':'';
            $rows[]= $rowsheet['apn_no'];
            $rows[]= $time_pad.$break;
            $rows[]= $rowsheet['decimal_duration']-$rowsheet['break_hours'];
            $rows[] = '';
            
            $duration += $rowsheet['decimal_duration'];
            $break_time += $rowsheet['break_hours'];
            $xls_content_row .= implode("\t", array_values($rows)) . "\r\n";
        }
        $rows = array();
        $rows[] = '';
        $rows[] = '';
        $rows[] = '';
        $rows[] = 'Total';
        $rows[] = '';
        $rows[] = '';
        $rows[] = $duration-$break_time;
        $total_hours += ($duration-$break_time);
        $xls_content_row .= implode("\t", array_values($rows)) . "\r\n";
    }
}

$rows = array();
$rows[] = '';
$rows[] = '';
$rows[] = '';
$rows[] = $total_hours.' hours x';
$rows[] = '$'.$client_rate.' =';
$rows[] = '$'.number_format($total_hours*$client_rate,2);
$rows[] = '';

$xls_content_row .= implode("\t", array_values($rows)) . "\r\n";        

$xls_content = $xls_content_header."\n".$xls_content_row;
      
print $xls_content;
?>
