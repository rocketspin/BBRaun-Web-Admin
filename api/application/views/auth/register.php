<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register | Access Unli Strategies</title>
        <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico')?>"/>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?=base_url('assets/css/font-awesome.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css')?>"/>
        <link rel="stylesheet" href="<?=base_url('assets/css/login.css')?>"/>
    </head>
<body>
    <div class="container">
        <div class="row">
        
            <div class="col-sm-6 col-md-6 col-md-offset-3">
            	<h1 class="text-center login-title">Register Your Account</h1>
                <?php if(isset($response)) echo $response;?>
                <div class="account-wall">
                    <form class="form-register" method="post" novalidate>
                        <div class="form-group<?php if(form_error('username')) echo ' has-error';?>">
                            <label>Member's ID</label>
                            <input type="text" name="username" class="form-control input-lg" value="<?php echo set_value('username', $this->input->get('mid'));?>">
                            <?php echo form_error('username', '<small class="text-danger">', '</small>');?>
                        </div>
                        
                        <div class="form-group<?php if(form_error('first_name')) echo ' has-error';?>">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control input-lg" value="<?php echo set_value('first_name');?>">
                            <?php echo form_error('first_name', '<small class="text-danger">', '</small>');?>
                        </div>
                        
                        <div class="form-group<?php if(form_error('last_name')) echo ' has-error';?>">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control input-lg" value="<?php echo set_value('last_name');?>">
                            <?php echo form_error('last_name', '<small class="text-danger">', '</small>');?>
                        </div>
                        
                        <div class="form-group<?php if(form_error('email_address')) echo ' has-error';?>">
                            <label>Email Address</label>
                            <input type="text" name="email_address" class="form-control input-lg" value="<?php echo set_value('email_address');?>">
                            <?php echo form_error('email_address', '<small class="text-danger">', '</small>');?>
                        </div>
                        
                        <div class="form-group<?php if(form_error('password')) echo ' has-error';?>">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control input-lg">
                            <?php echo form_error('password', '<small class="text-danger">', '</small>');?>
                        </div>
                        
                        <div class="form-group<?php if(form_error('retype_password')) echo ' has-error';?>">
                            <label>Retype Password</label>
                            <input type="password" name="retype_password" class="form-control input-lg">
                            <?php echo form_error('retype_password', '<small class="text-danger">', '</small>');?>
                        </div>
                        
                        <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login" name="login">Register</button>
                    </form>
                </div><br>
                <center><a href="<?=base_url('auth')?>">Back to login &raquo;</a></center><br><br>
            </div>
        </div>
    </div>
</body>
</html>
