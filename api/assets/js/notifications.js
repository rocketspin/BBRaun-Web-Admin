function displayNotebox()
{
	if($('#note-container .note-body').is(':visible'))
	{
		$('#note-container .note-body').hide();
		$('#note-container .show-btn').html('<i class="fa fa-arrow-up"></i>');
	}
	else
	{
		$('#note-container .note-body').show();
		$('#note-container .show-btn').html('<i class="fa fa-arrow-down"></i>');
	}
}

function getNotifications(callback)
{
	var html;
	var $badge = $('#note-container .note-header .badge');
	
	$.get(base_url+'post/get_notifications/'+$('#note-container').attr('lastid'),
	function(data)
	{
		if(data.Status)
		{
			$badge = $badge.html(parseInt($badge.html()) + parseInt(data.Response.Total));
			$.each(data.Response.Result, function(index, item)
			{
				html = '<li><a href="'+base_url+'page/notification/'+item.table_name+'/'+item.table_id+'">';
				html += '<img src="'+base_url+'assets/img/user-icon.png"/>';
				html += '<p><strong>'+item.full_name+' ('+item.username+')</strong> '+item.description+'</p>';
				html += '<span class="datetime">'+item.date_registered+'</span>';
				html += '<div class="clearfix"></div>';
				html += '</a></li>';
				
				$('#note-container .note-body ul').prepend(html);
				$('#note-container').attr('lastid', item.id);
			});
		}
		
		if(typeof callback == 'function') callback(data.Status);
		
		if(parseInt($badge.html()) > 0)
			$badge.show();
		else
			$badge.hide();
		
		
	},'json');
}

function reloadNotifications()
{
	$('#note-container .fa-refresh').addClass('fa-spin');
	getNotifications(function(result){
		if(result) $('#NotificationAudio')[0].play();
		$('#note-container button .fa-refresh').removeClass('fa-spin');
	});
}

getNotifications();

window.setInterval(function(){

	getNotifications(function(result){
		if(result) $('#NotificationAudio')[0].play();
	});
	
},30000);


	