<?php
// This is CSV data controller. This controller is for upload data via CSV. 
App::uses('CakeEmail', 'Network/Email');
class DashboardsController extends AppController {
	// List of models which are used in the csv data controller
		var $uses = array();
	// If there is any wrong navigation
	public function index(){
		$this->layout = 'dashboard';
		$this->set('title', 'Dashbord');
		// Call Values for the Dashboard from the session
		$user_id=$this->Auth->user('id'); // Login user's user id
		$user_type_id=$this->Auth->user('user_type_id'); // Login user's user type
		$day = date("Y-m-d");
        $date = $day;
        $project_id = 1;
        $this->set('date', $date);  
        /** Verify data start **/   
        $not_verified = $this->Timesheet->find('count',array('conditions'=>array('Timesheet.verified'=>0,'or'=>array('Timesheet.entered_by !='=>$this->Auth->user('id'),'Timesheet.created_on <='=>date("Y-m-d",strtotime("-5 days"))))));
        $total_not_verified = $this->Timesheet->find('count',array('conditions'=>array('Timesheet.verified'=>0)));
        $total_partial_verified = $this->Timesheet->find('count',array('conditions'=>array('Timesheet.verified'=>2)));
        $verified = $this->Timesheet->find('count',array('conditions'=>array('Timesheet.verified'=>1)));
        
        $partial_verified = $this->Timesheet->find('count', array(
            'joins' => array(
                array(
                    'table' => 'verifylogs',
                    'alias' => 'Verifylog',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Verifylog.timesheet_id = Timesheet.id',
                        'Verifylog.user_id !='=>$this->Auth->user('id')
                    )
                )
            ),
            'conditions' => array(
                'Timesheet.verified = 2',
                
            )
        ));
        
        $this->set('not_verified',$not_verified);
        $this->set('total_not_verified',$total_not_verified);
        $this->set('partial_verified',$partial_verified);
        $this->set('total_partial_verified',$total_partial_verified);
        $this->set('verified',$verified);
        /** Verify data end **/ 
        /** Graph Data start ***/
        $data = $this->get_graph_data('','');
        $this->set('earn',$data['earn']);
        $this->set('expense',$data['expense']);
        $this->set('times',$data['times']);
        /** Graph data end ***/
        /** Summary by position start *******/
        $res_dates = $this->Timesheet->find('all',array('fields'=>array('Timesheet.date'),'conditions'=>array('Timesheet.project_id'=>$project_id),'order'=>array('Timesheet.date DESC'),'group'=>array('Timesheet.date'),'recursive'=>'-1'));
        //pr($res);die;      
        $this->set('res_dates', $res_dates);
        /** Summary by position end *******/
	}
        
	/** Graph Data start ***/
    public function ajax_graph_data($start_date='',$end_date = ''){
        $this->layout = 'ajax';
        $data = $this->get_graph_data($start_date,$end_date);
        $this->set('earn',$data['earn']);
        $this->set('expense',$data['expense']);
        $this->set('times',$data['times']);    
    }
    public function get_graph_data($start_date='',$end_date = ''){
        if($start_date==''){
            $start_date = date('Y-m-d', strtotime("-29 days"));
        }
        if($end_date==''){
            $end_date = date('Y-m-d');
        }
        $date1=date_create($start_date);
        $date2=date_create($end_date);
        $diff=date_diff($date1,$date2);
        $days = $diff->format("%a")+0;
        $data = array();
        for($i=0;$i<=$days;$i++){
            $date = date("Y-m-d",strtotime("$start_date +$i days"));
            $total_cost = $total_earned = $total_exp = 0;
            //if(trim(date('w', strtotime($date))!=6)){              
                $exp_data = $this->Expense->find('all',array('conditions'=>array('Expense.project_id'=>1,'Expense.date'=>$date)));
                if(!empty($exp_data)){
                    foreach($exp_data as $row){
                        $total_exp += $time_data['Expense']['amount'];
                    }
                }
                $times_result = $this->Timesheet->find('all',array('conditions'=>array('Timesheet.date'=>$date)));
                if(!empty($times_result)){
                    $cost_data = $this->calculate_cost($times_result);
                    $total_cost = $cost_data['total_cost']+$total_exp;
                    $total_earned = $cost_data['total_earned'];
                    $data['times'][] = date("d M y",strtotime($date));
                    $data['earn'][] = !empty($total_earned)?round($total_earned,2):0;
                    $data['expense'][] = !empty($total_cost)?round($total_cost,2):0;
                }else{
                    if($total_exp>0){
                        $data['times'][] = date("d M y",strtotime($date));
                        $data['earn'][] = 0;
                        $data['expense'][] = round(0+$total_exp,2);
                    } 
                }
            //}
        }
        //pr($data);die;
        return $data;               
    }
    /** Graph data end ***/
    
    /** Time summary position start *******/
    public function ajax_times_summary_position($date=''){
        $this->layout = 'ajax';
        $date = date("Y-m-d",strtotime($date));
        $res = $this->get_times_summary_position($date); 
        $this->set('res', $res);
        $this->set('date', $date);        
    }
    public function get_times_summary_position($date='',$project_id=1){
        $positions = $this->User->find('all',array('fields'=>array('position'),'conditions'=>array('User.position !='=>''),'group'=>array('position')));
        foreach($positions as $pos){
            $entry_data[$pos['User']['position']] = $this->Timesheet->find('all',array(
                    'conditions'=>array(
                                'Timesheet.project_id'=>$project_id,
                                'Timesheet.date'=>$date,
                                'User.position'=>$pos['User']['position']
                                ),
                    'order'=>array('Timesheet.date DESC')));
        }       
        return $entry_data;             
    }
    function getWeekDates($year, $week, $start='',$end='')
    {
        $from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
        if(strtotime($from)< strtotime($start)){
            $from = date("Y-m-d",strtotime($start));
        }
        $to = date("Y-m-d", strtotime("{$year}-W{$week}-7"));   //Returns the date of sunday in week
        if(strtotime($to)> strtotime($end)){
            $to = date("Y-m-d",strtotime($end));
        }
        $arr['start'] = $from;
        $arr['end'] = $to;
        $arr['wkd'] = $week;
        return $arr;
    }
    /** Loan Collection end *******/
    
    function getWorkdays($date1, $date2, $workSat = TRUE, $patron = NULL) {
          if (!defined('SATURDAY')) define('SATURDAY', 6);
          if (!defined('SUNDAY')) define('SUNDAY', 0);
          // Array of all public festivities
          $publicHolidays = array();
          // The Patron day (if any) is added to public festivities
          if ($patron) {
            $publicHolidays[] = $patron;
          }
          /*
           * Array of all Easter Mondays in the given interval
           */
          $yearStart = date('Y', strtotime($date1));
          $yearEnd   = date('Y', strtotime($date2));
          for ($i = $yearStart; $i <= $yearEnd; $i++) {
            $easter = date('Y-m-d', easter_date($i));
            list($y, $m, $g) = explode("-", $easter);
            $monday = mktime(0,0,0, date($m), date($g)+1, date($y));
            $easterMondays[] = $monday;
          }
          $start = strtotime($date1);
          $end   = strtotime($date2);
          $workdays = 0;
          for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if ($day != SUNDAY &&
              !in_array($mmgg, $publicHolidays) &&
              !in_array($i, $easterMondays) &&
              !($day == SATURDAY && $workSat == FALSE)) {
                $workdays++;
            }
          }
          return intval($workdays);
    }
}
// CSV data controller end
?>