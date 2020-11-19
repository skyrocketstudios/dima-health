(function($) {
	'use strict';

	var listingGallery = {};
	qode.modules.listingGallery = listingGallery;

	listingGallery.qodeIniListingGallery = qodeIniListingGallery;

	listingGallery.qodeOnDocumentReady = qodeOnDocumentReady;

	$(document).ready(qodeOnDocumentReady);

	/*
	 All functions to be called on $(document).ready() should be in this function
	 */
	function qodeOnDocumentReady() {
		qodeIniListingGallery();
	}

	/**
	 * Masonry gallery, init masonry and resize pictures in grid
	 */
	function qodeIniListingGallery(){

		var galleryHolder = $('.qode-ls-category-gallery'),
			gallery = galleryHolder.children('.qode-ls-gallery-inner'),
			gallerySizer = gallery.children('.qode-ls-gallery-sizer');

		resizeListingGallery(gallerySizer.outerWidth(), gallery);

		if(galleryHolder.length){
			galleryHolder.each(function(){
				var holder = $(this),
					holderGallery = holder.children('.qode-ls-gallery-inner');

				holderGallery.waitForImages(function(){
					holderGallery.animate({opacity:1});

					holderGallery.isotope({
						layoutMode: 'packery',
						itemSelector: '.qode-ls-gallery-item',
						percentPosition: true,
						packery: {
							columnWidth: '.qode-ls-gallery-sizer'
						}
					});
				});
			});

			$(window).resize(function(){
				resizeListingGallery(gallerySizer.outerWidth(), gallery);
				gallery.isotope('reloadItems');
			});
		}
	}

	function resizeListingGallery(size, holder){
		var rectangle_portrait = holder.find('.qode-ls-gallery-rec-portrait'),
			rectangle_landscape = holder.find('.qode-ls-gallery-rec-landscape'),
			square_big = holder.find('.qode-ls-gallery-square-big'),
			square_small = holder.find('.qode-ls-gallery-square-small');

		
		rectangle_landscape.css('height', size);
		square_small.css('height', 'size');
		
		
		rectangle_portrait.css('height', 2*size);
		

		if (window.innerWidth <= 680) {
			rectangle_landscape.css('height', size/2);
		} else {
			rectangle_landscape.css('height', size);
		}

		square_big.css('height', 2*size);

		if (window.innerWidth <= 680) {
			square_big.css('height', square_big.width());
		}

		square_small.css('height', size);
	}

})(jQuery);