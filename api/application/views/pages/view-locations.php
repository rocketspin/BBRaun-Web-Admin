<div class="location-container">
	<div class="location">
        <div class="panel panel-primary">
            <div class="panel-heading">Healthcare Worker</div>
            <div class="panel-body">
            	<div class="input-group">
                    <input type="text" class="form-control input-healthcare" data-list="healthcare" placeholder="Add new">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" onclick="addItem($('.input-healthcare'))"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
                
                <ol class="location-list healthcare"></ol>
                <div class="note">NOTE: Drag to sort items</div>
            </div>
        </div>
    </div>
    
    <div class="location">
        <div class="panel panel-primary">
            <!-- <div class="panel-heading">Facility</div>  -->
            <div class="panel-heading">Location Level 1</div>  
            <div class="panel-body">
            	<div class="input-group">
                    <input type="text" class="form-control input-location1" data-list="location1" placeholder="Add new">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" onclick="addItem($('.input-location1'))"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
                
                <ol class="location-list location1"></ol>
                <div class="note">NOTE: Drag to sort items</div>
            </div>
        </div>
    </div>
    

    <div class="location">
        <div class="panel panel-primary">
            <!-- <div class="panel-heading">Service</div>  -->
            <div class="panel-heading">Location Level 2</div> 
            <div class="panel-body">
            	<div class="input-group">
                    <input type="text" class="form-control input-location4" data-list="location4" placeholder="Add new">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" onclick="addItem($('.input-location4'))"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
                
                <ol class="location-list location4"></ol>
                <div class="note">NOTE: Drag to sort items</div>
            </div>
        </div>
    </div>
    
    <div class="location">
        <div class="panel panel-primary">
            <!-- <div class="panel-heading">Ward</div>  -->
            <div class="panel-heading">Location Level 3</div> 
            <div class="panel-body">
            	<div class="input-group">
                    <input type="text" class="form-control input-location3" data-list="location3" placeholder="Add new">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" onclick="addItem($('.input-location3'))"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
                
                <ol class="location-list location3"></ol>
                <div class="note">NOTE: Drag to sort items</div>
            </div>
        </div>
    </div>

    <div class="location">
        <div class="panel panel-primary">
            <!-- <div class="panel-heading">Department</div>  -->
            <div class="panel-heading">Location Level 4</div> 
            <div class="panel-body">
            	<div class="input-group">
                    <input type="text" class="form-control input-location2" data-list="location2" placeholder="Add new">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" onclick="addItem($('.input-location2'))"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
                
                <ol class="location-list location2"></ol>
                <div class="note">NOTE: Drag to sort items</div>
            </div>
        </div>
    </div>
    
    
    
</div>


<script type="application/javascript">
function serialize(className, location)
{
	$.each(location, function(index, item){
		$('ol.location-list.'+className).append('<li data-id="'+item.id+'">'+item.name+'<i class="fa fa-times delete" onclick="deleteItem('+item.id+')"></i></li>');
	});
	
	$("ol.location-list."+className).sortable({
		delay: 100,
		onDrop: function ($item, container, _super) {
			var data = $("ol.location-list."+className).sortable("serialize").get();
			_super($item, container);
			
			$.ajax({
				type: "POST",
				url: '<?=base_url('tool/sort_location')?>',
				data: {'data':data[0]},
				dataType: 'json',
				success: function(result){
				},
			});
		}
	});
}

$.get('<?=base_url('api/getlocations').'?cid='.$this->ion_auth->user()->row()->cid?>', function(data){
	
	if(data.result != undefined)
	{
		if(data.result.healthcare != undefined) serialize('healthcare', data.result.healthcare);
		if(data.result.location1 != undefined) serialize('location1', data.result.location1);
		if(data.result.location2 != undefined) serialize('location2', data.result.location2);
		if(data.result.location3 != undefined) serialize('location3', data.result.location3);
		if(data.result.location4 != undefined) serialize('location4', data.result.location4);
	}
});


function addItem(elem)
{
	$.get('<?=base_url('tool/add_location_list')?>?name='+encodeURIComponent(elem.val())+'&category='+elem.data('list'), function(data){
		if(data.status == 0)
			alert(data.message);
		else
		{
			$('ol.'+elem.data('list')).append('<li data-id="'+data.result.id+'">'+elem.val()+'<i class="fa fa-times delete" onclick="deleteItem('+data.result.sort+')"></i></li>');
			elem.val('');
		}
	},'json');
}

function deleteItem( id ) {
	
	if(confirm('Are you sure you want to delete this item?'))
	{
		$.get('<?=base_url('tool/delete_location')?>?id='+id, function(data){
			if(data.status == 0)
				alert(data.message);
			else
			{
				$('ol.location-list li[data-id="'+id+'"]').remove();
			}
		},'json');
	}
}
</script>







