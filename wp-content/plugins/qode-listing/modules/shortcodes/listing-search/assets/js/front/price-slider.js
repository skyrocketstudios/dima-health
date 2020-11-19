(function($) {
	'use strict';

	var listingSearch = {};
	qode.modules.listingSearch = listingSearch;

	listingSearch.qodeListingSearchPriceSlider = qodeListingSearchPriceSlider;

	listingSearch.qodeOnDocumentReady = qodeOnDocumentReady;

	$(document).ready(qodeOnDocumentReady);

	/*
	 All functions to be called on $(document).ready() should be in this function
	 */
	function qodeOnDocumentReady() {
		qodeListingSearchPriceSlider();
	}
    
	function qodeListingSearchPriceSlider(){
        var holder = $('.qode-ls-main-search-holder-part.price'),
            slider = holder.find('.qode-price-slider'),
            maxValue = slider.attr('max'),
            response = holder.find('.qode-price-slider-response'),
            hiddenValue = holder.find('.qode-price-slider-value');
    
        // Basic rangeslider initialization
		slider.rangeslider({
			polyfill: false,
			onInit: function(position, value) {
				qodeSetListingSearchPricePosition(maxValue, value);
			},
			onSlide: function(position, value) {
                qodeSetListingSearchPriceAmount(value);
                qodeSetListingSearchPricePosition(maxValue, value);
			}
		});

		function qodeSetListingSearchPriceAmount(value){
			response.text("$"+value);
            hiddenValue.val(value);
		}
		
		function qodeSetListingSearchPricePosition(maxValue, currentValue) {

			if(qode.modules.listings.qodeIsValidObject(maxValue) && qode.modules.listings.qodeIsValidObject(currentValue) ){
                var percent = (currentValue/maxValue) * 100;
                response.css('left', percent+'%');
			}

        }
        
	}
})(jQuery);