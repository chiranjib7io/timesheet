<?php
/*
Users Controller perform the login and loagout functionalities
with Dashboard of all type of users.
Also contain the Forgot password, new user registration and change password.
*/
App::uses('CakeEmail', 'Network/Email');
class ReportsController extends AppController {	
	// Tell auth controller which are the functions can use without login
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Session->delete('current_url');
    }
	// Function Start
    public function index() {  			

    }
	// Function End
    public function work_time_sheet(){
        $this->layout = 'dashboard';
        $this->set('title', 'Work Time Sheet');
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $date = '';
        $project_id = 1;
        $this->set('date', $date);
        $res = $this->Timesheet->find('all',array('conditions'=>array('Timesheet.project_id'=>$project_id),'order'=>array('Timesheet.date DESC')));
        //pr($res);die;
        if(!empty($this->request->query['date'])){
           $date =  date("Y-m-d",strtotime($this->request->query['date']));
           $project_id = $this->request->query['project_id'];
           $res = $this->Timesheet->find('all',array('conditions'=>array('Timesheet.date'=>$date,'Timesheet.project_id'=>$project_id)));
           //pr($res);die;
           $this->set('date', date("d-m-Y",strtotime($date)));
        }
        $this->set('res', $res);
        $this->set('project_id', $project_id);
    }
    // Function Start
    public function time_entries() {  			
		$this->layout = 'dashboard';
        $this->set('title', 'Work Time Sheet');
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $date = $project_id = '';
        $this->set('date', $date);
        if(!empty($this->request->query['date'])){
           $date =  date("Y-m-d",strtotime($this->request->query['date']));
           $project_id = $this->request->query['project_id'];
           $res = $this->Timesheet->find('all',array('conditions'=>array('Timesheet.date'=>$date,'Timesheet.project_id'=>$project_id)));
           //pr($res);die;
           $this->set('res', $res);
           $this->set('date', date("d-m-Y",strtotime($date)));
        }
        $this->set('project_id', $project_id);
    }
	// Function End
    // Function Start
    public function time_rate_report() {  			
		$this->layout = 'dashboard';
        $this->set('title', 'Cost & Earned Report');
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $date = $start_date = $end_date = '';
        $project_id = 1;
        $this->set('date', $date);
        $this->set('res_data', array());
        if(!empty($this->request->query['date'])){
            $date = $this->request->query['date'];
           $date_arr = explode('-',$date);
           $start_date = date("Y-m-d",strtotime(trim($date_arr[0])));
           $end_date = date("Y-m-d",strtotime(trim($date_arr[1])));
           $project_id = $this->request->query['project_id'];
           $res = $this->Timesheet->find('all',array(
                                'conditions'=>array(
                                    'Timesheet.date >='=>$start_date,
                                    'Timesheet.date <='=>$end_date,
                                    'Timesheet.project_id'=>$project_id
                                    ),
                                'group'=>array('Timesheet.user_id')
                                ));
           //pr($res);die;
           $this->set('res_data', $res);
           $this->set('start_date', $start_date);
           $this->set('end_date', $end_date);
           $this->set('date', $date);
        }
        $this->set('project_id', $project_id);
    }
	// Function End
    public function expense_list(){
        $this->layout = 'dashboard';
        $this->set('title', 'Expense List');
        $exp_list = $this->Expense->find('all', array('order' => array('date desc')));
        $this->set('exp_list', $exp_list);
    }
    public function generate_invoice(){
        $this->layout = 'dashboard';
        $this->set('title', 'Generate Invoice');
        $position_list = array();
        $positions = $this->User->find('all',array('fields'=>array('position'),'conditions'=>array('User.position !='=>''),'group'=>array('position')));
        foreach($positions as $pos){
            $position_list[$pos['User']['position']] = $pos['User']['position'];
        }
        $this->set('position_list', $position_list);
        $date = $res = $position = '';
        $project_id = 1;
        $this->set('date', $date);
        $this->set('project_id', $project_id);
        $this->set('position', $position);
        if(!empty($this->request->query['date'])){
           $date =  $this->request->query['date'];
           $project_id = $this->request->query['project_id'];
           $position = $this->request->query['position'];
           $start_date = $end_date = '';
           $date_arr = explode('-',$date);
           $start_date = date("Y-m-d",strtotime(trim($date_arr[0])));
           $end_date = date("Y-m-d",strtotime(trim($date_arr[1])));
           $res = $this->Timesheet->find('all',array(     
                    'conditions'=>array(
                                'Timesheet.project_id'=>$project_id,
                                'Timesheet.date >='=>$start_date,
                                'Timesheet.date <='=>$end_date,
                                'User.position'=>$position
                                ),
                    'order'=>array('Timesheet.date ASC')));
           //pr($res);die;
           $this->set('date', $date);
           $this->set('position', $position);
           $this->set('project_id', $project_id);
        }
        $this->set('res', $res);
    }
    public function download_excel_invoice(){
        $this->layout = 'ajax';
        App::uses('SltHelper', 'View/Helper');
        $SltHelper = new SltHelper(new View());
        $date = $res = $position = $start_date = $end_date = '';
        $project_id = 1;
        if(!empty($this->request->query['date'])){
           $date =  $this->request->query['date'];
           $project_id = $this->request->query['project_id'];
           $position = $this->request->query['position'];
           $start_date = $end_date = '';
           $date_arr = explode('-',$date);
           $start_date = date("Y-m-d",strtotime(trim($date_arr[0])));
           $end_date = date("Y-m-d",strtotime(trim($date_arr[1])));
           $res = $this->Timesheet->find('all',array(    
                    'conditions'=>array(
                                'Timesheet.project_id'=>$project_id,
                                'Timesheet.date >='=>$start_date,
                                'Timesheet.date <='=>$end_date,
                                'User.position'=>$position
                                ),
                    'order'=>array('Timesheet.date ASC')));
        }
        $this->set('end_date', $end_date);
        $this->set('res', $res);
        $this->set('position', $position);
    }
}
// End of controller
?>