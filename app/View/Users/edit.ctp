<style type="text/css">
.box.box-primary { border-top-color: #3c8dbc; margin-top:10px;}
.box {
    position: relative;
    border-radius: 3px;
    background: #ffffff;
    border-top: 3px solid #d2d6de;
    margin-bottom: 20px;
    width: 100%;
    box-shadow: 0 1px 1px rgba(0,0,0,0.1); padding:10px 20px;}
h1 {margin:15px 0 0 0; font-size: 24px!important;}
input{width: 100%; margin-bottom: 15px;}
.form-submit{width: 20%; margin-top: 15px; background-color:#075878; color:#fff; border-radius:4px; outline:none; border:none;padding: 12px 0;
height: 42px; font-size: 15px;}
.cancelBtn, .cancelBtn:hover{ color: #fff!important; background-color: #269abc; padding:10px 30px; margin-top:-2px}

</style>


<div class="col-md-offset-3 col-md-6 col-sm-12 col-xs-12">
<h1><?php echo __('Edit User'); ?></h1>
<!-- app/View/Users/add.ctp -->

<div class="box box-primary" style="float:left; padding-bottom: 20px;">

<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <label for="UserName"><?php echo $this->data['User']['fullname']; ?></label>
        <?php 
		echo $this->Form->hidden('id', array('value' => $this->data['User']['id']));
		
        
		echo $this->Form->input('email',array( 'readonly' => 'readonly', 'label' => 'Login email cannot be changed!'));
        echo $this->Form->input('password_update', array( 'label' => 'New Password (leave empty if you do not want to change)', 'maxLength' => 255, 'type'=>'password','required' => 0));
		echo $this->Form->input('password_confirm_update', array('label' => 'Confirm New Password *', 'maxLength' => 255, 'title' => 'Confirm New password', 'type'=>'password','required' => 0));
		
        
        echo $this->Form->input('first_name',array( 'label' => 'First Name','required'=>'required'));
        
        echo $this->Form->input('last_name',array( 'label' => 'Last Name','required'=>'required'));
        
        echo $this->Form->input('phone_no',array( 'label' => 'Phone no'));
        
        $positionList = array(
                                                'Asbestos Sampling'=>'Asbestos Sampling',
                                                'Environmental Tech'=>'Environmental Tech',
                                                'Asbestos Survey Helper'=>'Asbestos Survey Helper',
                                                'Task Force Leader'=>'Task Force Leader',
                                                'No name'=>'No name'
                                                );
		echo $this->Form->input('position', array(
            'options' => $positionList
        ));
        
        
        
        echo $this->Form->input('client_rate',array( 'label' => 'Client Rate'));
        
        echo $this->Form->input('normal_rate',array( 'label' => 'Normal Rate'));
        
        echo $this->Form->input('normal_hours',array( 'label' => 'Normal hours'));
 ?>
        <div class="form-group">
        <label for="UserPosition">Overtime Eligible?</label>
          <select type="select" class="form-control" id="ot_eligible" name="data[User][ot_eligible]" required="required">
            <option value="">Overtime Eligible?</option>
            <option <?php echo ($this->request->data['User']['ot_eligible']==1)?'selected':''; ?> value="1">Yes</option>
            <option <?php echo ($this->request->data['User']['ot_eligible']==0)?'selected':''; ?> value="0">No</option>
          </select>
        </div>
 
 <?php       
        echo $this->Form->input('ot_rate',array( 'label' => 'Overtime rate'));
?>        
        
       
		<div class="submit"><input class="form-submit" title="Click here to add the user" type="submit" value="Save">
         <a href="<?php echo $this->Html->url('/users/user_list'); ?>"><button type="button" class="btn cancelBtn">
			Cancel
		</button>
        <a href="<?php echo $this->Html->url('/users/delete/'.$this->request->data['User']['id']); ?>" onclick="return confirm('Are you sure to delete?')"><button type="button" class="btn btn-danger" style="margin-top:27px">
			Delete User
		</button>
        </a>
        </div>

       
    </fieldset>
<?php echo $this->Form->end(); ?>
</div>
</div>
</div>



<ol class="breadcrumb">
            <li><?php 
echo $this->Html->link( "Return to Dashboard",   array('action'=>'index') ); 
?>
<br/>
</li>
            <li class="active"><?php 
echo $this->Html->link( "Logout",   array('action'=>'logout') ); 
?></li>
          </ol>