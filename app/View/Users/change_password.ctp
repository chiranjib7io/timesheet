<script type="text/javascript">
    $(function () {
        $("#btnSubmit").click(function () {
            var password = $("#txtnewPassword").val();
            var confirmPassword = $("#txtConfirmPassword").val();
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        });
    });
</script>           

		   <!-- Content Header (Page header) -->
            <section class="content-header">
              <h1>
                Change Password
              </h1>
              <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Change Password</li>
              </ol>
            </section>
            
            <!-- Main content -->
            <section class="content">
              <div class="row">
              	<div class="col-xs-12">
                	<div class="box box-danger col-xs-12" style="padding-top:20px; padding-bottom:20px;">
					<!--
                        <div class="box-header">
                          <h3 class="box-title">Change Password</h3>
                        </div>
						-->
						<?php echo $this->Form->create('User',array('class'=>'')); ?>
						
						<span style="color:red"><?php echo $this->Session->flash(); ?></span>
						
                            <div class="box-body col-md-4 col-sm-12">
                            	<div class="form-group">
                                    <label>Old Password</label>
                                    <input type="text"  class="form-control"  placeholder="Old Password" id="txtPassword" name="txtPassword" required="required">
                                </div><!-- /.form group -->
                            </div>
                            <div class="box-body col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="text"  class="form-control"  placeholder="New Password" id="txtnewPassword" name="txtnewPassword" required="required">
                                </div><!-- /.form group -->
                            </div>
                            <div class="box-body col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>Retype New Password</label>
                                    <input type="text"  class="form-control"  placeholder="Retype New Password" id="txtConfirmPassword" name="txtConfirmPassword" required="required">
                                </div><!-- /.form group -->
                            </div>
                        <div class="box-footer col-xs-12" style="text-align:right;">
                            <button type="submit" class="btn btn-primary btn-lg" id="btnSubmit">Submit</button>
                        </div>
						</form>
                      </div><!-- /.box -->
                </div><!-- /.col -->
              </div><!-- /.row -->
            
            </section><!-- /.content -->