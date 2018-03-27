<div class="col-md-12 col-sm-12 col-xs-12">
<div class="users form">
<div>
<div class="col-md-2 col-sm-3 col-xs-12 pull-left">
<h1>Users List</h1>
</div>
<div class="col-md-2 col-sm-3 col-xs-12 pull-right" style="margin-top:20px">				
<?php echo $this->Html->link( "Add A New User",   array('action'=>'add'),array('escape' => false,'class'=>'btn btn-success') ); ?>

</div>
</div>
<table class="table table1 table-hover">
    <thead>
		<tr>
			
			<th><?php echo $this->Paginator->sort('first_name', 'Fullname');?>  </th>
			<th><?php echo $this->Paginator->sort('email', 'E-Mail');?></th>
			<th><?php echo $this->Paginator->sort('created', 'Created');?></th>
			<th><?php echo $this->Paginator->sort('modified','Last Update');?></th>
			<th><?php echo $this->Paginator->sort('position','Position');?></th>
			<th><?php echo $this->Paginator->sort('status','Status');?></th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>						
		<?php $count=0; ?>
		<?php foreach($users as $user): ?>				
		<?php $count ++;?>
		<?php if($count % 2): echo '<tr>'; else: echo '<tr class="zebra">' ?>
		<?php endif; ?>
			
			<td><?php echo $this->Html->link( $user['User']['fullname']  ,   array('action'=>'view', $user['User']['id']),array('escape' => false) );?></td>
			<td><?php echo $user['User']['email']; ?></td>
			<td><?php echo $this->Time->niceShort($user['User']['created']); ?></td>
			<td><?php echo $this->Time->niceShort($user['User']['modified']); ?></td>
			<td><?php echo $user['User']['position']; ?></td>
			<td><?php echo $user['User']['status']; ?></td>
			<td >
			<?php echo $this->Html->link(    "Edit",   array('action'=>'edit', $user['User']['id']) ); ?> | 
			<?php echo $this->Html->link(    "View",   array('action'=>'view', $user['User']['id']) ); ?> | 
			<?php
				if( $user['User']['status'] != 0){ 
					echo $this->Html->link(    "De-Activate", array('action'=>'de_activate', $user['User']['id']));}else{
					echo $this->Html->link(    "Activate", array('action'=>'activate', $user['User']['id']));
					}
            ?>
            
			</td>
		</tr>
		<?php endforeach; ?>
		<?php unset($user); ?>
	</tbody>
</table>
<div class="col-md-6 col-sm-6 col-xs-12">
<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled gap'));?>
<?php echo $this->Paginator->numbers(array(   'class' => 'numbers'     ));?>
<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled gap'));?>
</div>

</div>
</div>
