<div id="main" class="clearfix" role="main">
		<script type="text/javascript">
            function aa(idshow){
                $("div[id^='div_']").hide();
                $('#'+idshow).toggle(200);
            }
            
            
        </script>
        <div id="add_prop">
            <?php echo $this->Form->create('User',array('class'=>'form-horizontal')); ?>
                <div class="panel panel-primary">
                    <div class="panel-heading text-center"> Edit Profile</div>
                    <span style="color:red"><?php echo $this->Session->flash(); ?></span>				
	

<div class="panel-body"> 
	<?php echo $this->Session->flash('auth'); ?>
	<?php
	echo $this->Form->input('fullname', array('label' => false, 'placeholder' => 'Full Name', 'class' => 'form-control')) .'</br>';
		echo $this->Form->input('username', array('label' => false, 'placeholder' => 'User Name', 'class' => 'form-control')).'</br>';
		echo $this->Form->input('email', array('label' => false, 'placeholder' => 'Email ID', 'class' => 'form-control')).'</br>';
       // echo $this->Form->input('password', array('label' => false, 'placeholder' => 'Password', 'class' => 'form-control')).'</br>';
		//echo $this->Form->input('password_confirm', array('label' => false, 'placeholder' => 'Confirm Password', 'class' => 'form-control', 'maxLength' => 255, 'title' => 'Confirm password', 'type'=>'password')).'</br>';
		//echo $this->Form->input('role', array('label' => false, 'type' => 'hidden', 'class' => 'form-control', 'value'=>'hazmat' ));
		
		echo $this->Form->submit('Update Profile', array('class' => 'btn btn-primary',  'title' => 'Update Profile') );
		?>
</div>

</div>
        </div>
    </div>