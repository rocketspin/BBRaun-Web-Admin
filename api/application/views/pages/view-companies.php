<a href="<?=base_url('page/add_company')?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Institute</a>
<div class="clearfix"></div><br />
<?php
echo $response;

$filter['columns'] = array(
	'country_name' 			=> 'Country',
	'name' 			=> 'Company Name',
);
$this->load->view('includes/filter-widget-company', $filter);
?>


<div class="panel panel-default">
	<table class="table table-striped table-xs table-hover table-bordered table-responsive">
    	<thead>
        	<tr>
                <th>ID</th>
                <th>Country</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Expiration</th>
                <th width="70"></th>
            </tr>
        </thead>
        <tfoot>
        	<tr>
                <th>ID</th>
                <th>Country</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Expiration</th>
                <th></th>
            </tr>
        </tfoot>

        <tbody>
        	<?php if(count($datatables['results'])):?>
				<?php foreach($datatables['results'] as $item):?>
                
                    <tr>
                        <td><?=$item->id?></td>
                        <td><?=$item->country_name?></td>
                        <td><?=$item->name?></td>
                        <td><?=$item->phone?></td>
                        <td><?=$item->expiration?></td>
                        <td align="center">
                        <a href="<?=base_url('page/edit_company/'.$item->id)?>" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i> Edit</a>
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
<div class="row">
	<div class="col-md-6 col-sm-6 col-lg-6">
		<?=$datatables['links'];?>
    </div>
</div>
