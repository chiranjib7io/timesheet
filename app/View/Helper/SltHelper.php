<?php
/* /app/View/Helper/LinkHelper.php (using other helpers) */
App::uses('AppHelper', 'View/Helper');
class SltHelper extends AppHelper {
    public function get_day_name($timestamp) 
    {
        $date = date('d/m/Y', $timestamp);
        if($date == date('d/m/Y')) {
          $date = 'Today';
        } 
        else if($date == date('d/m/Y',time() - (24 * 60 * 60))) {
          $date = 'Yesterday';
        }
        return $date;
    }
    public function cal_month($date1,$date2)
    {
		//$date1 = '2000-01-25';
		//$date2 = '2010-02-20';
		$ts1 = strtotime($date1);
		$ts2 = strtotime($date2);
		$year1 = date('Y', $ts1);
		$year2 = date('Y', $ts2);
		$month1 = date('m', $ts1);
		$month2 = date('m', $ts2);
		$diff = (($year2 - $year1) * 12) + ($month2 - $month1);
		return $diff;
	}
	// Genaral function for making a JSON file
    function prepare_json($response, $remove_null = 1)  
    {
        $json = json_encode($response, true);
        if ($remove_null == 1) {
            $json = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $json);
        }
        return $json;
    }
    // Upload Type ID Array Start
    function id_proof_name()  
    {
        $upload_type = array(
            "Voter Card" => "Voter Card",
            "Aadhar Card" => "Aadhar Card",
            "Ration Card" => "Ration Card",
            "PAN Card" => "PAN Card",
            "Driving License" => "Driving License",
            "Passport" => "Passport",
            "Panchayat Certificate" => "Panchayat Certificate"
		);
        return $upload_type;
    }
    // Upload Type ID Array End
	// Relationship Type Array Start
    function relationship_type()
    {
        $reletion_type = array(
            "Husband" => "Husband",
            "Wife" => "Wife",
            "Father" => "Father",
            "Mother" => "Mother",
            "Son" => "Son",
            "Daughter" => "Daughter",
            "Brother" => "Brother",
            "Sister" => "Sister",
            "Uncle" => "Uncle",
            "Anut" => "Anut",
            "Grand Father" => "Grand Father",
            "Grand Mother" => "Grand Mother",
            "Grand Son" => "Grand Son",
            "Grand Daughter" => "Grand Daughter"
		);
        return $reletion_type;
    }
    // Relationship Type Array End
    function multi_array_search($search_for, $search_in) {
        foreach ($search_in as $key=>$element) {
            if ( ($element === $search_for) || (is_array($element) && $this->multi_array_search($search_for, $element)) ){
                return $key;
            }
        }
        return -1;
    }
    function array_find_deep($array, $search, $keys = array())
    {
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $sub = $this->array_find_deep($value, $search, array_merge($keys, array($key)));
                if (count($sub)) {
                    return $sub;
                }
            } elseif ($value === $search) {
                return array_merge($keys, array($key));
            }
        }
        return array();
    }
    // Find the date difference function Start
    public function date_difference($date1,$date2){
        $diff = abs(strtotime($date2) - strtotime($date1));
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        return $days;
    }
    // Find the date difference function End
    function rangeMonth($datestr) {
        date_default_timezone_set(date_default_timezone_get());
        $dt = strtotime($datestr);
        $res['start'] = date('Y-m-d', strtotime('first day of this month', $dt));
        $res['end'] = date('Y-m-d', strtotime('last day of this month', $dt));
        return $res;
    }
  function rangeWeek($datestr) {
    date_default_timezone_set(date_default_timezone_get());
    $dt = strtotime($datestr);
    $res['start'] = date('N', $dt)==1 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('last monday', $dt));
    $res['end'] = date('N', $dt)==7 ? date('Y-m-d', $dt) : date('Y-m-d', strtotime('next sunday', $dt));
    return $res;
  }
  public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
    public function get_all_rate($user_id){
        App::import("Model","User");
        $User_model=  new User();
        $rate_data = $User_model->find('first',array('conditions'=>array('User.id'=>$user_id)));
        if(!empty($rate_data))
            return $rate_data['User'];
        else
            return $rate_data;
    }
    public function get_hours($user_id,$date,$project_id=1){
        App::import("Model","Timesheet");
        $Timesheet_model=  new Timesheet();
        $time_data = $Timesheet_model->find('first',array('conditions'=>array('Timesheet.user_id'=>$user_id,'Timesheet.project_id'=>$project_id,'Timesheet.date'=>$date)));
        $total_hours = $total_break = 0;
        if(!empty($time_data['TimesheetDetail'])){
            foreach($time_data['TimesheetDetail'] as $row){
                $total_hours += $row['decimal_duration'];
                $total_break += $row['break_hours'];
            }
        }
        return ($total_hours-$total_break);    
    }
    public function get_expense($user_id,$date,$project_id=1){
        App::import("Model","Expense");
        $Expense_model=  new Expense();
        $exp_data = $Expense_model->find('all',array('conditions'=>array('Expense.user_id'=>$user_id,'Expense.project_id'=>$project_id,'Expense.date'=>$date)));
        $total_exp = 0;
        if(!empty($exp_data)){
            foreach($exp_data as $row){
                $total_exp += $time_data['Expense']['amount'];
            }
        }
        return $total_exp;    
    }
    public function get_expense_for_range($user_id,$start_date,$end_date,$project_id=1){
        App::import("Model","Expense");
        $Expense_model=  new Expense();
        $exp_data = $Expense_model->find('all',array('conditions'=>array('Expense.user_id'=>$user_id,'Expense.project_id'=>$project_id,'Expense.date >='=>$start_date,'Expense.date <='=>$end_date)));
        $total_exp = 0;
        if(!empty($exp_data)){
            foreach($exp_data as $row){
                $total_exp += $time_data['Expense']['amount'];
            }
        }
        return $total_exp;    
    }
    function calculate_hours_and_cost_for_range($user_id,$start_date,$end_date,$project_id=1){
        App::import("Model","Timesheet");
        $Timesheet_model=  new Timesheet();
        $times_res = $Timesheet_model->find('all',array('conditions'=>array('Timesheet.user_id'=>$user_id,'Timesheet.project_id'=>$project_id,'Timesheet.date >='=>$start_date,'Timesheet.date <='=>$end_date)));
        $res_arr = array();
        $total_hours = $total_ot_hours = $total_normal_hours = $total_cost = $total_earned = $total_normal_charge = $total_ot_charge = $total_exp = $total_extra_charge = 0;   
        foreach($times_res as $time_data){
            $normal_hours = $ot_hours = $client_rate = $normal_rate = $ot_rate = $duration = $total_break = $exp = $extra_hours = 0;
            $normal_charge = $ot_charge = $client_charge = $extra_charge = 0;
        	$client_rate = number_format(!empty($time_data['Timesheet']['client_rate'])?$time_data['Timesheet']['client_rate']:0,2);
            $normal_rate = number_format(!empty($time_data['Timesheet']['normal_rate'])?$time_data['Timesheet']['normal_rate']:0,2);
            $ot_rate = number_format(($time_data['Timesheet']['ot_eligible']==1)?$time_data['Timesheet']['ot_rate']:0,2);
            $normal_hours = !empty($time_data['Timesheet']['normal_hours'])?$time_data['Timesheet']['normal_hours']:0;
            if(!empty($time_data['TimesheetDetail'])){
                foreach($time_data['TimesheetDetail'] as $rowsheet){ 
                        $duration += $rowsheet['decimal_duration'];   
                        $total_break += $rowsheet['break_hours'];    
                }
            }
            $duration = $duration-$total_break;
            if(($time_data['Timesheet']['ot_eligible']==1)&&($duration>=$normal_hours)){
                if($duration>12){
                    $extra_hours = $duration-12;
                    $extra_charge = $extra_hours*($normal_rate*2);
                    $ot_hours = ($duration - $extra_hours) - $normal_hours;
                    $normal_charge = $normal_hours*$normal_rate;
                    $ot_charge = $ot_hours*$ot_rate;
                }else{
                    $extra_hours = 0;
                    $extra_charge = 0;
                    $ot_hours = $duration - $normal_hours;
                    $normal_charge = $normal_hours*$normal_rate;
                    $ot_charge = $ot_hours*$ot_rate;
                }
            }else{
                $extra_hours = 0;
                $extra_charge = 0; 
                $ot_hours = 0;
                $normal_charge = $duration*$normal_rate;
                $ot_charge = 0;
            }      
            $client_charge = $duration*$client_rate;
            $cost = ($normal_charge+$ot_charge+$extra_charge);
            $total_cost += $cost;
            $total_earned += $client_charge;
            $total_normal_charge += $normal_charge;
            $total_ot_charge += $ot_charge;
            $total_hours += $duration;
            $total_ot_hours += $ot_hours;
            $total_normal_hours += $normal_hours;
            $total_extra_charge += $extra_charge;
        }
        $res_arr['total_cost'] = $total_cost;
        $res_arr['total_hours'] = $total_hours;
        $res_arr['total_ot_hours'] = $total_ot_hours;
        $res_arr['total_normal_hours'] = $total_normal_hours;
        $res_arr['total_ot_charge'] = $total_ot_charge;
        $res_arr['total_earned'] = $total_earned;
        $res_arr['total_normal_charge'] = $total_normal_charge;
        $res_arr['total_extra_charge'] = $total_extra_charge;
        return $res_arr;  
    }
    function calculate_hours_and_cost($time_data){
        $res_arr = array();
        $total_hours = $total_cost = $total_earned = $total_normal_charge = $total_ot_charge = $total_exp = $total_extra_charge = 0; 
        $normal_hours = $ot_hours = $client_rate = $normal_rate = $ot_rate = $duration = $total_break = $exp = $extra_hours = 0;
        $normal_charge = $ot_charge = $client_charge = $extra_charge = 0;
    	$client_rate = number_format(!empty($time_data['Timesheet']['client_rate'])?$time_data['Timesheet']['client_rate']:0,2);
        $normal_rate = number_format(!empty($time_data['Timesheet']['normal_rate'])?$time_data['Timesheet']['normal_rate']:0,2);
        $ot_rate = number_format(($time_data['Timesheet']['ot_eligible']==1)?$time_data['Timesheet']['ot_rate']:0,2);
        $normal_hours = !empty($time_data['Timesheet']['normal_hours'])?$time_data['Timesheet']['normal_hours']:0;
        if(!empty($time_data['TimesheetDetail'])){
            foreach($time_data['TimesheetDetail'] as $rowsheet){ 
                    $duration += $rowsheet['decimal_duration'];   
                    $total_break += $rowsheet['break_hours'];    
            }
        }
        $duration = $duration-$total_break;
        if(($time_data['Timesheet']['ot_eligible']==1)&&($duration>=$normal_hours)){
            if($duration>12){
                $extra_hours = $duration-12;
                $extra_charge = $extra_hours*($normal_rate*2);
                $ot_hours = ($duration - $extra_hours) - $normal_hours;
                $normal_charge = $normal_hours*$normal_rate;
                $ot_charge = $ot_hours*$ot_rate;
            }else{
                $extra_hours = 0;
                $extra_charge = 0;
                $ot_hours = $duration - $normal_hours;
                $normal_charge = $normal_hours*$normal_rate;
                $ot_charge = $ot_hours*$ot_rate;
            }
        }else{
            $extra_hours = 0;
            $extra_charge = 0;
            $ot_hours = 0;
            $normal_charge = $duration*$normal_rate;
            $ot_charge = 0;
        }    
        $client_charge = $duration*$client_rate;
        $cost = ($normal_charge+$ot_charge+$extra_charge);
        $total_cost += $cost;
        $total_earned += $client_charge;
        $total_normal_charge += $normal_charge;
        $total_ot_charge += $ot_charge;
        $total_hours += $duration;
        $total_extra_charge += $extra_charge;
        $res_arr['total_cost'] = $total_cost;
        $res_arr['total_hours'] = $total_hours;
        $res_arr['total_ot_hours'] = $ot_hours;
        $res_arr['total_ot_charge'] = $total_ot_charge;
        $res_arr['total_earned'] = $total_earned;
        $res_arr['total_normal_charge'] = $total_normal_charge;
        $res_arr['total_extra_charge'] = $total_extra_charge;
        return $res_arr;  
    }
    public function get_times_summary_position($date='',$project_id=1){
        App::import("Model","User");
        $User_model=  new User();
        App::import("Model","Timesheet");
        $Timesheet_model=  new Timesheet();
        $positions = $User_model->find('all',array('fields'=>array('position'),'conditions'=>array('User.position !='=>''),'group'=>array('position')));
        foreach($positions as $pos){
            $entry_data[$pos['User']['position']] = $Timesheet_model->find('all',array(
                    'conditions'=>array(
                                'Timesheet.project_id'=>$project_id,
                                'Timesheet.date'=>$date,
                                'User.position'=>$pos['User']['position']
                                ),
                    'order'=>array('Timesheet.date DESC')));
        }     
        return $entry_data;            
    }
    public function get_timesheet_detail($time_id){
        App::import("Model","TimesheetDetail");
        $TimesheetDetail_model=  new TimesheetDetail();
        $res = $TimesheetDetail_model->find('all',array('conditions'=>array('TimesheetDetail.timesheet_id'=>$time_id),'order'=>array('TimesheetDetail.start_time asc')));
        return $res;
    }
    public function get_verification_detail($time_id){
        App::import("Model","Verifylog");
        $Verifylog_model=  new Verifylog();
        $res = $Verifylog_model->find('all',array('conditions'=>array('Verifylog.timesheet_id'=>$time_id),'order'=>array('Verifylog.verified_on asc')));
        return $res;
    }
    public function get_verification_log($time_id,$user_id){
        App::import("Model","Verifylog");
        $Verifylog_model=  new Verifylog();
        $res = $Verifylog_model->find('first',array('conditions'=>array('Verifylog.timesheet_id'=>$time_id,'Verifylog.user_id'=>$user_id),'order'=>array('Verifylog.verified_on asc')));
        return $res;
    }
    public function get_user_name($user_id){
        App::import("Model","User");
        $User_model=  new User();
        $rate_data = $User_model->find('first',array('conditions'=>array('User.id'=>$user_id)));
        if(!empty($rate_data))
            return $rate_data['User']['first_name'].' '.$rate_data['User']['last_name'];
        else
            return $rate_data;
    }
}
?>