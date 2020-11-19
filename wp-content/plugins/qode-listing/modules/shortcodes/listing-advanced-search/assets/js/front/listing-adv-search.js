(function($) {
	'use strict';

	var listingAdvSearch = {};
	qode.modules.listingAdvSearch = listingAdvSearch;

	listingAdvSearch.qodeOnDocumentReady = qodeOnDocumentReady;

	$(document).ready(qodeOnDocumentReady);
	listingAdvSearch.qodeInitAdvSearch = qodeInitAdvSearch;
	listingAdvSearch.qodeGetAdvancedSearchResponse = qodeGetAdvancedSearchResponse;

	/*
	 All functions to be called on $(document).ready() should be in this function
	 */
	function qodeOnDocumentReady() {
		qodeInitAdvSearch();
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
	
	function qodeInitAdvSearch(){

		var container = $('.qode-ls-adv-search-holder');

		if(container.length){
			container.each(function(){

				var thisContainer = $(this),
					typeID = thisContainer.data('type'),
					nextPage = thisContainer.data('next-page'),
					maxNumPages = thisContainer.data('max-num-pages'),
				 	submitButton = thisContainer.find('.qode-adv-search-submit'),
					keywordSubmitButton = thisContainer.find('.qode-ls-adv-search-keyword-button'),
					keywordField = thisContainer.find('.qode-ls-adv-search-keyword'),
					availableListings = qodeListingConvertHtmlEntities(qodeListingTitles.titles),
					loadMoreButton = thisContainer.find('.qode-ls-adv-search-load-more');
					
				if(qode.modules.listings.qodeIsValidObject(keywordField)){
				    keywordField.autocomplete({
					    source: availableListings
				    });
				}
				
				if(qode.modules.listings.qodeIsValidObject(keywordField)){
				    keywordSubmitButton.on('click', function(){
					    qodeGetAdvancedSearchResponse(typeID, thisContainer, false);
				    });
				}
				

				submitButton.on('click', function(){
					qodeGetAdvancedSearchResponse(typeID, thisContainer, false);
				});
				
				

				if(typeof loadMoreButton !== 'undefined' && loadMoreButton !=='false'){
					qode.modules.listings.qodeShowHideButton(loadMoreButton, nextPage, maxNumPages);
					
					loadMoreButton.on('click', function(){
						qodeGetAdvancedSearchResponse(typeID, thisContainer, true);
					});
				}

			});
		}

	}

	function qodeGetAdvancedSearchResponse(typeId, container, loadMoreFlag){

		if(typeof typeId !== 'undefined' && typeId !== false && typeId !== ''){
		    
		    
			var number = container.data('number'),
			    searchFields = container.find('.qode-ls-adv-search-input'),
			    itemsHolder = container.find('.qode-ls-adv-search-items-holder-inner'),
			    googleMap = container.data('enable-map'),
			    mapFlag = false,
			    loadMoreData,
			    loadMoreButton = container.find('.qode-ls-adv-search-load-more'),
			    keywordField = container.find('.qode-ls-adv-search-keyword'),
			    keyword = '',
			    defaultSearchParams = {},
			    checkBoxSearchParams  = {},
			    categoryParams  = {},
			    nextPage,
			    data = {};
			    
			if(qode.modules.listings.qodeIsValidObject(googleMap))    {
			    if(googleMap === 'yes'){
				mapFlag = true;
			    }
			}
			

			if(searchFields.length){
				searchFields.each(function(){

					var thisField = $(this);
					var fieldNameAttr = thisField.attr('name');
					var fieldType = thisField.attr('type');
					var fieldID;
					var fieldVal;

					if(fieldNameAttr === 'job_type_categories'){
						//generate category params
						fieldVal = thisField.is(':checked');
						fieldID = thisField.attr('id');
						categoryParams[fieldID] = fieldVal;
					}else{
						//generate params for all other fields
						switch (fieldType) {
							case 'checkbox':
								fieldVal = thisField.is(':checked');
								checkBoxSearchParams[fieldNameAttr] = fieldVal;
								break;
							default :
								fieldVal = thisField.val();
								defaultSearchParams[fieldNameAttr] = fieldVal;
								break;
						}
					}
				});
			}
			
			if(qode.modules.listings.qodeIsValidObject(keywordField))    {
			    keyword = keywordField.val();
			}
			
			
			if(loadMoreFlag){
				loadMoreData = qode.modules.common.getLoadMoreData(container);
			}else{
				container.data('next-page', '2');
			}			
			
			
			//always get value from holder
			nextPage = container.data('next-page');
			
			data = {
				action: 'qode_listing_advanced_search_response',
				typeId : typeId,
				postPerPage : number,
				defaultSearchParams: defaultSearchParams,
				checkBoxSearchParams: checkBoxSearchParams,
				catParams: categoryParams,		
				keyword: keyword,
				enableLoadMore: loadMoreFlag,
				loadMoreData: loadMoreData,
				enableMap: mapFlag
			};
			
			$.ajax({
				type: "POST",
				url: QodeListingAjaxUrl,
				data: data,
				success: function (data) {
					if (data === 'error') {

					}else{
						var response = $.parseJSON(data);
						var responseHtml = response.html;
						var maxNumPages = response.maxNumPages;
						
						if(typeof maxNumPages !== 'undefined' && maxNumPages !== 'false'){
							container.data('max-num-pages', maxNumPages);
						}
						
						if(mapFlag){
						    
						    var mapObjs = response.mapAddresses;
						    var mapAddresses = '';
						   
						   
						    if(qode.modules.listings.qodeIsValidObject(mapObjs)){
							    mapAddresses = mapObjs['addresses'];
						    }
						    
						    if(loadMoreFlag){
							nextPage++;
							container.data('next-page', nextPage);
							//if new map objects are sent via ajax, update global map objects
							
							qode.modules.listings.qodeReinitMultipleGoogleMaps(mapAddresses, 'append');
							
							setTimeout(function(){
								itemsHolder.append(responseHtml);
							},300);
						    }else{
							//update multiple map addressess object
							
							qode.modules.listings.qodeReinitMultipleGoogleMaps(mapAddresses, 'replace');
							
							setTimeout(function(){
								itemsHolder.html(responseHtml);
							},300);
						    }						    
							
						    qode.modules.listings.qodeBindTitles();
						}
						else{
						    
						    if(loadMoreFlag){
							nextPage++;
							container.data('next-page', nextPage);
							
							setTimeout(function(){
								itemsHolder.append(responseHtml);
							},300);
						    }
						    else{
							setTimeout(function(){
								itemsHolder.html(responseHtml);
							},300);
						    }
						    
						}
						
						//show button
						qode.modules.listings.qodeShowHideButton(loadMoreButton, nextPage, maxNumPages);
					}

				}
			});
		}

	}

})(jQuery);