<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<!-- Include Required Prerequisites -->

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<header class="container-fluid">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<h6>
					Hello, <?php echo $userData['username']; ?>
				</h6>
			</div>
            <div class="col-md-4 col-sm-4 col-xs-12">
            
            </div>
			<div class="col-md-2 col-sm-2 col-xs-12">
			<!--	<a href="<?php echo $this->Html->url('/projects/save'); ?>"><button type="button" class="btn btn-success">
            			Add Project
            		</button>
            -->
			</div>
		</div>
	</div>
</header>
<div style="color:red; padding:5px; margin-left: 25px;"><?php echo $this->Session->flash(); ?></div>
<div class="container-fluid">

	<div class="col-md-12 col-sm-12 col-xs-12 ">
    <h4>Project List:</h4>
    <div class="box box-primary" style="float:left; padding-bottom: 20px;">
		<div class="tableSec table-responsive" id="q_table">
			
			<table class="table table-striped table-hover ">
                <tr>
                    <th>Project Title</th>
                    <th>Address</th>
                    <th>Client</th>
                    <th>Options</th>
                </tr>
                <?php foreach($projects as $row): ?>	
                <tr>
                    <td><?php echo $row['Project']['project_title']; ?></td>
                    <td><?php echo $row['Project']['address']; ?></td>
                    <td><?php echo $row['Project']['client']; ?></td>
                    <td><?php echo $this->Html->link(    "Edit",   array('action'=>'save', $row['Project']['id']) ); ?> |
                        <?php //echo $this->Html->link(    "Delete",   array('action'=>'delete', $row['Project']['id']),['_confirm'=>'Are you sure to delete?'] ); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php unset($projects); ?>
            </table>
            
		</div>
	</div>
</div>
</div>
