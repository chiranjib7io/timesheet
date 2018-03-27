<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet" />

<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<!-- Include Required Prerequisites -->

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

<script>
$(document).ready(function() {
   var weekday=new Array(7);
    
    weekday[0]="Monday";
    weekday[1]="Tuesday";
    weekday[2]="Wednesday";
    weekday[3]="Thursday";
    weekday[4]="Friday";
    weekday[5]="Saturday"; 
    weekday[6]="Sunday";
   $("#datepick").datepicker({ 
        dateFormat: 'dd-mm-yy',
        onSelect: function(dateText, inst) {
          var date = $(this).datepicker('getDate');
          var dayOfWeek = weekday[date.getUTCDay()];
          $('#wkday').html(dayOfWeek);
        }
   });
   
   
   
 });
</script>

<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />

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
   <?php
   if($userData['user_type_id']==2 || $userData['user_type_id']==6){ 
    ?>
				<a href="<?php echo $this->Html->url('/projects/user_entry'); ?>"><button type="button" class="btn btn-danger">
            			Record User Time
            		</button>
                </a>
   <?php } ?>
			</div>
		</div>
	</div>
</header>
<div style="color:red; padding:5px; margin-left: 25px;"><?php echo $this->Session->flash(); ?></div>
<div class="container-fluid">
  <h2>Work Time Sheet</h2>
<div class="box box-primary" style="float:left; padding: 20px 0;">

	<div class="col-md-12 col-sm-12 col-xs-12 " >
          
        <?php echo $this->Form->create('Timesheet',array('type'=>'get')); ?>
                <div class=" col-md-12 col-sm-12 col-xs-12">
                    <?php
				echo $this->Form->input('project_id', array('type' => 'hidden','value'=>$project_id));
			?>
					
                    
                    <div class="col-md-4 col-sm-6 col-xs-12">
						<label>
							Filter by Date
						</label>
                        <div class="inputBox">
                        <?php
				echo $this->Form->input('date', array('type' => 'text','id'=>'datepick','value'=>$date,'label'=>false,'div'=>false,'required'=>'required'  ));
			?>
							
						</div>
                        <label id="wkday"></label>
					</div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <button type="submit" class="btn btn-success" style="margin-top:40px">
            			Show records
            		</button>
                    <a href="<?php echo $this->Html->url('/reports/work_time_sheet'); ?>" class="btn btn-danger" style="margin-top:40px">
            			Clear Filter
            		</a>
                    </div>
                </div>
            </form>
                <div class="clearfix"></div>
		
			
			<div class="container">
            
                <?php echo $this->element("work_time_sheet"); ?>
            </div>
            
		
	</div>
</div>
</div>
