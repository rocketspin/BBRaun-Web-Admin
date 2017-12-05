<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Forgot Password | B. Braun Hand Hygiene</title>
        <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico')?>"/>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?=base_url('assets/css/font-awesome.min.css')?>">
        <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css')?>"/>
        <link rel="stylesheet" href="<?=base_url('assets/css/login.css')?>"/>
    </head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-7 col-md-5 col-md-offset-3">
            	<h1 class="text-center login-title">Password Recovery</h1>
                <div class="account-wall forgot-password">
                    <form class="form-signin" action="<?=base_url('auth/forgot_password')?>" method="post" novalidate>
                        <div class="form-group">
                        	<label>Username</label>
                        	<input type="text" name="username" value="<?=set_value('username');?>" class="form-control" placeholder="Enter your email address..."/>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login" name="login">Submit</button>
                    </form>
                </div><br>
                <div class="clearfix"></div>
                <a href="<?=base_url('auth')?>" class="pull-right">Back to login</a>
                <div class="clearfix"></div>
                <?php if(isset($response)) echo '<br>'.$response;?>
            </div>
        </div>
    </div>
</body>
</html>
