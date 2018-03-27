<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 
<!-- Include chart js ----->
<script src="<?php echo $this->webroot; ?>front/js/Chart.js"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet" />
<!-- Theme style -->
<link href="<?php echo $this->webroot; ?>asset/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />


</header>

<div class="container-fluid">
	<div class="col-md-12 col-sm-12 col-xs-12 borderR">
    
        <!--- 1stSection -------->
    
        <div class="row">
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <a href="<?php echo $this->Html->url('/projects/verify_entry'); ?>">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo $not_verified; ?> of <?php echo $total_not_verified; ?></h3>
    
                  <p>Your Not Verified Entry</p>
                </div>
                <div class="icon">
                  
                </div>
                
              </div>
              </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <a href="<?php echo $this->Html->url('/projects/verify_entry'); ?>">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo $partial_verified; ?> of <?php echo $total_partial_verified; ?></h3>
    
                  <p>Your Partial Verify</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                
              </div>
              </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo $verified; ?></h3>
    
                  <p>Verified Entry</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                
              </div>
            </div>
            
      </div>
    
    
    
        <!--- 1st section End ----------->
    
    
    		<!--graph-->
            <div class="box-body" id="sec2">
            <h3>Earning &amp; Costing Data : </h3>
              <div class="chart">
                <canvas id="areaChart" style="height: 300px; width:100%;" width="100%" height="300"></canvas>
                <ul class="iconBx">
                	<li><img src="<?php echo $this->webroot; ?>front/images/blueIcon.jpg"/> Earning</li>
                    <li><img src="<?php echo $this->webroot; ?>front/images/greyIocn.jpg" /> Costing</li>
                </ul>
              </div>
              <!--graph  -->
                
                <script>
                  $(function () {
                    // Get context with jQuery - using jQuery's .get() method.
                    var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
                    // This will get the first returned node in the jQuery collection.
                    var areaChart       = new Chart(areaChartCanvas)
                
                    var areaChartData = {
                      labels  : <?php echo json_encode($times); ?>,
                      datasets: [
                        {
                          label               : 'Earning',
                          fillColor           : 'rgba(60,141,188,0.9)',
                          strokeColor         : 'rgba(60,141,188,0.8)',
                          pointColor          : '#3b8bba',
                          pointStrokeColor    : 'rgba(60,141,188,1)',
                          pointHighlightFill  : '#fff',
                          pointHighlightStroke: 'rgba(60,141,188,1)',
                          data                : <?php echo json_encode($earn); ?>
                        },
                        {
                          label               : 'Costing',
                          fillColor           : 'rgba(210, 214, 222, 1)',
                          strokeColor         : 'rgba(210, 214, 222, 1)',
                          pointColor          : 'rgba(210, 214, 222, 1)',
                          pointStrokeColor    : '#c1c7d1',
                          pointHighlightFill  : '#fff',
                          pointHighlightStroke: 'rgba(220,220,220,1)',
                          data                : <?php echo json_encode($expense); ?>
                        }
                        
                      ]
                    }
                
                    var areaChartOptions = {
                      //Boolean - If we should show the scale at all
                      showScale               : true,
                      //Boolean - Whether grid lines are shown across the chart
                      scaleShowGridLines      : false,
                      //String - Colour of the grid lines
                      scaleGridLineColor      : 'rgba(0,0,0,.05)',
                      //Number - Width of the grid lines
                      scaleGridLineWidth      : 1,
                      //Boolean - Whether to show horizontal lines (except X axis)
                      scaleShowHorizontalLines: true,
                      //Boolean - Whether to show vertical lines (except Y axis)
                      scaleShowVerticalLines  : true,
                      //Boolean - Whether the line is curved between points
                      bezierCurve             : true,
                      //Number - Tension of the bezier curve between points
                      bezierCurveTension      : 0.3,
                      //Boolean - Whether to show a dot for each point
                      pointDot                : false,
                      //Number - Radius of each point dot in pixels
                      pointDotRadius          : 4,
                      //Number - Pixel width of point dot stroke
                      pointDotStrokeWidth     : 1,
                      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                      pointHitDetectionRadius : 20,
                      //Boolean - Whether to show a stroke for datasets
                      datasetStroke           : true,
                      //Number - Pixel width of dataset stroke
                      datasetStrokeWidth      : 2,
                      //Boolean - Whether to fill the dataset with a color
                      datasetFill             : true,
                      //String - A legend template
                      legendTemplate          : '',
                      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                      maintainAspectRatio     : true,
                      //Boolean - whether to make the chart responsive to window resizing
                      responsive              : true
                    }
                
                    //Create the line chart
                    areaChart.Line(areaChartData, areaChartOptions)
                
                  })
                </script>
            </div>
            
            <!--table1-->
            
            <div class="tableSec table-responsive" >
            <h4>Job Position wise summary:</h4>
   <?php
    if(!empty($res_dates)){
        foreach($res_dates as $j=>$sheet){
            if(!empty($sheet)){
    ?>   
            
                <a href="<?php echo $this->Html->url('/reports/work_time_sheet?project_id=1&date='.$sheet['Timesheet']['date']); ?>">
                <button type="button" class="btn btn-primary btn-block"><?php echo date("M-d-Y",strtotime($sheet['Timesheet']['date'])); ?></button>
                </a>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                
                <?php
                
                $res = $this->Slt->get_times_summary_position($sheet['Timesheet']['date']);
                if(!empty($res)){
                    $k=0;
                    foreach($res as $position=>$row){
                        
                        if(!empty($row)){
                ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="collapse-heading-<?php echo $j.$k+1; ?>">
                                <a role="button" class="panel-link" data-toggle="collapse" data-parent="#accordion" href="#collapse-category-<?php echo $j.$k+1; ?>" aria-expanded="false" aria-controls="collapse-category-<?php echo $j.$k+1; ?>">
                                <b><?php echo $position; ?></b>
                            </a>
                            
                            
                            </div>
                            <div class="panel-collapse " id="collapse-category-<?php echo $j.$k+1; ?>" role="tabpanel" aria-labelledby="collapse-heading-<?php echo $j.$k+1; ?>">
                                <div class="panel-body">
                                    <table class="table table-striped ">
                                        <tr>
                                            <th>Name</th>
                                            <th>Work Hours</th>
                                            <th>Normal Cost</th>
                                            <th>OT Hours</th>
                                            <th>OT Cost</th>
                                            <th>Double Cost</th>
                                            <th>Other Cost</th>
                                            <th>Total Cost</th>
                                            <th>Total Earning</th>
                                        </tr>
                            <?php
                            $total_cost = $total_earned = $total_hours = 0;
                            foreach($row as $rowsheet){
                                $res = $this->Slt->calculate_hours_and_cost($rowsheet);
                                //pr($res);
                                $other_cost = $this->Slt->get_expense($rowsheet['User']['id'],$sheet['Timesheet']['date']);
                            
                                $cost = $res['total_cost']+$other_cost;
                                
                                $total_cost += $cost;
                                $total_earned += $res['total_earned'];
                                $total_hours += $res['total_hours'];
                                
                            ?>
                                        <tr>
                                            <td>
                                            <?php
                                            if($rowsheet['Timesheet']['verified']==1){
                                                echo '<i class="fa fa-check-circle text-success" title="Verified"></i>';
                                            }elseif($rowsheet['Timesheet']['verified']==2){
                                                echo '<i class="fa fa-check-circle text-primary" title="Partial verified"></i>';
                                            }else{
                                                echo '<i class="fa fa-times-circle text-warning" title="Not verified"></i>';
                                            }
                                            ?>
                                            <a href="<?php echo $this->Html->url('/users/view/'.$rowsheet['User']['id']); ?>"><?php echo $rowsheet['User']['fullname']; ?></a></td>
                                            <td><?php echo $res['total_hours']; ?></td>
                                            <td><?php echo number_format($res['total_normal_charge'],2); ?></td>
                                            <td><?php echo $res['total_ot_hours']; ?></td>
                                            <td><?php echo number_format($res['total_ot_charge'],2); ?></td>
                                            <td><?php echo number_format($res['total_extra_charge'],2); ?></td>
                                            <td><?php echo number_format($other_cost,2); ?></td>
                                            <td><?php echo number_format($cost,2); ?></td>
                                            <td><?php echo number_format($res['total_earned'],2); ?></td>
                                            
                                        </tr>
                            <?php
                            }
                            ?>
                            
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total Hours: <b><?php echo $total_hours; ?></b></td>
                                            <td>Total Cost: <b><?php echo number_format($total_cost,2); ?></b></td>
                                            <td>Total Earned: <b><?php echo number_format($total_earned,2); ?></b></td>
                                            
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                <?php
                $k++;
                    }
                
                    }
                }
                ?>        
                        
                        
                        
                    </div> <!-- Date end-->
   <?php
            }
        }
     }
     ?>                 
                    
                    
                    
            
			</div>
            <!--table 1-->
            
            
            
            
            
            

   
            
    </div>
    
    
    
    
</div>
<div id="saving_container" style="display:none;">
	<div id="saving" style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
	<img id="saving_animation" src="<?php echo $this->webroot; ?>upload/blue-loading.gif" alt="saving" style="z-index:100001; margin-left:-32px; margin-top:-32px; position:fixed; left:50%; top:50%"/>
	<div id="saving_text" style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001">Please wait...</div>
</div>

<script type="text/javascript">
$.ajaxPrefilter(function( options, originalOptions, jqXHR ) { options.async = true; });
function show_animation()
{
	$('#saving_container').css('display', 'block');
	$('#saving').css('opacity', '.8');
}

function hide_animation()
{
	$('#saving_container').fadeOut();
	
}

function load_summary_by_position(d){
    $.post("<?php echo $this->Html->url('/dashboards/ajax_times_summary_position/'); ?>"+d, function(data, status){
            $('.panel-group').html(data);
        });
}

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
            show_animation();
          load_summary_by_position(dateText);
          setTimeout(hide_animation, 1000);
        }
   });
   
   
   
 });

</script>

<!--dropdown-->
<script src="<?php echo $this->webroot; ?>front/js/jquery.nice-select.min.js"></script>
  <script>
    $(document).ready(function() {
      $('select:not(.ignore)').niceSelect();      
      //FastClick.attach(document.body);
    });    
  </script>
