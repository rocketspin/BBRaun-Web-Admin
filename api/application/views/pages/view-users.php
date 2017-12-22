<a href="<?=base_url('page/add_user')?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add User</a>
<div class="clearfix"></div><br />

<?php
echo $response;

$filter['columns'] = array(
	'users-first_name' 		=> 'First Name',
	'users-last_name' 		=> 'Last Name',
	'users-email' 			=> 'Email Address',
	'country_name' 		=> 'Country',
	'name' 		=> 'Institution',
);
$this->load->view('includes/filter-widget', $filter);
?>

<div class="row-fluid">
    <div class="panel panel-default">
        <table class="table table-striped table-xs table-hover table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Active?</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>Institution</th>
                    <th>Expiration Date</th>
                    <th>Date Added/Modified</th>
                    <th width="40"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Active?</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Country</th>
                    <th>Institution</th>
                    <th>Expiration Date</th>
                    <th>Date Added/Modified</th>
                    <th width="40"></th>
                </tr>
            </tfoot>

            <tbody>
                <?php if(count($datatables['results'])):?>
                    <?php foreach($datatables['results'] as $users):?>

                        <tr<?php if(!$users->active) echo ' class="danger"';?>>
                            <td><?= (($users->active == 1) ? 'YES' : 'NO')?></td>
                            <td><?=$users->full_name?></td>
                            <td><?=$users->email?></td>
                            <td><?=$users->country_name?></td>
                            <td><?=$users->company?></td>
                            <td><?=$users->expiration_date?></td>
                            <td><?=$users->date_registered?></td>
                            <td align="center">
                                <div class="btn-group btn-member">
                                    <button data-toggle="dropdown" class="btn btn-xs btn-primary dropdown-toggle"><span class="fa fa-cog"></span></button>
                                    <ul class="dropdown-menu pull-right bullet">
                                        <li><a href="<?=base_url('page/edit_user/'.$users->id)?>"><i class="fa fa-pencil"></i> Edit</a></li>

                                        <?php if($users->active):?>
                                            <li><a href="<?=base_url('auth/update_status/'.$users->id)?>" class="confirm"><i class="fa fa-lock"></i> Inactive</a></li>
                                        <?php else:?>
                                            <li><a href="<?=base_url('auth/update_status/'.$users->id)?>" class="confirm"><i class="fa fa-unlock"></i> Active</a></li>
                                        <?php endif;?>
                                        <!-- Other items -->
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php else:?>
                    <tr>
                        <td colspan="30" align="center"><h3>No records found.</h3></td>
                    </tr>
                <?php endif;?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
	<div class="col-md-6 col-sm-6 col-lg-6">
		<?=$datatables['links'];?>
    </div>
</div>
