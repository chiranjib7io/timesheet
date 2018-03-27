<?php
App::uses('AuthComponent', 'Controller/Component');

class TimesheetDetail extends AppModel { 
    
    var $belongsTo = array(
		'Timesheet' => array(
			'className'    	=> 'Timesheet',
			'foriegnKey'	=> 'timesheet_id'
		)
			
	);

}

?>