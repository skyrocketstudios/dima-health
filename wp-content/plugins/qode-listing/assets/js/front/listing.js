(function($) {

	var listings = {};
	qode.modules.listings = listings;
	listings.qodeOnDocumentReady = qodeOnDocumentReady;
	listings.qodeOnWindowLoad = qodeOnWindowLoad;
	listings.qodeOnWindowResize = qodeOnWindowResize;
	listings.qodeOnWindowScroll = qodeOnWindowScroll;

	$(document).ready(qodeOnDocumentReady);
	$(window).load(qodeOnWindowLoad);
	$(window).resize(qodeOnWindowResize);
	$(window).scroll(qodeOnWindowScroll);

	listings.qodeInitListingTypeCustomFields = qodeInitListingTypeCustomFields;
	listings.qodeGetListingTypeCustomFieldsOnChange = qodeGetListingTypeCustomFieldsOnChange;
	listings.qodeInitListingMainSearch = qodeInitListingMainSearch;
	listings.qodeBindTitles = qodeBindTitles;
	listings.qodeShowHideButton = qodeShowHideButton;
	listings.qodeReinitMultipleGoogleMaps = qodeReinitMultipleGoogleMaps;
	listings.qodeBindTitles = qodeBindTitles;
	listings.qodeIsValidObject = qodeIsValidObject;

	function qodeOnDocumentReady() {
		qodeInitListingTypeCustomFields();
		qodeGetListingTypeCustomFieldsOnChange();
		qodeInitListingMainSearch();
        qodeInitListingSimpleSearch();
		qodeBindTitles();
	}
	function qodeOnWindowLoad() {}
	function qodeOnWindowResize() {}
	function qodeOnWindowScroll() {}

	function qodeListingConvertHtmlEntities( array ){
		var i = 0,
			n = array.length;

		while( i < n ){
			array[i] = array[i].replace("&#8217;", "'").replace('&#8220;', '"').replace('&#8221;', '"').replace('&#038;', '&');
			i++;
		}

		return array;
	}

	function qodeInitListingMainSearch(){
		var container = $('.qode-ls-main-search-holder');
		if(container.length){
			container.each(function(){
				var thisContainer = $(this),
					keywordSearch = thisContainer.find('.qode-ls-main-search-keyword'),
					availableListings = qodeListingConvertHtmlEntities(qodeListingTitles.titles);

					keywordSearch.autocomplete({
						source: availableListings
					});

			});
		}
	}

    function qodeInitListingSimpleSearch(){
        var container = $('.qode-ls-simple-search-holder');
        if(container.length){
            container.each(function(){
                var thisContainer = $(this),
                    keywordSearch = thisContainer.find('.qode-ls-simple-search-keyword'),
                    availableListings = qodeListingConvertHtmlEntities(qodeListingTitles.titles);

                keywordSearch.autocomplete({
                    source: availableListings
                });

            });
        }
    }

	function qodeInitListingTypeCustomFields(){

		var typeField = $('.job-manager-form .fieldset-job_type #job_type');
		var typeFieldVal = typeField.val();
		qodeAddListingTypeItems(typeFieldVal);
		qodeDeleteListingTypeItems(typeFieldVal);

	}
	
	function qodeGetListingTypeCustomFieldsOnChange(){

		var typeField = $('.job-manager-form .fieldset-job_type #job_type');
		typeField.on('change', function(){
			var thisField = $(this);
			var thisFieldVal = thisField.val();
			qodeAddListingTypeItems(thisFieldVal);
			qodeDeleteListingTypeItems(thisFieldVal);
		});

	}

	function qodeAddListingTypeItems(types){

		if(typeof types !== 'undefined' && types !== null && types.length){
			//there is minimum one selected type
			if(types instanceof Array) {
				var i;
				for (i = 0; i < types.length; i++) {
					if ($.inArray(types[i], qodeListingGlobalVars.vars.selectedTypes) > -1) {
					}
					else {
						//element is in not in array, add it
						qodeGetListingTypeField(types[i]);
					}
				}
			} else {
				if ($.inArray(types, qodeListingGlobalVars.vars.selectedTypes) > -1) {

				}
				else {
					//element is in not in array, add it
					qodeGetListingTypeField(types);
				}
			}
		}else{
			//there is no selected types
			qodeDeleteAllListingTypeFields();
		}

	}

	function qodeDeleteListingTypeItems(types){
		if(typeof types !== 'undefined' && types !== null && types.length){

			//there is minimum one selected type
			var i;
			for(i = 0; i < qodeListingGlobalVars.vars.selectedTypes.length; i++){
				if($.inArray(qodeListingGlobalVars.vars.selectedTypes[i],types) > -1){
				}
				else{
					//element is in not in array, add it
					qodeDeleteListingTypeField(qodeListingGlobalVars.vars.selectedTypes[i]);
				}
			}

		}else{
			//there is no selected types
			qodeDeleteAllListingTypeFields();
		}
	}

	function qodeGetListingTypeField(itemId){
		var form = $('.job-manager-form');
		var formAction = form.attr('action');

		//get current post id if is set
		// this id is set on edit job pages and we need it to get custom field values
		var actionArray = formAction.split('=');
		var currentPostId = actionArray[actionArray.length - 1];

		var container = $('.job-manager-form .fieldset-job_type');
		var data = {
			selectedType: itemId,
			action: 'qode_listing_type_get_custom_fields'
		};
		if(typeof currentPostId !== 'undefined' && currentPostId !== 'false'){
			data['currentPostId'] = currentPostId;
		}
		$.ajax({
			type: "POST",
			url: QodeListingAjaxUrl,
			data: data,
			success: function (data) {
				if (data === 'error') {
					//error handler
				}else{
					//set new item in global var
					qodeListingGlobalVars.vars.selectedTypes.push(itemId);
					response = $.parseJSON(data);
					responseHtml = response.html;
					setTimeout(function(){
						container.after(responseHtml);
                        qodeReinitAdditionalSelectFields();
					},300);
 				}
			}
		});

	}
	
	function qodeReinitAdditionalSelectFields() {
        var selectFields = $('.job-manager-form .qode-ls-type-field-wrapper select');
        if(selectFields.length){
        	selectFields.each(function () {
				$(this).select2();
            });
		}
    }

	function qodeDeleteListingTypeField(itemId){

		var typeFieldWrappers = $('.qode-ls-type-field-wrapper ');

		if(typeFieldWrappers.length){
			typeFieldWrappers.each(function(){
				var thisFieldWrapper = $(this);
				var id = thisFieldWrapper.attr('id');

				if(id === itemId){
					setTimeout(function(){
						thisFieldWrapper.remove();
						//remove current element from global array
						var index = qodeListingGlobalVars.vars.selectedTypes.indexOf(itemId);
						qodeListingGlobalVars.vars.selectedTypes.splice(index, 1);
					},300);
				};

			});
		}
	}

	function qodeDeleteAllListingTypeFields(){
		var typeFieldWrappers = $('.qode-ls-type-field-wrapper ');

		if(typeFieldWrappers.length){
			typeFieldWrappers.each(function() {
				var thisFieldWrapper = $(this);
				thisFieldWrapper.remove();
			});
		}
	}
	
	function qodeReinitMultipleGoogleMaps(addresses, action){

		if(action === 'append'){

			var mapObjs = qodeMultipleMapVars.multiple.addresses;
			mapObjs = qodeMultipleMapVars.multiple.addresses.concat(addresses);
			qodeMultipleMapVars.multiple.addresses = mapObjs;

			qode.modules.maps.qodeGoogleMaps.getDirectoryItemsAddresses({
				addresses: mapObjs
			});
		}
		else if(action === 'replace'){

			qodeMultipleMapVars.multiple.addresses = addresses;
			qode.modules.maps.qodeGoogleMaps.getDirectoryItemsAddresses({
				addresses: addresses
			});

		}
	}

	function qodeShowHideButton(button, nextPage, maxNumPages){

		if(typeof button !== 'undefined' && button !== false && button !== null ){
			if(nextPage <= maxNumPages){
				button.show();
			}else{
				button.hide();
			}
		}

	}
	
	function qodeListingArchiveInitBack() {

		window.addEventListener("popstate", function(e) { // if a back or forward button is clicked
			location.reload();
		});

	}

	function qodeBindTitles() {
		
		var maps = $('.qode-ls-archive-map-holder'),
			lists = $('.qode-ls-archive-items');
		if (maps.length && lists.length){
			maps.each(function(){
				var  listItems = lists.find('.qode-listing-archive-item');

				listItems.each(function(){
					var listItem = $(this);
					listItem.mouseenter(function(){
						var itemId = listItem.attr('id');
						if ($('.qode-map-marker-holder').length) {
							$('.qode-map-marker-holder').each(function(){
								var markerHolder = $(this),
									markerId = markerHolder.attr('id');
								if (itemId == markerId) {
									markerHolder.addClass('active');
									setTimeout(function(){
									},300);
								} else {
									markerHolder.removeClass('active');
								}
							});
						}
					});
				});

				lists.mouseleave(function(){
					$('.qode-map-marker-holder').removeClass('active');
				});
			});
		}
	}	

	function qodeIsValidObject(object){
		if(typeof(object !== 'undefined') && object !== 'false' && object !== '' && object !== undefined){
			return true;
		}
		return false;
	}

})(jQuery);