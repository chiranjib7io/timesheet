<?php
/*
Projects Controller perform the projects functionalities

*/
App::uses('CakeEmail', 'Network/Email');
class ProjectsController extends AppController {
	// Pagination in Cakephp
	public $paginate = array(
        'limit' => 25,
        'conditions' => array('status' => '1'),
    	'order' => array('Project.project_title' => 'asc' ) 
    );
	// Tell auth controller which are the functions can use without login
    public function beforeFilter() {
        parent::beforeFilter();
    }
	// Function Start
    public function index() {  
		$this->layout = 'dashboard';
		$this->set('title', 'Project List');
		$projects = $this->Project->find('all');
		$this->set(compact('projects'));
    }
	// Function End
	public function save($pid = '')
    {
        $this->layout = 'dashboard';
        $this->set('title', 'Save Project');
        $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
        $user_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 4,
                    )));
        $this->set('user_list', $user_list);
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $ds = $this->Project->getDataSource();
	       $ds->begin();
            if ($pid != '') {
                $this->request->data['Project']['modified_on'] = date("Y-m-d H:i:s");
                $this->Project->id = $pid;
            } else {
                $this->request->data['Project']['modified_on'] = date("Y-m-d H:i:s");
                $this->Project->create();
            }
            if ($this->Project->save($this->request->data)) {
                if($pid ==''){
                    $pid = $this->Project->getLastInsertID();
                }
                if(!empty($this->request->data['users'])){
                    $this->UsersProject->deleteAll(array('UsersProject.project_id' => $pid), true);
                    foreach($this->request->data['users'] as $val){
                        $sdata['UsersProject'] = $val;
                        $sdata['UsersProject']['project_id'] = $pid;
                        $sdata['UsersProject']['assign_date'] = date("Y-m-d");
                        $sdata['UsersProject']['modified_on'] = date("Y-m-d H:i:s");
                        $this->UsersProject->clear();
                        if(!$this->UsersProject->save($sdata)){
                            $ds->rollback();
                            $this->Session->setFlash(__('Unable to save your Project.'));
                            return $this->redirect(array('action' => 'save',$pid));
                        }
                    }
                }
                $ds->commit();
                $this->Session->setFlash(__('Your Project has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }else{
                $ds->rollback();
                $this->Flash->error(__('Unable to save your Project.'));
            }
        }
        if ($pid != '') {
            $this->request->data = $this->Project->findById($pid);
        }
    }
	//User delete function start
    public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Please provide a Project id');
			$this->redirect(array('action'=>'index'));
		}
        $this->Project->id = $id;
        if (!$this->Project->exists()) {
            $this->Session->setFlash('Invalid Project id provided');
			$this->redirect(array('action'=>'index'));
        }
        if ($this->Project->saveField('status', 0)) {
            $this->Session->setFlash(__('Project deleted'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Project was not deleted'));
        $this->redirect(array('action' => 'index'));
    }
	//User delete function end
    public function user_entry() {
        $this->layout = 'dashboard';
        $this->set('title', 'Worksheet Input');
        $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
        $user_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 4,
                    'User.status' => 1,
                    )));
        $this->set('user_list', $user_list);
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['Timesheet']['date'] = date("Y-m-d", strtotime($this->request->data['Timesheet']['date']));
            //debug($this->request->data);
            $times = $this->Timesheet->find('first',array('conditions'=>array('Timesheet.project_id'=>$this->request->data['Timesheet']['project_id'],'Timesheet.user_id'=>$this->request->data['Timesheet']['user_id'],'Timesheet.date'=>date('Y-m-d',strtotime($this->request->data['Timesheet']['date'])))));
            if(!empty($times)){ 
                $this->Session->setFlash(__('There is already entry exist for this date and user'));
                return $this->redirect(array('action' => 'edit_user_entry',$times['Timesheet']['id']));
            }else{
                $getRates = $this->Micro->get_all_rate($this->request->data['Timesheet']['user_id']);
                $this->request->data['Timesheet']['client_rate']  = $getRates['client_rate'];
                $this->request->data['Timesheet']['normal_rate']  = $getRates['normal_rate'];
                $this->request->data['Timesheet']['ot_eligible']  = $getRates['ot_eligible'];
                $this->request->data['Timesheet']['ot_rate']      = $getRates['ot_rate'];
                $this->request->data['Timesheet']['normal_hours'] = $getRates['normal_hours'];
                $this->request->data['Timesheet']['entered_by'] = $this->Auth->user('id');
                $this->Timesheet->create();
                if ($this->Timesheet->save($this->request->data)) {
                    $id = $this->Timesheet->getLastInsertID();
                }
            }
            if(!empty($this->request->data['times'])) {
                $ds = $this->Timesheet->getDataSource();
                $ds->begin();
                foreach($this->request->data['times'] as $val) {
                    $sdata['TimesheetDetail'] = $val;
                    $sdata['TimesheetDetail']['timesheet_id'] = $id;
                    $sdata['TimesheetDetail']['modified_on'] = date("Y-m-d H:i:s");
                    $time1 = strtotime($val['start_time']);
                    $time2 = strtotime($val['end_time']);
                    $difference = round(abs($time2 - $time1) / 3600,2);
                    $sdata['TimesheetDetail']['decimal_duration'] = $difference;
                    $this->TimesheetDetail->clear();
                    if(!$this->TimesheetDetail->save($sdata)){
                        $ds->rollback();
                        $this->Session->setFlash(__('Unable to save data.'));
                        return $this->redirect(array('action' => 'user_entry'));
                    }
                }
                $ds->commit();
                $this->Session->setFlash(__('Your data has been saved.')); 
            }else{
                $ds->rollback();
                $this->Session->setFlash(__('Unable to save data.'));
            }
        }
	}
    public function edit_user_entry($id) {
        $this->layout = 'dashboard';
        $this->set('title', 'Worksheet Update');      
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
        $user_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 4,
                    )));
        //pr($user_list);die;
        $this->set('user_list', $user_list);
        if ($this->request->is(array('post', 'put'))) {
            //debug($this->request->data);
            $this->request->data['Timesheet']['modified_on'] = date("Y-m-d H:i:s");
            $ds = $this->Timesheet->getDataSource();
	        $ds->begin();
            $getRates = $this->Micro->get_all_rate($this->request->data['Timesheet']['user_id']);
            $this->request->data['Timesheet']['client_rate']  = $getRates['client_rate'];
            $this->request->data['Timesheet']['normal_rate']  = $getRates['normal_rate'];
            $this->request->data['Timesheet']['ot_eligible']  = $getRates['ot_eligible'];
            $this->request->data['Timesheet']['ot_rate']      = $getRates['ot_rate'];
            $this->request->data['Timesheet']['normal_hours'] = $getRates['normal_hours'];
            $this->Timesheet->id=$id;
            if ($this->Timesheet->save($this->request->data)) {
                if(!empty($this->request->data['times'])) {
                    $this->TimesheetDetail->deleteAll(array('TimesheetDetail.timesheet_id' => $id), false);
                    $ds = $this->Timesheet->getDataSource();
                    $ds->begin();
                    foreach($this->request->data['times'] as $val) {
                        $sdata['TimesheetDetail'] = $val;
                        $sdata['TimesheetDetail']['timesheet_id'] = $id;
                        $sdata['TimesheetDetail']['modified_on'] = date("Y-m-d H:i:s");
                        $time1 = strtotime($val['start_time']);
                        $time2 = strtotime($val['end_time']);
                        $difference = round(abs($time2 - $time1) / 3600,2);
                        $sdata['TimesheetDetail']['decimal_duration'] = $difference;
                        $this->TimesheetDetail->clear();
                        if(!$this->TimesheetDetail->save($sdata)){
                            $ds->rollback();
                            $this->Session->setFlash(__('Unable to save data.'));
                            return $this->redirect(array('action' => 'edit_user_entry',$id));
                        }
                    }
                    $ds->commit();
                    $this->Session->setFlash(__('Your data has been updated.'));
                    if($this->Session->check('current_url')){
                        return $this->redirect(array('action' => 'edit_user_entry',$id));
                    }else{
                        return $this->redirect('/reports/work_time_sheet'); 
                    }
                       
                }else{
                    $ds->rollback();
                    $this->Session->setFlash(__('Unable to save data.'));
                }
            }else{
                $ds->rollback();
                $this->Session->setFlash(__('Unable to save data.'));
            }
        }
        $tdata = $this->Timesheet->find('first',array('conditions'=>array('Timesheet.id'=>$id)));
        $this->request->data = $tdata;
        
        
	}
    function timesheet_delete($id){
        if($this->Timesheet->delete($id)){
            $this->TimesheetDetail->deleteAll(array('TimesheetDetail.timesheet_id' => $id), false);
        }
        return $this->redirect('/reports/work_time_sheet'); 
    }
    function ajax_get_users_rate($uid){
        $this->layout ='ajax';
        $this->autoRender =false;
        $getRates = $this->Micro->get_all_rate($uid);
        $arr['client_rate']  = !empty($getRates['client_rate'])?$getRates['client_rate']:0;
        $arr['normal_rate']  = !empty($getRates['normal_rate'])?$getRates['normal_rate']:0;
        $arr['ot_eligible']  = !empty($getRates['ot_eligible'])?$getRates['ot_eligible']:0;
        $arr['ot_rate']      = !empty($getRates['ot_rate'])?$getRates['ot_rate']:0;
        $arr['normal_hours'] = !empty($getRates['normal_hours'])?$getRates['normal_hours']:0;
        echo json_encode($arr);
    }
    public function expense_entry() {
        $this->layout = 'dashboard';
        $this->set('title', 'Expense Input');
        $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
        $user_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 4,
                    )));
        $this->set('user_list', $user_list);
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $exp_list = $this->Expensetype->find('list', array('fields' => array('id', 'type_name')));
        $this->set('exp_list', $exp_list);
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $ds = $this->Expense->getDataSource();
	        $ds->begin();
            $sedata['Expense'] = $this->request->data['Expense'];
            $sedata['Expense']['date']=date("Y-m-d",strtotime($this->request->data['Expense']['date']));
            $sedata['Expense']['entered_by'] = $this->Auth->user('id');
            if(!$this->Expense->save($sedata)){
                $ds->rollback();
                $this->Session->setFlash(__('Unable to save data.'));
                return $this->redirect(array('action' => 'expense_entry'));
            }else{
                $ds->commit();
                $this->Session->setFlash(__('Your data has been saved.'));
            }
        }
	}
    public function edit_expense_entry($exp_id='') {
        $this->layout = 'dashboard';
        $this->set('title', 'Expense Input');
        $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
        $user_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 4,
                    )));
        $this->set('user_list', $user_list);
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $exp_list = $this->Expensetype->find('list', array('fields' => array('id', 'type_name')));
        $this->set('exp_list', $exp_list);
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $ds = $this->Expense->getDataSource();
	        $ds->begin();
            $sedata['Expense'] = $this->request->data['Expense'];
            $sedata['Expense']['date']=date("Y-m-d",strtotime($this->request->data['Expense']['date']));
            $this->Expense->id = $this->request->data['Expense']['id'];
            if(!$this->Expense->save($sedata)){
                $ds->rollback();
                $this->Session->setFlash(__('Unable to save data.'));
                return $this->redirect(array('action' => 'edit_expense_entry',$exp_id));
            }else{
                $ds->commit();
                $this->Session->setFlash(__('Your data has been saved.'));
                return $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
            }
        }
        if($exp_id!=''){
            $this->request->data = $this->Expense->findById($exp_id);
        }else{
            $this->Session->setFlash(__('Please provide id to edit.'));
            return $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
        }
	}
    public function bulk_expense_entry() {
        $this->layout = 'dashboard';
        $this->set('title', 'Bulk Expense Input');
        $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
        $user_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 4,
                    )));
        $this->set('user_list', $user_list);
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $exp_list = $this->Expensetype->find('list', array('fields' => array('id', 'type_name')));
        $this->set('exp_list', $exp_list);
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $arr = $this->request->data;
            $ds = $this->Expentry->getDataSource();
	        $ds->begin();
            $sedata['Expentry']['date_range'] = $arr['date'];
            $sedata['Expentry']['amount'] = $arr['amount'];
            $sedata['Expentry']['expensetype_id'] = $arr['expensetype_id'];
            $sedata['Expentry']['notes'] = $arr['notes'];
            $sedata['Expentry']['users'] = implode(',',$arr['users']);
            $sedata['Expentry']['project_id'] = 1;
            $sedata['Expentry']['created_on'] = date("Y-m-d H:i:s");
            $sedata['Expentry']['modified_on'] = date("Y-m-d H:i:s");
            $sedata['Expentry']['entered_by'] = $this->Auth->user('id');
            //$this->expense_entry_for_bulk($arr,1); die;
            if(!$this->Expentry->save($sedata)){
                $ds->rollback();
                $this->Session->setFlash(__('Unable to save data.'));
                return $this->redirect(array('action' => 'bulk_expense_entry'));
            }else{
                $ds->commit();
                $expentry_id = $this->Expentry->getLastInsertID();
                if($this->expense_entry_for_bulk($arr,$expentry_id)){
                    $this->Session->setFlash(__('Your data has been saved.'));
                }else{
                    $this->Session->setFlash(__('No work days found for those users.'));
                    $this->Expentry->id = $expentry_id;
                    $this->Expentry->delete();
                }
                return $this->redirect(array('action' => 'bulk_expense_entry'));
            }
        }
	}
    public function edit_bulk_expense_entry($expentry_id='') {
        $this->layout = 'dashboard';
        $this->set('title', 'Bulk Expense Input');
        $this->User->virtualFields = array('full_name' =>
                    "CONCAT(User.first_name, ' ', User.last_name)");
        $user_list = $this->User->find('list', array('fields' => array('id', 'full_name'),
                    'conditions' => array(
                    'User.user_type_id' => 4,
                    )));
        $this->set('user_list', $user_list);
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $exp_list = $this->Expensetype->find('list', array('fields' => array('id', 'type_name')));
        $this->set('exp_list', $exp_list);
        if ($this->request->is(array('post', 'put'))) {
            //pr($this->request->data);die;
            $arr = $this->request->data;
            $ds = $this->Expentry->getDataSource();
	        $ds->begin();
            $sedata['Expentry']['date_range'] = $arr['date'];
            $sedata['Expentry']['amount'] = $arr['amount'];
            $sedata['Expentry']['expensetype_id'] = $arr['expensetype_id'];
            $sedata['Expentry']['notes'] = $arr['notes'];
            $sedata['Expentry']['users'] = implode(',',$arr['users']);
            $sedata['Expentry']['project_id'] = 1;
            $sedata['Expentry']['created_on'] = date("Y-m-d H:i:s");
            $sedata['Expentry']['modified_on'] = date("Y-m-d H:i:s");
            //$this->expense_entry_for_bulk($arr,1); die;
            $this->Expentry->id = $arr['expentry_id'];
            if(!$this->Expentry->save($sedata)){
                $ds->rollback();
                $this->Session->setFlash(__('Unable to save data.'));
                return $this->redirect(array('action' => 'bulk_expense_entry'));
            }else{
                $ds->commit();
                $this->Session->setFlash(__('Your data has been Updated.'));
                $this->Expense->deleteAll(array('Expense.expentry_id' => $arr['expentry_id']), false);           
                $this->expense_entry_for_bulk($arr,$expentry_id);
                return $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
            }
        }
        if($expentry_id!=''){
            $expdata = $this->Expentry->findById($expentry_id);
            $this->set('expdata',$expdata);
        }else{
            $this->Session->setFlash(__('Please provide id to edit.'));
            return $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
        }
	}
    public function expense_entry_for_bulk($arr,$ref_id){
        $start_date = $end_date = '';
        $date_arr = explode('-',$arr['date']);
        $start_date = date("Y-m-d",strtotime(trim($date_arr[0])));
        $end_date = date("Y-m-d",strtotime(trim($date_arr[1])));
        $days_count = 0;
        foreach($arr['users'] as $user_id){
            $time_data = $this->Timesheet->find('all',array(
                            'conditions'=>array(
                                'Timesheet.user_id'=>$user_id,
                                'Timesheet.project_id'=>1,
                                'Timesheet.date >='=>$start_date,
                                'Timesheet.date <='=>$end_date,
                            )
                            ));
            $days_count += count($time_data);
        }
        if($days_count>0){
            $amount = round($arr['amount']/$days_count,2);
            foreach($arr['users'] as $user_id){
                $time_data = $this->Timesheet->find('all',array(
                                'conditions'=>array(
                                    'Timesheet.user_id'=>$user_id,
                                    'Timesheet.project_id'=>1,
                                    'Timesheet.date >='=>$start_date,
                                    'Timesheet.date <='=>$end_date,
                                )
                                ));
                if(!empty($time_data)){
                    foreach($time_data as $trow){
                        $save_data = array();
                        $save_data['Expense']['user_id'] = $user_id;
                        $save_data['Expense']['date'] = $trow['Timesheet']['date'];
                        $save_data['Expense']['expensetype_id'] = $arr['expensetype_id'];
                        $save_data['Expense']['amount'] = $amount;
                        $save_data['Expense']['notes'] = 'Amount divided by system';
                        $save_data['Expense']['expentry_id'] = $ref_id;
                        $save_data['Expense']['entered_by'] = $this->Auth->user('id');
                        $this->Expense->clear();
                        $this->Expense->save($save_data);
                    }
                }
            }
            return true;
        }else{
            return false;
        }
    }
    
    public function delete_bulk_expense_entry($id='') {
        if (!$id) {
			$this->Session->setFlash('Please provide an id');
			$this->redirect(array('controller' => 'reports','action' => 'expense_list'));
		}
        $this->Expentry->id = $id;
        if (!$this->Expentry->exists()) {
            $this->Session->setFlash('Invalid id provided');
			$this->redirect(array('controller' => 'reports','action' => 'expense_list'));
        }
        if ($this->Expentry->delete()) {
            $this->Expense->deleteAll(array('Expense.expentry_id' => $id), false);
            $this->Session->setFlash(__('Expenses deleted'));
            $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
        }
        $this->Session->setFlash(__('Expenses was not deleted'));
        $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
    }
    public function delete_expense_entry($id='') {
        if (!$id) {
			$this->Session->setFlash('Please provide an id');
			$this->redirect(array('controller' => 'reports','action' => 'expense_list'));
		}
        $this->Expense->id = $id;
        if (!$this->Expense->exists()) {
            $this->Session->setFlash('Invalid id provided');
			$this->redirect(array('controller' => 'reports','action' => 'expense_list'));
        }
        if ($this->Expense->delete()) {
            $this->Session->setFlash(__('Expenses deleted'));
            $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
        }
        $this->Session->setFlash(__('Expenses was not deleted'));
        $this->redirect(array('controller' => 'reports','action' => 'expense_list'));
    }
    public function ajax_data_verify($id,$status){
        $this->layout ='ajax';
        $this->autoRender =false;
        $ds = $this->Timesheet->getDataSource();
        $ds->begin();
        $data['Timesheet']['verified'] = $status;
        $this->Timesheet->id=$id;
        if ($this->Timesheet->save($data)) {
            $vdata['Verifylog']['user_id'] = $this->Auth->user('id');
            $vdata['Verifylog']['timesheet_id'] = $id;
            $vdata['Verifylog']['verified_on'] = date("Y-m-d H:i:s");
            $vdata['Verifylog']['verify_status'] = $status;
            if (!$this->Verifylog->save($vdata)) {
                $ds->rollback();
            }else{
                $ds->commit();
            }
        }else{
            $ds->rollback();
        }
        $this->Session->delete('current_url');
        $res = $this->Timesheet->findById($id);
        $urow = $this->User->find('first',array('conditions'=>array('User.id'=>$res['Timesheet']['entered_by'])));
        
        echo "<p>Data entered by ".$urow['User']['first_name'].' '.$urow['User']['last_name']." on ".date("M-d-Y",strtotime($res['Timesheet']['created_on']))."</p>";
        $v_res = $this->Verifylog->find('all',array('conditions'=>array('Verifylog.timesheet_id'=>$id),'order'=>array('Verifylog.verified_on asc')));
        foreach($v_res as $vrow){
            echo "<p>Data checked by ".$vrow['User']['fullname']." on ".date("M-d-Y",strtotime($vrow['Verifylog']['verified_on']))."</p>";
        }
        
        if($res['Timesheet']['verified']==1){
            echo '<span class="text-success"><i class="fa fa-check-circle" title="Verified"></i> Verified</span>';
        }elseif($res['Timesheet']['verified']==2){
            if(($res['Timesheet']['entered_by']!=$this->Auth->user('id'))||(strtotime($res['Timesheet']['created_on'])<strtotime("-5 days"))){
                echo "<button type=\"button\" onclick=\"verify(1,".$res['Timesheet']['id'].")\" class=\"btn btn-primary\">Final Verify</button>";
            }else{
                echo '<span class="text-primary"><i class="fa fa-check-circle" title="Partial verified"></i>Partial verified</span>';
            }
        }else{
            if(($res['Timesheet']['entered_by']!=$this->Auth->user('id'))||(strtotime($res['Timesheet']['created_on'])<strtotime("-5 days"))){
               echo "<button type=\"button\" onclick=\"verify(2,".$res['Timesheet']['id'].")\" class=\"btn btn-warning\">Verify This</button>"; 
            }else{
               echo '<span class="text-warning"><i class="fa fa-times-circle" title="Not verified"></i>Not verified</span>'; 
            }
            
        }
    }
    public function verify_entry(){
        $this->layout = 'dashboard';
        $this->set('title', 'Verify Entry');
        $project_list = $this->Project->find('list', array('fields' => array('id', 'project_title')));
        $this->set('project_list', $project_list);
        $date = '';
        $project_id = 1;
        $this->set('date', $date);
        $res = $this->Timesheet->find('all',array('conditions'=>array('Timesheet.project_id'=>$project_id),'order'=>array('Timesheet.date DESC')));
        //pr($res);die;
        $url = Router::url(
                    ['controller' => 'projects', 'action' => 'verify_entry'],
                    true
                );
        $q = '';
        if(!empty($this->request->query['date'])/* || !empty($this->request->query['status'])*/){
           
           $project_id = $this->request->query['project_id'];
           $condi = array('Timesheet.project_id'=>$project_id);
           if(!empty($this->request->query['date'])){
                $date =  date("Y-m-d",strtotime($this->request->query['date']));
                $condi['Timesheet.date'] = $date;
           }
           $res = $this->Timesheet->find('all',array('conditions'=>$condi,'order'=>array('Timesheet.date DESC')));  
           /*
           if(!empty($this->request->query['status'])&&($this->request->query['status']==2)){
                $condi['Timesheet.verified'] = $this->request->query['status'];
                $res = $this->Timesheet->find('all', array(
                    'joins' => array(
                        array(
                            'table' => 'verifylogs',
                            'alias' => 'Verifylog',
                            'type' => 'INNER',
                            'conditions' => array(
                                'Verifylog.timesheet_id = Timesheet.id',
                                'Verifylog.user_id !='=>$this->Auth->user('id'),
                                'or'=>array('Verifylog.verify_status <'=>date("Y-m-d",strtotime("-5 days")))
                            )
                        )
                    ),
                    'conditions' => $condi,
                    'order'=>array('Timesheet.date DESC')
                ));
           }elseif(!empty($this->request->query['status'])&&($this->request->query['status']==0)){
                $condi['Timesheet.verified'] = $this->request->query['status'];
                $condi['or'] = array(
                                    'Timesheet.entered_by !='=>$this->Auth->user('id'),
                                    'Timesheet.created_on <='=>date("Y-m-d",strtotime("-5 days"))
                                    );
                
                $res = $this->Timesheet->find('all',array(
                        'conditions'=>$condi,
                        'order'=>array('Timesheet.date DESC')
                        ));
                
                
           }else{
              $res = $this->Timesheet->find('all',array('conditions'=>$condi,'order'=>array('Timesheet.date DESC')));  
           }
           */
           
           //pr($res);die;
           $this->set('date', date("d-m-Y",strtotime($date)));
           $q = "?project_id=$project_id&date=".date("d-m-Y",strtotime($date));
           
        }
        
        $this->set('res', $res);
        $this->set('project_id', $project_id);
        $this->Session->write('current_url', $url.$q);
    }
}
// End of controller
?>