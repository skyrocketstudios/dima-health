(function($){
	$(document).ready(function() {
		qodeCreateCustomField();
		qodeOptionRepeater();
		qodeOptionRepeaterDeleteRow();
		qodeToggleRow();
		qodeDeleteRow();
		qodeAmenityRepeater();
		qodeAmenityRepeaterDeleteRow();
		qodeInitAmenityIconSelectDependency();
	});
	function qodeCreateCustomField(){

		var customField = $('.qode-add-custom-field');
		if(customField.length){
			customField.on('click',function(){
				var thisField = $(this);
				var parent = thisField.parents('.term-description-wrap');
				var data = {
					type: thisField.data('type'),
					action: 'qode_listing_get_custom_field_html'
				};
				$.ajax({
					type: "POST",
					url: QodeAdminAjaxUrl,
					data: data,
					success: function (data) {
						if (data === 'error') {
							//error handler
						}else{
							response = $.parseJSON(data);
							parent.after(response.html);
							qodeOptionRepeater();
							qodeOptionRepeaterDeleteRow();
							qodeToggleRow();
							qodeDeleteRow();
						}
					}
				});
				return false;
			});
		}
	}

	function qodeOptionRepeater(){
		var button = $('.qode-option-repeater-button');
		var counter = 0;
		if(button.length){
			button.each(function(){
				var currentButton = $(this);
				currentButton.on('click', function(){
					counter ++;
					var thisButton = $(this);
					var parent = thisButton.siblings('.qode-custom-select-field-option-wrapper');
					var selectFieldId = '';
					var customFieldWrapper = parent.parents('.qode-custom-field-wrapper');
					if(customFieldWrapper.hasClass('qode-custom-select-field')){
						selectFieldId = customFieldWrapper.data('select-field-id');
					}

					var data = {
						action: 'qode_listing_get_option_field_html',
						parentId: selectFieldId
					};
					if(counter === 1){
						$.ajax({
							type: "POST",
							url: QodeAdminAjaxUrl,
							data: data,
							success: function (data) {
								response = $.parseJSON(data);
								if (response === 'error') {
									//error handler
								}else{
									parent.append(response.html);
									qodeOptionRepeater();
									qodeOptionRepeaterDeleteRow();
									qodeToggleRow();
									qodeDeleteRow();
								}
							}
						});
					}
				});
			});
		}

	}
	function qodeAmenityRepeater(){
		var button = $('.qode-custom-amenity-add-button');
		var counter = 0;
		if(button.length){
			button.each(function(){
				var currentButton = $(this);
				currentButton.on('click', function(){
					counter ++;
					var thisButton = $(this);
					var parent = thisButton.siblings('.qode-taxonomy-amenities-holder');
					var data = {
						action: 'qode_listing_get_amenity_field_html'
					};
					if(counter === 1){
						$.ajax({
							type: "POST",
							url: QodeAdminAjaxUrl,
							data: data,
							success: function (data) {
								response = $.parseJSON(data);
								if (response === 'error') {
									//error handler
								}else{
									parent.append(response.html);
									qodeAmenityRepeater();
									qodeAmenityRepeaterDeleteRow();
									qodeInitAmenityIconSelectDependency();
								}
							}
						});
					}
				});
			});
		}

	}
	function qodeOptionRepeaterDeleteRow(){
		var deleteButton = $('.qode-option-repeater-close-button');
		deleteButton.on('click', function(){
			var thisCloseButton = $(this);
			var parent = thisCloseButton.parents('.qode-option-repeater-field-row');
			parent.remove();
		});
	}
	function qodeAmenityRepeaterDeleteRow(){
		var deleteButton = $('.qode-custom-amenity-close-button');
		deleteButton.on('click', function(){
			var thisCloseButton = $(this);
			var parent = thisCloseButton.parents('.qode-amenity-repeater-row');
			parent.remove();
		});
	}
	function qodeToggleRow(){

		var toggleRowTrigger = $('.qode-custom-row-expand-button');

		toggleRowTrigger.on('click', function(e){
			e.stopImmediatePropagation();

			var thisCloseButton = $(this);
			var content = thisCloseButton.siblings('.qode-custom-field-inner');
            var textContent = thisCloseButton.find('.qode-custom-row-opener');

			content.slideToggle();

            if(textContent.text() === '-'){
                textContent.text('+');
            }
            else{
                textContent.text('-');
            }

		});
	}

	function qodeDeleteRow(){

		var deleteButton = $('.qode-custom-row-close-button');
		deleteButton.on('click', function(){
			var thisCloseButton = $(this),
				parent = thisCloseButton.parents('.form-field.custom-term-row');

			parent.remove();

		});

	}

	function qodeInitAmenityIconSelectDependency() {

		var container = $('.qode-amenity-repeater-row');
		if(container.length){
			container.each(function() {

				var thisContainer = $(this),
					iconPack = thisContainer.find('#qode_amenity_icon_pack'),
					iconHolders = thisContainer.find('.icon-collection');

				var checkDependency = function() {
					iconHolders.each(function(){
						var value = iconPack.val(),
							holder = $(this);
						if ( holder.hasClass( value ) ) {
							holder.fadeIn(300);
						} else {
							holder.fadeOut(300);
						}
					});
				};
				checkDependency();

				iconPack.change( function() {
					checkDependency();
				});

			});
		}
	}

})(jQuery);