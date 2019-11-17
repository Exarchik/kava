jQuery( document ).ready(function() {

	jQuery('body').on('click', '.yes-form', function() {
		var sendLink = admin_base_link+'?'+jQuery('#admin-form #link').val();
		jQuery.ajax({
			url: sendLink,
			type: "POST",
			data: {'confirm' : 'yes'},
			dataType: "html",
			beforeSend: function(xhr) {
				jQuery('.form-buttons>.btn').addClass('disabled');
			},
		}).success(function(backdata) {
			jQuery('#basic-form').html(backdata);
		});
	});

	jQuery('body').on('click', '.close-form, .darkness', function() {
		jQuery('.darkness').hide();
		jQuery('.kava-admin-form').hide();
	});

	jQuery('body').on('click', '.close-reloading', function() {
		location.reload();
	});

	jQuery('body').on('click', '.send-form', function() {
		var adminFormData = jQuery('#admin-form').serializeArray();
		var formPath = jQuery('#admin-form').data('path');
		var sendLink = admin_base_link+'?l='+formPath+'&a=send-form';
		//console.log([sendLink, adminFormData]);
		jQuery.ajax({
			url: sendLink,
			type: "POST",
			data: adminFormData,
			dataType: "html",
			beforeSend: function(xhr) {
				jQuery('.form-buttons>.btn').addClass('disabled');
			},
		}).success(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			jQuery('.darkness').hide();
			jQuery('.kava-admin-form').hide();
			jQuery('.form-buttons>.btn').removeClass('disabled');
			location.reload();
		});
	});

	// что-то делаем со строчкой
	jQuery('body').on('click', '.action-row', function() {
		$this = jQuery(this);
		var currentPath = $this.data('path');
		var currentAction = $this.data('action');
		var rowIndex = $this.data('index') ? $this.data('index') : '0';
		var ajaxed = $this.data('ajaxed');

		var formLink = admin_base_link+'?l='+currentPath+'&a='+currentAction+'&id='+rowIndex;

		if (!ajaxed) {
			document.location.href = formLink;
		}
		
		jQuery.ajax({
			url: formLink,
			type: "GET",
			dataType: "html",
			// перед началом отправки
			beforeSend: function(xhr) {
				// показываем анимацию загрузки
				jQuery('.kava-loader').show();
				jQuery('.darkness').show();
			},
		}).success(function(backdata) {
			jQuery('#basic-form').html(backdata);
			var topParameter = (jQuery('body').outerHeight() - jQuery('.kava-admin-form').outerHeight())/2;
			jQuery('.kava-admin-form').show();
			jQuery('.kava-admin-form').css('top', topParameter+'px');
			//console.log(backdata);
			jQuery('.kava-loader').hide();
		}).fail(function(backdata) {
			var response = jQuery.parseJSON(backdata);
			//console.log(response);
			jQuery('.kava-loader').hide();
		});
	});
	
});
