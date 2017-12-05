		</div>
    </div>

    <script src="<?=base_url('assets/js/bootstrap.min.js')?>"></script>
    <script>
	$(function () {
		$('[data-toggle="tooltip"]').tooltip();
		$('[data-toggle="popover"]').popover({html:true});
		
		$('a.confirm').click(function(e) {
			var conf = confirm('Are you sure you want to continue?');
			if(!conf) return false;
        });
		
		// Setup drop down menu
		$('.dropdown-toggle').dropdown();
		
		$('.dropdown-menu').click(function(event){
			event.stopPropagation();
		});
	})
	</script>
</body>
</html>
