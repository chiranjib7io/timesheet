<?php
/*
Users Controller perform the login and loagout functionalities
with Dashboard of all type of users.
Also contain the Forgot password, new user registration and change password.
*/
App::uses('CakeEmail', 'Network/Email');
class UsersController extends AppController {
	// List of models which are used in the organization controller 
	var $uses = array('User');
	// Login with email id instant of username in auth controller
	public $components = array(
    'Auth' => array(
        'authenticate' => array(
            'Form' => array(
                'fields' => array('username' => 'email')
            )
        )
    ),'RequestHandler'
	);
	// Pagination in Cakephp
	public $paginate = array(
        'limit' => 25,
        'conditions' => array('status' => '1'),
    	'order' => array('User.username' => 'asc' ) 
    );
	// Tell auth controller which are the functions can use without login
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login','add','forgot'); 
    }
	// Dashboard Function Start
    public function index() {  
		$this->layout = 'dashboard';
		$this->set('title', 'Dashbord');
		// Call Values for the Dashboard from the session
		$user_id=$this->Auth->user('id'); // Login user's user id
		$user_type_id=$this->Auth->user('user_type_id'); // Login user's user type
    }
	// Dashboard Function End
    // User login function start
	public function login() {
		//if already logged-in, redirect
		if($this->Session->check('Auth.User')){	
            $this->redirect(array('action' => 'index'));	
		}
		$this->layout = 'login';
		// if we get the post information, try to authenticate
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$data['LogRecord']['device_id']=$this->request->clientIp();
				$data['LogRecord']['user_id']=$this->Auth->user('id');
				$data['LogRecord']['device_type']=$this->detectDevice();
				$data['LogRecord']['start_time']=date("Y-m-d H:i:s");
				$this->LogRecord->save($data);
				$last_log_record=$this->LogRecord->getLastInsertId();
				$this->Session->write('LogRecord.id', $last_log_record);             
				$this->Session->setFlash(__('Welcome, '. $this->Auth->user('username')));
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash(__('Invalid username or password'));
			}
		} 
	}
	 // User login function end 
    //user logout function start
	public function logout() {
		$end_time=date("Y-m-d h:i:sa");
		$logrecordid = $this->Session->read('LogRecord.id');
        $data['LogRecord']['id']=$logrecordid;
		$data['LogRecord']['end_time']=date("Y-m-d H:i:s");
        $data['LogRecord']['log_out']=1;		
		$this->LogRecord->save($data);
		$this->Session->destroy();
		$this->redirect($this->Auth->logout());
	}
	//user logout function end
	//user list function start
	public function user_list() {
	   $this->layout = 'dashboard';
		$this->paginate = array(
			'limit' => 30,
            'conditions' => array('User.user_type_id'=>4),
			'order' => array('User.username' => 'asc' )
		);
		$users = $this->paginate('User');
		$this->set(compact('users'));
    }
	//user list function end
	// User Registration function start
    public function add() { 
		$countrylist = $this->Country->find('list', array('fields' => array('id', 'name')));
		$this->set('countryList', $countrylist); // List of Countries
		$this->layout = 'dashboard';
        if ($this->request->is('post')) {
            //pr($this->request->data);die;
			$this->request->data['User']['created'] = date("Y-m-d");
			$this->request->data['User']['modified'] = date("Y-m-d");
            $this->request->data['User']['entered_by'] = $this->Auth->user('id');
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been created'));
                $this->redirect(array('action' => 'user_list'));
			} else {
				$this->Session->setFlash(__('The user could not be created. Please, try again.'));
			}	
        }
    }
	// User Registration function end
    //user edit function start
    public function edit($id = null) {
        $this->layout = 'dashboard';
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'user_list'));
		}
		$user = $this->User->findById($id);
		if (!$user) {
			$this->Session->setFlash('Invalid User ID Provided');
			$this->redirect(array('action'=>'user_list'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $id;
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been updated'));
				$this->redirect(array('action' => 'user_list'));
			}else{
				$this->Session->setFlash(__('Unable to update your user.'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $user;
		}
    }
	//user edit function end
    //User delete function start
    public function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'user_list'));
		}
        $this->User->id = $id;
        if (!$this->User->exists()) {
            $this->Session->setFlash('Invalid user id provided');
			$this->redirect(array('action'=>'user_list'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'user_list'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        $this->redirect(array('action' => 'user_list'));
    }
	//User delete function end
    //User delete function start
    public function activate($id = null) {
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'user_list'));
		}
        $this->User->id = $id;
        if (!$this->User->exists()) {
            $this->Session->setFlash('Invalid user id provided');
			$this->redirect(array('action'=>'user_list'));
        }
        if ($this->User->saveField('status', 1)) {
            $this->Session->setFlash(__('User activated'));
            $this->redirect(array('action' => 'user_list'));
        }
        $this->Session->setFlash(__('User was not activated'));
        $this->redirect(array('action' => 'user_list'));
    }
	//User delete function end
    
    //User delete function start
    public function de_activate($id = null) {
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'user_list'));
		}
        $this->User->id = $id;
        if (!$this->User->exists()) {
            $this->Session->setFlash('Invalid user id provided');
			$this->redirect(array('action'=>'user_list'));
        }
        if ($this->User->saveField('status', 0)) {
            $this->Session->setFlash(__('User de-activated'));
            $this->redirect(array('action' => 'user_list'));
        }
        $this->Session->setFlash(__('User was not de-activated'));
        $this->redirect(array('action' => 'user_list'));
    }
	//User delete function end
	// Site Admin Login Function start
	public function admin_login() {
		//if already logged-in, redirect
		if($this->Session->check('Auth.User')){
			$this->redirect(array('action' => 'index'));		
		}
		// if we get the post information, try to authenticate
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->Session->setFlash(__('Welcome, '. $this->Auth->user('username')));
				$this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Session->setFlash(__('Invalid username or password'));
			}
		} 
	}
	// Site Admin Login Function end
	//User forgot password function start
	public function forgot() {
		$this->layout = 'forgot';
		// if we get the post information, try to authenticate
		if ($this->request->is('post')) {
			$emailCount = $this->User->find('first', array(
				'conditions' => array('User.email' =>$this->request->data['User']['email'])
			));
			if(!empty($emailCount)){
				// Update Password Field
				$id=$emailCount['User']['id'];
				$this->User->id=$id;
				$this->User->saveField("password","123456");
				$dbEmail=$emailCount['User']['email'];
				// Email Send
				$this->Email->from = 'no-reply@microfinanceapp.com';
				$this->Email->to = $dbEmail;
				$this->set('heading', 'You Login Password');
				$this->set('content', "Your Updated Password is: 123456");
				$this->Email->subject = 'Forgot Password';
				$this->Email->layout = 'report_msg';
				$this->Email->template = 'text_template';
				$this->Email->additionalParams="-f$dbEmail";
				$this->Email->sendAs = 'html';
				try {
					if ($this->Email->send()) {
						$this->Session->setFlash(__('Password Send to your Email ID'));
						return true;
					} else {
						return false;
					}
				}
				catch (phpmailerException $e) {
					return false;
				}
				catch (exception $e) {
					return false;
				}
			} else {
				$this->Session->setFlash(__('Invalid Email ID'));
			}
		} 
	}
	//User forgot password function end
	// User Edit Function start
	public function user_edit() {
		$this->set('title', 'Edit Profile');  // This is used for Title for every page
		$this->layout = 'panel_layout';
		$id= $this->Session->read('Auth.User.id');
		if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'index'));
		}
		$user = $this->User->findById($id);
		if (!$user) {
			$this->Session->setFlash('Invalid User ID Provided');
			$this->redirect(array('action'=>'index'));
		}
		$this->set('data',$user);
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->User->id = $id;
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been updated'));
				$this->redirect(array('action' => 'edit', $id));
			}else{
				$this->Session->setFlash(__('Unable to update your user.'));
			}
		}
		if (!$this->request->data) {
			$this->request->data = $user;
		}
    }
	// User Edit Function end
	// User Picture Upload function start
	public function upload_pic() {
		$this->set('title', 'Upload Profile Picture');  // This is used for Title for every page
		$this->layout = 'panel_layout';
		$id= $this->Session->read('Auth.User.id');
		 if (!$id) {
			$this->Session->setFlash('Please provide a user id');
			$this->redirect(array('action'=>'index'));
		}
		$user = $this->User->findById($id);
		if (!$user) {
			$this->Session->setFlash('Invalid User ID Provided');
			$this->redirect(array('action'=>'index'));
		}
		$this->set('data',$user);
		if ($this->request->is('post') || $this->request->is('put')) {
			$file_name=$this->Session->read('Auth.User.id').'_profilepic_'.time().'_'.$_FILES['upl']['name'];
			$filename = WWW_ROOT."upload/profilePic/".$file_name; 
			if(move_uploaded_file($_FILES['upl']['tmp_name'],$filename )){
				$this->request->data['User']['image']=$file_name;
				$this->request->data['User']['modified']=date("Y-m-d");
				$this->Session->write('Auth.User.image', $file_name);
				$this->request->data['User']['id']=$this->Session->read('Auth.User.id');
				if ($this->User->save($this->request->data)) {
					 /* save message to session */
					$this->Session->setFlash('Picture uploaded successfuly.');
					/* redirect */
					$this->redirect(array('action' => 'upload_pic'));
				} else {
					/* save message to session */
					$this->Session->setFlash('There was a problem uploading image. Please try again.');
				}
			}
		}
		if (!$this->request->data) {
			$this->request->data = $user;
		}
    }
	// User Picture Upload function end	
	// Create Employee function start
	public function employee($emp_id=''){
		$this->layout = 'panel_layout';
		$this->set('title', 'Create Employee');
		$ut_list = $this->UserType->find('list', array('fields' => array('id', 'user_type'), 'conditions'=> array('id !='=>1)));
		$this->set('ut_list', $ut_list);
		$identity_type=$this->id_proof_name();
		$this->set('identity_type', $identity_type);
		$countrylist = $this->Country->find('list', array('fields' => array('id', 'name')));
		$this->set('countryList', $countrylist);
        $this->set('emp_id',$emp_id);
        if ($emp_id != '') {
            $emp_data = $this->User->findById($emp_id);
            if (!$this->request->data) {
                $this->request->data = $emp_data;
                unset($this->request->data['User']['password']);
            }
        }
		if ($this->request->is('post')) {
		  //pr($this->request->data);die;
          $is_exist=0;
          if ($emp_id == '') {
            $is_exist = $this->User->find('count',array('conditions'=>array('User.email'=>$this->request->data['User']['email'])));
          }
          if($is_exist==0){
            
            if ($emp_id != '') {
                $this->request->data['User']['modified'] = date("Y-m-d H:i:s");
                if(empty($this->request->data['User']['password'])){
                    unset($this->request->data['User']['password']);
                }
                $this->User->id = $emp_id;
            } else {
                $this->request->data['User']['organization_id'] = $this->Auth->user('organization_id');
                $this->request->data['User']['created'] = date("Y-m-d H:i:s");
                $this->User->create();
            }
            //prepare idproof data
            if(!empty($this->request->data['idproof'])){
                $idproof = $this->request->data['idproof'];
                $idproof_arr = array();
                foreach($idproof['id_proof_no'] as $k=>$v){
                    $idproof_arr[$k]['id_proof_no'] = $v;
                    $idproof_arr[$k]['id_proof_type'] = $idproof['id_proof_type'][$k];
                }
                $this->request->data['User']['id_proof']=json_encode($idproof_arr);
            }
            // end idproof data prepare
			$this->request->data['User']['username'] = $this->request->data['User']['first_name'];
			if ($this->User->save($this->request->data)) {
                if ($emp_id == ''){
    				$last_insert_user=$this->User->getLastInsertId();
                    $username=$this->request->data['User']['username'];
                    $password = $this->request->data['User']['password'];
        			$dbEmail=$this->request->data['User']['email'];
                    $this->Session->setFlash(__('The user has been Created'));
    				//$this->request->data['Idproof']['user_id'] = $last_insert_user;
    				//$this->Idproof->save($this->request->data);
    				// Email Send
    				$this->Email->from = 'no-reply@microfinanceapp.com';
    				$this->Email->to = $dbEmail;
    				$this->set('heading', 'Login Details');
    				$this->set('content', "Your email id is: $dbEmail and password is: $password");
    				$this->Email->subject = 'Your Username and Password';
    				$this->Email->layout = 'report_msg';
    				$this->Email->template = 'text_template';
    				$this->Email->additionalParams="-f$dbEmail";
    				$this->Email->sendAs = 'html';
    				try {
    					if ($this->Email->send()) {
    						$this->redirect(array('action' => 'employee'));
    						return true;
    					} else {
    						return false;
    					}
    				}
    				catch (phpmailerException $e) {
    					return false;
    				}
    				catch (exception $e) {
    					return false;
    				}
               }else{
                    $this->Session->setFlash(__('The user has been Saved'));
               }
			} else {
				$this->Session->setFlash(__('The user could not be created. Please, try again.'));
			}
            } //is exist if end	
            else{
                $this->Session->setFlash(__('The user with same email already exist.'));
            }
		}
	}
	// Create Employee function end
    public function ajax_idproof_row(){
        $this->layout = 'ajax';
        $identity_type=$this->id_proof_name();
		$this->set('identity_type', $identity_type);
    }
	// Change Password function START
	public function change_password(){
		$this->layout = 'panel_layout';
        $this->set('title', 'Change Password');
		if ($this->request->is('post')) {
			$new_password=$this->request->data['txtnewPassword'];
			$data['User']['id']=$this->Auth->user('id');
			$data['User']['password']=$new_password;
			if ($this->User->save($data)) {
				$this->Session->setFlash(__('The user password has been change'));
				$this->redirect(array('action' => 'change_password'));
			} else {
				$this->Session->setFlash(__('The user password uable to change. Please, try again.'));
			}
		}
	}
	// Change Password function END
    // Show all the Region lists function start
    public function employee_list(){
        $this->layout = 'panel_layout';
        $this->set('title', 'Employee List');
        $employee_data = $this->User->find('all',array('conditions'=>array('User.user_type_id !='=>1,'User.user_type_id !='=>2,'User.status'=>1)));
        //pr($employee_data);die;
        $this->set('employee_data', $employee_data);
    }
    public function view($id = null) {
    	$this->layout = 'dashboard';
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
        $project_id = 1;
		$date = date('Y-m-d');
        $this->set('date', '');
        $res = $this->Timesheet->find('all',array('conditions'=>array('Timesheet.project_id'=>$project_id,'Timesheet.user_id'=>$id),'order'=>array('Timesheet.date DESC')));
        //pr($res);die;
        if(!empty($this->request->query['date'])) {
           $date =  $this->Micro->ustime_to_sql($this->request->query['date']);
           $project_id = $this->request->query['project_id'];
           $res = $this->Timesheet->find('all',array('conditions'=>array('Timesheet.date'=>$date,'Timesheet.project_id'=>$project_id,'Timesheet.user_id'=>$id),'order'=>array('Timesheet.date DESC')));
           $this->set('date', date('m-d-Y',strtotime($date)));
        }
        $this->set('res', $res);
        $this->set('project_id', $project_id);
	}
}
// End of User controller
?>