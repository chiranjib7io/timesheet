
<div class="col-md-offset-3 col-md-6 col-sm-12 col-xs-12">
<div class="box-body" style="margin:60px 0">
<h4> <strong>Create User</strong></h4>
<?php echo $this->Form->create('User'); ?>
                        <div class="form-group">
                          <input type="text" class="form-control" id="first_name" name="data[User][first_name]" placeholder="Enter First Name" required="required">
                        </div>
						
						<div class="form-group">
                          <input type="text" class="form-control" id="last_name" name="data[User][last_name]" placeholder="Enter Last Name" required="required">
                        </div>
                        
                        <div class="form-group">
                          <input type="email" class="form-control" id="email" name="data[User][email]" placeholder="Enter Email" required="required">
                        </div>
                        
                        <div class="form-group">
                          <input type="password" class="form-control" id="password" name="data[User][password]" placeholder="Enter Password" required="required">
                        </div>
                        <!--
                        <div class="form-group">
                          <label for="re-password">Re-enter Password</label>
                          <input type="text" class="form-control" id="email" name="data[User][email]" placeholder="Re-enter Password" required="required">
                        </div>
                        -->
                        <div class="form-group">
								<?php
                                $positionList = array(
                                                'Asbestos Sampling'=>'Asbestos Sampling',
                                                'Environmental Tech'=>'Environmental Tech',
                                                'Asbestos Survey Helper'=>'Asbestos Survey Helper',
                                                'Task Force Leader'=>'Task Force Leader',
                                                'No name'=>'No name'
                                                );
									echo $this->Form->input('User.position', array('type' => 'select', 'options' => $positionList, 'class'=>'form-control', 'label'=>false, 'empty' => 'Select Position','required'=>'required'));
								?>
                        </div>
                        <div class="form-group">
                          <input type="text" class="form-control" id="phone_no" name="data[User][phone_no]" placeholder="Enter Phone no" >
                        </div>
                        
                        <div class="form-group">
                        <label for="UserPosition">Client rate</label>
                          <input type="text" class="form-control" id="client_rate" value="0" name="data[User][client_rate]" placeholder="Enter Client Rate" required="required">
                        </div>
                        
                        <div class="form-group">
                        <label for="UserPosition">Normal rate</label>
                          <input type="text" class="form-control" id="normal_rate" value="0" name="data[User][normal_rate]" placeholder="Enter Normal Rate" required="required">
                        </div>
                        
                        <div class="form-group">
                          <select type="select" class="form-control" id="ot_eligible" name="data[User][ot_eligible]" required="required">
                            <option value="">Overtime Eligible?</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                          </select>
                        </div>
                        
                        <div class="form-group">
                        <label for="UserPosition">Overtime rate</label>
                          <input type="text" class="form-control" id="ot_rate" value="0" name="data[User][ot_rate]" placeholder="Enter Overtime Rate" required="required">
                        </div>
                        <div class="form-group">
                        <label for="UserPosition">Normal hours</label>
                          <input type="text" class="form-control" id="normal_hours" value="0" name="data[User][normal_hours]" placeholder="Enter Normal work hours" required="required">
                        </div>
                        
                        <div class="form-group">
                          <input type="text" class="form-control" id="address" name="data[User][address]" placeholder="Enter Address" >
                        </div>
                        
                        <div class="form-group">
                          <input type="text" class="form-control" id="city" name="data[User][city]" placeholder="Enter City" >
                        </div>
                        
                        <div class="form-group">
                          <input type="text" class="form-control" id="state" name="data[User][state]" placeholder="Enter State" >
                        </div>
						
                       
                        <div class="form-group">
								<?php
									echo $this->Form->input('User.country_id', array('type' => 'select', 'options' => $countryList, 'class'=>'form-control', 'label'=>false, 'value'=>99, 'empty' => 'Select Country'));
								?>
                        </div>
                       
						<input type="hidden" value="4" name="data[User][user_type_id]" />
                        <div class="form-group">
                          <input type="text" class="form-control" id="zip" name="data[User][zip]" placeholder="Enter Zip" >
                        </div>
                         <div class="box-footer" align="center">
                    	<button type="submit" class="btn btn-primary btn-lg">Submit</button>
                    </div>
</form>    
                    </div><!-- /.box-body -->
 </div>                   
                    
                   