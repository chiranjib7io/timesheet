<?php
App::uses('AuthComponent', 'Controller/Component');

class User extends AppModel {
	
	public $avatarUploadDir = 'img/avatars';
    
	
		/**
	 * Before isUniqueUsername
	 * @param array $options
	 * @return boolean
	 */
	function isUniqueUsername($check) {

		$username = $this->find(
			'first',
			array(
				'fields' => array(
					'User.id',
					'User.username'
				),
				'conditions' => array(
					'User.username' => $check['username']
				)
			)
		);

		if(!empty($username)){
			if($this->data[$this->alias]['id'] == $username['User']['id']){
				return true; 
			}else{
				return false; 
			}
		}else{
			return true; 
		}
    }

	/**
	 * Before isUniqueEmail
	 * @param array $options
	 * @return boolean
	 */
	function isUniqueEmail($check) {

		$email = $this->find(
			'first',
			array(
				'fields' => array(
					'User.id'
				),
				'conditions' => array(
					'User.email' => $check['email']
				)
			)
		);

		if(!empty($email)){
			if($this->data[$this->alias]['id'] == $email['User']['id']){
				return true; 
			}else{
				return false; 
			}
		}else{
			return true; 
		}
    }
	
	public function alphaNumericDashUnderscore($check) {
        // $data array is passed using the form field name as the key
        // have to extract the value to make the function generic
        $value = array_values($check);
        $value = $value[0];

        return preg_match('/^[a-zA-Z0-9_ \-]*$/', $value);
    }
	
	public function equaltofield($check,$otherfield) 
    { 
        //get name of field 
        $fname = ''; 
        foreach ($check as $key => $value){ 
            $fname = $key; 
            break; 
        } 
        return $this->data[$this->name][$otherfield] === $this->data[$this->name][$fname]; 
    } 

	/**
	 * Before Save
	 * @param array $options
	 * @return boolean
	 */
	 public function beforeSave($options = array()) {
		// hash our password
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		
		// if we get a new password, hash it
		if (isset($this->data[$this->alias]['password_update'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password_update']);
		}
        
        // a file has been uploaded so grab the filepath
    	if (!empty($this->data[$this->alias]['filepath'])) {
    		$this->data[$this->alias]['id_proof_pic'] = $this->data[$this->alias]['filepath'];
    	}
    
		// fallback to our parent
		return parent::beforeSave($options);
	}
    
    var $virtualFields = array(
    'fullname' => "CONCAT(User.first_name, ' ', User.last_name)"
);
    
    
    var $hasMany = array(
        
			
	);
    
    
    
    var $belongsTo = array(
		'UserType' => array(
			'className'    	=> 'UserType',
			'foriegnKey'	=> 'user_type_id'
		)
        
			
	);
    
    //public function afterFind($results, $primary = false) {
//		if (isset($results[0]['User'])) {
//			foreach ($results as $key => $val) {
//			    if (isset($val['User']['password'])) {
//				unset($results[$key]['User']['password']);
//			    }
//			}
//		}
//		if (isset($results['User']['password'])) {
//			unset($results['User']['password']);
//		}
//		if (isset($results['password'])) {
//			unset($results['password']);
//		}
//		return $results;
//    }







}

?>