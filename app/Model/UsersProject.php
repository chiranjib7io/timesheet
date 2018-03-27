<?php
App::uses('AuthComponent', 'Controller/Component');

class UsersProject extends AppModel {
	
    
    
    
    var $belongsTo = array(
		'Project' => array(
			'className'    	=> 'Project',
			'foriegnKey'	=> 'project_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		)
			
	);

}

?>