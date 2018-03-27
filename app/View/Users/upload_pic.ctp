<div id="main" class="clearfix" role="main">
	<script type="text/javascript">
		function aa(idshow) {
			$("div[id^='div_']").hide();
			$('#' + idshow).toggle(200);
		}
        
	</script>
	<div id="add_prop">
		<?php echo $this->
			Form->create('User', array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')); ?>
			<div class="panel panel-primary">
				<div class="panel-heading text-center">
					Upload Your Image
				</div>
				<span style="color:red">
					<?php echo $this->
						Session->flash(); ?>
				</span>
				<div class="panel-body">
					<?php echo $this->Session->flash('auth'); ?>
                    
                    
                    
						<div class="form-group">
							<label class="col-lg-2 control-label">
								Upload Your Image*
							</label>
							<div class="col-lg-10">
								<input type="file" name="upl" />
							</div>
						</div>
						<?php echo $this->
							Form->input('username', array( 'label' => false, 'class' => 'form-control', 'type' => "hidden")) . '
							</br>
							'; echo $this->Form->input('email', array( 'label' => false, 'class' => 'form-control', 'type' => "hidden")) . '
							</br>
							'; ?>
							<div class="form-group text-center">
								<button class="btn btn-primary" type="submit" placeholder="Submit">
									Upload Image &nbsp; &nbsp;
									<i class="fa fa-chevron-right">
									</i>
								</button>
							</div>
				</div>
			</div>
	</div>
</div>