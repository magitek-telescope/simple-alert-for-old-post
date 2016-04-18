(function ($){
	var prefix = {
		"id": "#alert_settings_"
	};
	update_alert_preview();

	function update_alert_preview(){
		var text = $(prefix['id']+"message").val();
		var date_type = $(prefix['id']+"date_type option[value='" + $(prefix['id']+"date_type").val() + "']").text();
		$(".alert-content").text(text.replace(/%s/g, $(prefix['id']+"date").val() + date_type));
	};

	$(prefix['id']+"theme").change(function(event) {
		event.preventDefault();
		$.each($(this).children('option'), function(index, val) {
			$(".simple-old-alert").removeClass('alert-'+$(this).attr("value"));
		});

		$(".simple-old-alert").addClass('alert-'+$(this).val());
	});

	$(prefix['id']+"icon").change(function(event) {
		event.preventDefault();
		switch($(this).val()){
			case "info" : {
				$(".simple-old-alert").removeClass("alert-caution");
				$(".simple-old-alert").addClass("alert-info");
				break;
			}

			case "caution" : {
				$(".simple-old-alert").removeClass("alert-info");
				$(".simple-old-alert").addClass("alert-caution");
				break;
			}
		}
	});

	$(prefix['id']+"date_type").change(function() {
		update_alert_preview();
	});

	$(prefix['id']+"message").on('change keyup', function() {
		update_alert_preview();
	});

	$(prefix['id']+"date").on('change keyup', function (){
		update_alert_preview();
	});

	$(".alert-settings-checkbox").click(function() {
		$(this).siblings('.checkbox-hidden').click();
	});

})( jQuery );
