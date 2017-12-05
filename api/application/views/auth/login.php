<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login | B. Braun Hand Hygiene</title>
        <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico')?>"/>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?=base_url('assets/css/font-awesome.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css')?>"/>
        <link rel="stylesheet" href="<?=base_url('assets/css/login.css')?>"/>
    </head>
<body>
    <div class="container">
        <div class="row">
        
            <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            	<h1 class="text-center login-title text-center">
                	<img src="<?=base_url('assets/img/logo.png')?>" alt="B-Braun Hand Hygiene" height="60"/>
                </h1>
                <div class="account-wall">
                    <form class="form-signin" method="post" novalidate>
                        <input type="text" 		name="email" class="form-control" value="<?php echo set_value('email');?>" placeholder="Email Address">
                        <input type="password" 	name="password" class="form-control" placeholder="Password"><br>
                        <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login" name="login">Sign in</button>
                        
                        <label class="checkbox pull-left">
                            <input type="checkbox" name="rememberme" value="1"> Remember me
                        </label>
                        <a href="<?=base_url('auth/forgot_password')?>" class="pull-right need-help">Forgot Password? </a><span class="clearfix"></span>
                    </form>
                </div><br>
                <div class="clearfix"></div>
                <?php if(isset($response)) echo $response;?>
            </div>
        </div>
    </div>
</body>
</html>
