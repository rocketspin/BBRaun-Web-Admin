<div class="form-box">
    <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">Update User Info</h3></div>
        <div class="panel-body">
            <?=$response;?>
            <form method="post" class="container-fluid" onsubmit="$('#loader').show()"><br />
            <table width="100%">
                <tr>
                    <td valign="top" width="200"><label>First Name <span class="required">*</span></label></td>
                    <td>
                        <div class="form-group<?php if(form_error('first_name')) echo ' has-error';?>">
                            <input type="text" name="first_name" class="form-control" value="<?=set_value('first_name', $userdata->first_name);?>"/>
                            <?php echo form_error('first_name', '<small class="text-danger">', '</small>');?>
                        </div>
                    </td>
                </tr>
                                    
                <tr>
                    <td valign="top" width="200"><label>Last Name <span class="required">*</span></label></td>
                    <td>
                        <div class="form-group<?php if(form_error('last_name')) echo ' has-error';?>">
                            <input type="text" name="last_name" class="form-control" value="<?=set_value('last_name', $userdata->last_name);?>"/>
                            <?php echo form_error('last_name', '<small class="text-danger">', '</small>');?>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" width="200"><label>Email Address <span class="required">*</span></label></td>
                    <td>
                        <div class="form-group<?php if(form_error('email_address')) echo ' has-error';?>">
                            <input type="text" name="email_address" class="form-control" value="<?=set_value('email_address', $userdata->email);?>"/>
                            <?php echo form_error('email_address', '<small class="text-danger">', '</small>');?>
                        </div>
                    </td>
                </tr>
                
                <tr>
                        <td valign="top" width="200"><label>Company <span class="required">*</span></label></td>
                        <td>
                            <div class="form-group<?php if(form_error('company')) echo ' has-error';?>">
                                <?php
                                $options = array();
                                $options[''] = '--select--';
                                
                                if($companies){
                                    foreach($companies as $company)
                                    {
                                        $options[$company->id] = $company->name;
                                    }
                                }
                                $sl_val = $this->input->post('company');
                                echo form_dropdown('company', $options, set_value('company', ( ( !empty($sl_val) ) ? "$sl_val" : $userdata->cid) ), 'class="form-control"' );  
                                ?>
                                <?php echo form_error('company', '<small class="text-danger">', '</small>');?>
                            </div>
                        </td>
                    </tr>
                
                <tr>
                    <td valign="top" width="200"><label>Contact No</label></td>
                    <td>
                        <div class="form-group<?php if(form_error('contact_no')) echo ' has-error';?>">
                            <input type="text" name="contact_no" class="form-control" value="<?=set_value('contact_no', $userdata->phone);?>"/>
                            <?php echo form_error('contact_no', '<small class="text-danger">', '</small>');?>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" width="200"><label>Address</label></td>
                    <td>
                        <div class="form-group<?php if(form_error('address')) echo ' has-error';?>">
                            <textarea name="address" class="form-control" rows="4"><?=set_value('address', $userdata->address);?></textarea>
                            <?php echo form_error('address', '<small class="text-danger">', '</small>');?>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td valign="top" width="200"><label>User Role <span class="required">*</span></label></td>
                    <td>
                        <div class="form-group<?php if(form_error('role')) echo ' has-error';?>">
                            <?php
                            $options = array();
                            $options[''] = '--select--';
                            
                            foreach($this->ion_auth->groups()->result() as $role)
                            {
                                $options[$role->id] = $role->name;
                            }
                            $sl_val = $this->input->post('role');
                            echo form_dropdown('role', $options, set_value('role', ( ( !empty($sl_val) ) ? "$sl_val" : $usergroup->id)), 'class="form-control"' );  
                            ?>
                            <?php echo form_error('role', '<small class="text-danger">', '</small>');?>
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td></td>
                    <td>
                        <div class="row">
                            <div class="col-md-6">
                            <a class="btn btn-default btn-lg btn-block" href="<?=base_url()?>">Cancel</a>
                            </div>
                            <div class="col-md-6">
                            <input class="btn btn-primary pull-right btn-lg btn-block" type="submit" value="Save Changes"/>
                            </div>
                        </div>
                    
                    </td>
                </tr>
            </table><br />
        </form>
        </div>
    </div>
</div>


<script type="text/javascript">
	$('.expiration_date').datepicker({
		startView: 1,
		autoclose: true
    });
</script>
