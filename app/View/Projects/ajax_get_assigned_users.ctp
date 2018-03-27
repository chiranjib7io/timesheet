<?php
echo $this->Form->input('Timesheet.user_id', array('type' => 'select','options' => $user_list,'empty'=>'Select user', 'label'=>false,'div'=>false,'required'=>'required'  ));
			?>