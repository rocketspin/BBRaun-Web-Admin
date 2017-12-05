<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>B. Braun | User Management Tool</title>
    <link rel="shortcut icon" href="<?=base_url('assets/img/favicon.ico')?>"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?=base_url('assets/css/font-awesome.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap-datepicker.min.css');?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/css/dropdowns-enhancement.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>"/>
    
    <script src="<?=base_url('assets/js/jquery.min.js')?>"></script>
    <script src="<?=base_url('assets/js/moment.js');?>"></script>
	<script src="<?=base_url('assets/js/bootstrap-datepicker.min.js');?>"></script>
    <script src="<?=base_url('assets/js/jquery-sortable.js');?>"></script>
    <script>var base_url = '<?=base_url();?>';</script>
</head>
<body>
	<div id="loader"><i class="fa fa-spinner fa-spin"></i></div>
    
	<div id="header">
    	<h1 class="title">
        	<img src="<?=base_url('assets/img/logo.png')?>" alt="Logo"/>
        </h1>
        <?php if($this->ion_auth->is_admin()):?>
        	<h2 class="page-title">HHAT Administration Platform</h2>
        <?php else:?>
        	<h2 class="page-title">HHAT Manager Platform</h2>
		<?php endif;?>
        
        <div class="right-content">
        	<?php if($this->ion_auth->is_admin()):?>
                <a href="<?=base_url()?>" data-toggle="tooltip" data-placement="bottom" title="Users List" class="pull-left">
                    <i class="fa fa-users"></i>
                </a>
                
                <a href="<?=base_url('page/companies')?>" data-toggle="tooltip" data-placement="bottom" title="Institute" class="pull-left">
                    <i class="fa fa-building-o"></i>
                </a>
            <?php endif;?>
            <a href="<?=base_url('auth/logout')?>" data-toggle="tooltip" data-placement="bottom" title="Logout?" class="confirm pull-left">
            	<i class="fa fa-sign-out"></i>
            </a>
        </div>
    </div>
    
    <div class="clearfix"></div>
    <div id="wrapper">
    	<div class="container-fluid">