<?php
App::uses('AuthComponent', 'Controller/Component');
class Verifylog extends AppModel {
	
	var $belongsTo = array(
        'User' => array(
			'className'    	=> 'User',
			'foriegnKey'	=> 'user_id'
		)
			
	);
}

?>