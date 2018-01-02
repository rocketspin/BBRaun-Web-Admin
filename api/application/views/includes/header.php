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
    <link rel="stylesheet" href="<?=base_url('assets/datatables/datatables.min.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/css/sb-admin-2.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/bootstrap-multiselect/dist/css/bootstrap-multiselect.css')?>"/>

    <script src="<?=base_url('assets/js/jquery.min.js')?>"></script>
    <script src="<?=base_url('assets/js/moment.js');?>"></script>
	<script src="<?=base_url('assets/js/bootstrap-datepicker.min.js');?>"></script>
    <script src="<?=base_url('assets/js/jquery-sortable.js');?>"></script>
    <script src="<?=base_url('assets/bootstrap-multiselect/dist/js/bootstrap-multiselect.js');?>"></script>
    <script src="<?=base_url('assets/js/lodash.js');?>"></script>
    <script src="<?=base_url('assets/datatables/datatables.min.js');?>"></script>
    <script>var base_url='<?=base_url();?>';</script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<div id="loader"><i class="fa fa-spinner fa-spin"></i></div>

    <div class="wrapper2">

      <!-- Static navbar -->
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?=base_url('/')?>">
                <img style="max-width:140px; margin-top: -7px;" src="<?=base_url('assets/img/logo.png')?>" alt="Logo"/>
            </a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?=base_url('page/chart')?>" data-toggle="tooltip" data-placement="bottom" title="Reports" class="pull-left">
                        <i class="fa fa-bar-chart-o navIcons"></i>
                    </a>
                </li>
              <?php if ($this->ion_auth->is_admin()):?>
                <li>
                    <a href="<?=base_url()?>" data-toggle="tooltip" data-placement="bottom" title="Users List" class="pull-left">
                        <i class="fa fa-users navIcons"></i>
                    </a>
                </li>
                <li>
                    <a href="<?=base_url('page/companies')?>" data-toggle="tooltip" data-placement="bottom" title="Institute" class="pull-left">
                        <i class="fa fa-building-o navIcons"></i>
                    </a>
                </li>
                <?php else: ?>
                    <li>
                        <a href="<?= base_url('tool') ?>" data-toggle="tooltip" data-placement="bottom" title="Manage Locations" class="pull-left">
                            <i class="fa fa-map-marker navIcons"></i>
                        </a>
                    </li>
                <?php endif;?>
                <li>
                    <a href="<?=base_url('auth/logout')?>" data-toggle="tooltip" data-placement="bottom" title="Logout?" class="confirm pull-left">
                        <i class="fa fa-sign-out navIcons-danger"></i>
                    </a>
                </li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12 text-primary">
                    <?php if($this->ion_auth->is_admin()):?>
                    <h3 class="page-header">HHAT Administration Platform</h3>
                    <?php else:?>
                    <h3 class="page-title">HHAT Manager Platform</h3>
                    <?php endif;?>
                </div>
                <!-- /.col-lg-12 -->
            </div>

            <!-- /.row -->

