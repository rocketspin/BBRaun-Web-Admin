<?php
if(!is_array($columns)) $columns = array();
foreach($columns as $key => $item) $field_search[] = $key;
?>
<div class="paging-tool">
    <div class="pull-left">
    	<a href="?download=true" class="btn btn-danger pull-left btn-download" title="Download" data-placement="bottom" data-toggle="tooltip">
        	<i class="fa fa-arrow-circle-o-down"></i>
        </a>
        <form method="get" class="form-inline pull-left form-perpage" action="<?=$datatables['base_url'];?>/companies">
            <select name="per_page" class="form-control" onchange="$(this).closest('form').submit();">
            	<?php 
				$nums = array(30 => 30, 50 => 50, 100 => 100, 150 => 150, 200 => 200, 300 => 300, 500 => 500, 10000000000 => 'All');
				
				foreach($nums as $key => $num)
				{
					if($key == $this->session->userdata('per_page'))
						echo '<option value="'.$key.'" selected="selected">'.$num.'</option>';
					else
						echo '<option value="'.$key.'">'.$num.'</option>';
				}
				?>
            </select>
        </form>
        
		<div class="pull-left">
			<?=$datatables['links'];?>
        </div>
    </div>
    	
    <div class="pull-right filter-search">
        <div class="input-group">
            <form method="get" class="form-inline form-search" action="<?=$datatables['base_url'];?>/companies" id="filter_search">
                <input type="hidden" name="field_search" value="<?=implode(',', $field_search)?>" />
                <input type="text" class="form-control" name="keyword" placeholder="Search Columns" value="<?=$this->input->get('keyword')?>">            
            </form>
            
            <form method="get" class="form-inline form-search" action="<?=$datatables['base_url'];?>/companies">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-primary" onclick="$('#filter_search').submit();">Search</button>
                    <button data-toggle="dropdown" class="btn btn-<?=($this->input->get('filter_column')) ? 'success' : 'primary';?> dropdown-toggle" data-placeholder="false"><i class="fa fa-filter"></i></button>
                    <ul class="dropdown-menu pull-right bullet">
                        <li class="dropdown-header text-center">Filter By Column</li>
                        <?php foreach($columns as $key => $item):?>
                            <li><input type="text" name="<?=$key?>" class="form-control input-sm" placeholder="<?=$item?>" value="<?=$this->input->get($key)?>"/></li>
                        <?php endforeach;?>
                        <li class="divider"></li>
                        <li>
                            <div class="btn-group btn-group-justified" role="group" aria-label="...">
                                <div class="btn-group" role="group">
                                <a href="<?=current_url();?>" class="btn btn-sm btn-default" onclick="$('#filter_search').submit();">Clear</a>
                                </div>
                                <div class="btn-group" role="group">
                                <input type="submit" name="filter_column" value="Filter" class="btn btn-sm btn-primary"/>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>
            
            <form method="get" class="form-inline form-search form-calendar hidden" action="<?=$datatables['base_url'];?>/companies">
            	&nbsp;&nbsp;
                <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" data-placeholder="false"><i class="fa fa-calendar"></i></button>
                    <div class="dropdown-menu pull-right bullet">
						<div id="date_filter_from"></div>
                    </div>
			</form>
        </div><!-- /input-group -->
        
        
        
    </div>
</div>
<div class="clearfix"></div>