<?php
App::uses('AuthComponent', 'Controller/Component');

class Timesheet extends AppModel {
	
    var $hasMany = array(
        
		'TimesheetDetail' => array(
			'className'    	=> 'TimesheetDetail',
			'foriegnKey'	=> 'timesheet_id'
		)	
	);
    

    
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