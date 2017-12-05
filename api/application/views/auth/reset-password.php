<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password | B. Braun Hand Hygiene</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?=base_url('assets/css/font-awesome.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css')?>"/>
        <link rel="stylesheet" href="<?=base_url('assets/css/login.css')?>"/>
    </head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-7 col-md-5 col-md-offset-3">
            	<h1 class="text-center login-title">Password Reset</h1>
                <div class="account-wall forgot-password">
                    <div class="container-fluid">
						<?php echo form_open('auth/reset_password/' . $code);?>
                            <?php echo form_input($user_id);?>
                            
                            <?php echo form_hidden($csrf);?>
                            <div class="form-group">
                                <label>New Password</label>
                                <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
                                <?php echo form_input($new_password);?>
                            </div>
                            
                            <div class="form-group">
                                 <label>Retype New Password</label>
                                <?php echo form_input($new_password_confirm);?>
                            </div>
                            
                            <input type="submit" value="Reset Password" class="pull-right btn btn-primary">
                            <div class="clearfix"></div>
                        <?php echo form_close();?>
                    </div>
                </div><br>
                <?php if(isset($response)) echo $response;?>
            </div>
        </div>
    </div>
</body>
</html>
