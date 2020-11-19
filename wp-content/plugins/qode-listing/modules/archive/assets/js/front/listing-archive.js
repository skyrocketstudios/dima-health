(function($) {
	'use strict';

	var listingArchive = {};
	qode.modules.listingArchive = listingArchive;

	listingArchive.qodeOnDocumentReady = qodeOnDocumentReady;

	$(document).ready(qodeOnDocumentReady);
	
	listingArchive.qodeInitArchiveSearch = qodeInitArchiveSearch;
	listingArchive.qodeRenderAmenities = qodeRenderAmenities;
	listingArchive.qodeGetArchiveSearchResponse = qodeGetArchiveSearchResponse;
	listingArchive.qodeUpdateListingsNumber = qodeUpdateListingsNumber;


	/*
	 All functions to be called on $(document).ready() should be in this function
	 */
	function qodeOnDocumentReady() {
		qodeInitArchiveSearch();
		qodeFindUserLocation();
		qodeInitRangeSlider();
	}

    function qodeListingConvertHtmlEntities( array ){
        var i = 0,
            n = array.length;

        while( i < n ){
            array[i] = array[i].replace("&#8217;", "'").replace('&#8220;', '"').replace('&#8221;', '"').replace('&#038;', '&');
            i++;
        }

        return array;
    }
	
	function qodeInitArchiveSearch(){
		var container = $('.qode-ls-archive-holder');

		if(container.length){
			container.each(function(){

				var thisContainer = $(this),
					keywordSearch = thisContainer.find('.qode-archive-keyword-search'),
					typeSearch = thisContainer.find('.qode-archive-type-search'),
					typeSearchVal = typeSearch.val(),
					addressSearch = document.getElementById('qode-archive-places-search'),
					amenitiesHolder = thisContainer.find('.qode-listing-type-amenities-holder'),
					submitButton = thisContainer.find('.qode-archive-submit-button'),
					loadMoreButton = thisContainer.find('.qode-listing-archive-load-more'),
					availableListings = qodeListingConvertHtmlEntities(qodeListingTitles.titles),
					currentVar = qodeListingArchiveVar.searchParams;

				qodeUpdateListingsNumber(thisContainer, currentVar['foundPosts']);

				keywordSearch.autocomplete({
					source: availableListings
				});

				//check if type is set on page load
				if(typeof typeSearchVal !== "undefined" && typeSearchVal !== false && typeSearchVal !== null){
					qodeRenderAmenities(amenitiesHolder, typeSearchVal);
				}
				typeSearch.on('change', function(){
					var typeValue = $(this).val();
					qodeRenderAmenities(amenitiesHolder, typeValue);
				});

				//get address and distance on address change
				qodeGetAddressFieldParams(addressSearch);


				submitButton.on('click', function(){
					qodeGetArchiveSearchResponse(thisContainer, false);
				});
				if( typeof loadMoreButton !== 'undefined' && loadMoreButton !== null){
					loadMoreButton.on('click', function(){
						qodeGetArchiveSearchResponse(thisContainer, true);
					});
				}

				qode.modules.listings.qodeShowHideButton(loadMoreButton, currentVar['nextPage'], currentVar['maxPage']);

			});
		}
	}
	
	function qodeUpdateListingsNumber(container, currentNumber){

		var holder = container.find('.qode-ls-archive-items-number span');
		holder.html(currentNumber);

	}

	function qodeRenderAmenities(holder, typeId){
		holder.fadeOut(300);
		holder.removeClass('qode-opened');
		if(typeof typeId !== 'undefined' && typeId !== false && typeId !== ''){
			var ajaxData = {
				typeId: typeId,
				action: 'qode_listing_get_listing_type_amenities_html'
			}
			$.ajax({
				type: "POST",
				url: QodeListingAjaxUrl,
				data: ajaxData,
				success: function (data) {
					if (data === 'error') {
						//error handler
					}else{
						var response = $.parseJSON(data);
						var responseHtml = response.html;
						if(responseHtml !== ''){
							holder.fadeIn(300, function(){
								holder.addClass('qode-opened');
								holder.html(responseHtml);
							});
						}
					}
				}
			});
		}
	}

	function qodeGetArchiveSearchResponse(container, loadMoreFlag){

		var	keywordSearch = container.find('.qode-archive-keyword-search'),
			typeSearch = container.find('.qode-archive-type-search'),
			amenitiesArray = container.find('.qode-amenity-field'),
			loadMoreButton = container.find('.qode-listing-archive-load-more'),
			addressInput = container.find('.qode-archive-places-search'),
			itemHolder = container.find('.qode-ls-archive-items-inner'),
			distance = container.find('.qode-rangle-slider-response'),
			dist = 5, //set default distance value
			currentVar = qodeListingArchiveVar.searchParams;


		currentVar['keyword'] = keywordSearch.val();
		currentVar['type'] = typeSearch.select2('val');
		currentVar['amenities'] = {};

		if(amenitiesArray.length){
			amenitiesArray.each(function(){

				var thisField = $(this);
				var fieldVal;
				var fieldNameAttr = thisField.attr('name');

				fieldVal = thisField.is(':checked');
				currentVar['amenities'][fieldNameAttr] = fieldVal;
			});
		}

		if(loadMoreFlag){
			currentVar['enableLoadMore'] = true;
		}else{
			currentVar['enableLoadMore'] = false;
			currentVar['nextPage'] = '2';
		}


		//take distance. Note that lat and long address params are set in qodeGetAddressFieldParams function

		if(qode.modules.listings.qodeIsValidObject(distance)){
			var distanceValue = distance.text();
			if(distanceValue !== ''){
				dist = distanceValue;
			}
		}

		currentVar['locationDist'] = dist;

		//reset locationObject if address input field is empty
		if(addressInput.val() === ''){
			if(currentVar['locationObject'] !== null && typeof currentVar['locationObject'] !== 'undefined'){
				currentVar['locationObject'] = {};
			}
		}

		var ajaxData = {
			action: 'qode_listing_get_archive_search_response',
			searchParams: currentVar
		}

		$.ajax({
			type: "POST",
			url: QodeListingAjaxUrl,
			data: ajaxData,
			success: function (data) {
				if (data === 'error') {
					//error handler
				}else{
					var response = $.parseJSON(data);

					//update current post number
					var foundPosts = response.foundPosts;
					qodeUpdateListingsNumber(container, foundPosts);

					var mapObjs = response.mapAddresses;
					var mapAddresses = '';
					if(mapObjs !== null){
						mapAddresses = mapObjs['addresses'];
					}

					//update maxNumPages after each ajax response
					currentVar['maxPage'] = response.maxNumPages;

					//if is clicked load more button
					if(loadMoreFlag){
						//update nextPage after each ajax response
						currentVar['nextPage']++;

						//if new map objects are sent via ajax, update global map objects
						if(mapAddresses !== ''){
							qode.modules.listings.qodeReinitMultipleGoogleMaps(mapAddresses, 'append');
						}
						itemHolder.append(response.html);
					}
					else{
						//update multiple map addressess object
						if(mapAddresses !== ''){
							qode.modules.listings.qodeReinitMultipleGoogleMaps(mapAddresses, 'replace');
						}

						//get new listings html
						itemHolder.html(response.html);
					}

					//reinit bindTitles function
					qode.modules.listings.qodeBindTitles();

					//show button
					qode.modules.listings.qodeShowHideButton(loadMoreButton, currentVar['nextPage'], currentVar['maxPage']);

					//reinit global archive var object
					qodeListingArchiveVar.searchParams = currentVar;
				}
			}
		});

	}
	
	function qodeGetAddressFieldParams(addressInput){

		if ( qode.modules.listings.qodeIsValidObject(addressInput) ) {

			//Init Places search
			var autocomplete = new google.maps.places.Autocomplete(addressInput);
			//take initial value

			autocomplete.addListener('place_changed', function(){
				//take value after change
				qodeGetAddressAutocompleteResponse(autocomplete);
			});
		}
	}

	function qodeGetAddressAutocompleteResponse(autocomplete){
		var place = autocomplete.getPlace(),
			location = place.geometry.location;

			if(qode.modules.listings.qodeIsValidObject(location)){

				if(qode.modules.listings.qodeIsValidObject(location.lat()) &&  qode.modules.listings.qodeIsValidObject(location.lng())){
					qodeSetListingAddressParams(location.lat(), location.lng());
				}

			}

	}
	
	function qodeSetListingAddressParams(latitude, longitude){

		var locationObject = {};
 		locationObject['lat'] = latitude;
		locationObject['long'] = longitude;

		qodeListingArchiveVar.searchParams['locationObject'] = locationObject;

	}

	function qodeInitRangeSlider(){

		var selectorHolder =  $('.qode-listing-places-dist-holder');
		var slider = selectorHolder.find('.qode-rangle-slider');
		var output = selectorHolder.find('.qode-rangle-slider-response');;

		// Basic rangeslider initialization
		slider.rangeslider({
			polyfill: false,
			onInit: function(position, value) {
				qodeListingSetCurrentDistance(value);
			},
			onSlide: function(position, value) {
				qodeListingSetCurrentDistance(value);
			}
		});

		function qodeListingSetCurrentDistance(value){
			output.text(value);
		}

	}
	
	function qodeFindUserLocation(){

		var location = $('.qode-archive-current-location');

		location.on('click', function(){

			if (!navigator.geolocation){
				alert('Geolocation is not supported by your browser');
				return;
			}

			var thisLocationField = $(this);
			var addressField = thisLocationField.next('.qode-archive-places-search');
			var address = '';

			function success(position) {

				var latitude = position.coords.latitude;
				var longitude = position.coords.longitude;
				

				var GEOCODING = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latitude + '%2C' + longitude + '&language=en';

				$.getJSON(GEOCODING).done(function(location) {
					address = location.results[0].formatted_address;
					addressField.val(address);
				});

				qodeSetListingAddressParams(latitude, longitude);
			}

			function error(error) {

				if(error.code === 1 && error.message === 'Only secure origins are allowed (see: https://goo.gl/Y0ZkNV).'){

					$.getJSON("http://jsonip.com/?callback=?", function (data) {

						if(qode.modules.listings.qodeIsValidObject(data.ip)){

							$.getJSON('http://ip-api.com/json/'+data.ip, function(response) {
								
								if(qode.modules.listings.qodeIsValidObject(response.lat) && qode.modules.listings.qodeIsValidObject(response.lon)) {

									$.getJSON('http://maps.googleapis.com/maps/api/geocode/json?latlng='+response.lat+','+response.lon+'&sensor=true', function(response){
										address = response.results[0].formatted_address;
										addressField.val(address);
									});
									qodeSetListingAddressParams(response.lat, response.lon);
								}
							});

						}

					});

				}else {
					alert('ERROR(' + error.code + '): ' + error.message);
				}
			}

			navigator.geolocation.getCurrentPosition(success, error);

		});
	}
})(jQuery);