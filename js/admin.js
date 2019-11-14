jQuery( document ).ready(function() {

	jQuery('body').on('click', '.close-form', function() {
		jQuery('.darkness').hide();
		jQuery('.kava-admin-form').hide();
	});

	// что-то делаем со строчкой
	jQuery('body').on('click', '.action-row', function() {
		$this = jQuery(this);
		var currentPath = $this.data('path');
		var currentAction = $this.data('action');
		var rowIndex = $this.data('index');

		var formLink = admin_base_link+'?l='+currentPath+'&a='+currentAction+'&id='+rowIndex;

		jQuery.ajax({
			url: formLink,
			type: "GET",
			//data: {'name': name},
			dataType: "html",
			// перед началом отправки
			beforeSend: function(xhr) {
				// показываем анимацию загрузки
				jQuery('.kava-loader').show();
				jQuery('.darkness').show();
			},
		}).success(function(backdata) {
			jQuery('#basic-form').html(backdata);
			jQuery('.kava-admin-form').show();
			console.log(backdata);
			jQuery('.kava-loader').hide();
		}).fail(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			console.log(response);
			jQuery('.kava-loader').hide();
		});
	});
	
});
