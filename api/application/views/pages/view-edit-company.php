<div class="form-box">
    <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">Update Institute Details</h3></div>
        <div class="panel-body">
            <?=$response;?>
            <form method="post" class="container-fluid" onsubmit="$('#loader').show();" enctype="multipart/form-data"><br />
                <table width="100%">
                    <tr>
                        <td valign="top" width="200"><label>Institute Logo</label></td>
                        <td>
                            <div class="company-logo">
                            <?php if($company->logo) echo '<img src="'.$company->logo.'">';?>
                            </div>
                            <input type="file" name="logo" onchange="readURL(this);"/><br />
                        </td>
                    </tr>
                    
                    <tr>
                        <td valign="top" width="200"><label>Institute Name <span class="required">*</span></label></td>
                        <td>
                            <div class="form-group<?php if(form_error('name')) echo ' has-error';?>">
                                <input type="text" name="name" class="form-control" value="<?=set_value('name', $company->name);?>"/>
                                <?php echo form_error('name', '<small class="text-danger">', '</small>');?>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td valign="top" width="200"><label>Institute Phone No.</label></td>
                        <td>
                            <div class="form-group<?php if(form_error('phone')) echo ' has-error';?>">
                                <input type="text" name="phone" class="form-control" value="<?=set_value('phone', $company->phone);?>"/>
                                <?php echo form_error('phone', '<small class="text-danger">', '</small>');?>
                            </div>
                        </td>
                    </tr>
                                        
                    <tr>
                        <td valign="top" width="200"><label>Institute Address</label></td>
                        <td>
                            <div class="form-group<?php if(form_error('address')) echo ' has-error';?>">
                                <textarea name="address" class="form-control" rows="4"><?=set_value('address', $company->address);?></textarea>
                                <?php echo form_error('address', '<small class="text-danger">', '</small>');?>
                            </div>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <td valign="top" width="200"><label>Country <span class="required">*</span></label></td>
                        <td>
                            <div class="form-group<?php if(form_error('country')) echo ' has-error';?>">
                                <?php
                                $options = array();
                                $options[''] = '--select--';
                                
                                foreach($countries->country as $country)
                                {
                                    $options[$country->countryName] = $country->countryName;
                                }
                                $sl_val = $this->input->post('country');
                                echo form_dropdown('country', $options, set_value('country', ( ( !empty($sl_val) ) ? "$sl_val" : $company->country_name) ), 'class="form-control"' );  
                                ?>
                                <?php echo form_error('country', '<small class="text-danger">', '</small>');?>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td valign="top" width="200"><label>Expiration Date <span class="required">*</span></label></td>
                        <td>
                            <div class="form-group">
                                <div class="input-group<?php if(form_error('expiration')) echo ' has-error';?>">
                                    <input type="text" name="expiration" class="form-control expiration_date" value="<?=set_value('expiration', date('m/d/Y', strtotime($company->expiration)));?>"/>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                                <?php echo form_error('expiration', '<small class="text-danger">', '</small>');?>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                        <td></td>
                        <td>
                            <div class="row">
                                <div class="col-md-6">
                                <a class="btn btn-default btn-lg btn-block" href="<?=base_url('page/companies')?>">Cancel</a>
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
	autoclose: true,
	startDate:'0d'
});

function readURL(input) {
	if (input.files && input.files[0])
	{
		if(input.files[0].type != 'image/jpeg' && input.files[0].type != 'image/jpg' && input.files[0].type != 'image/png')
		{
			alert('Invalid file type.');
			$('input[name="logo"]').val('');
		}
		else if(input.files[0].size > 5242880)
		{
			alert('File size must be less than 5mb.');
			$('input[name="logo"]').val('');
		}
		else
		{
			var reader = new FileReader();
			reader.onload = function (e) {
				var img = '<img src="'+e.target.result+'">';
				$('.company-logo').html(img);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
}
</script>
