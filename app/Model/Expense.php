<?php
App::uses('AuthComponent', 'Controller/Component');

class Expense extends AppModel {
	
        
    var $belongsTo = array(
		'Project' => array(
			'className'    	=> 'Project',
			'foriegnKey'	=> 'project_id'
		),
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		),
        'Expensetype' => array(
			'className'    	=> 'Expensetype',
			'foriegnKey'	=> 'expensetype_id'
		)
			
	);
    
    

}

?>