(function ($) {
    'use strict';

    var listingSingle = {};
    qode.modules.listingSingle = listingSingle;

    listingSingle.qodeOnDocumentReady = qodeOnDocumentReady;
    listingSingle.qodeOnWindowLoad = qodeOnWindowLoad;
    listingSingle.qodeOnWindowResize = qodeOnWindowResize;

    $(document).ready(qodeOnDocumentReady);
    $(window).load(qodeOnWindowLoad);
    $(window).resize(qodeOnWindowResize);

    listingSingle.qodeInitCommentRating = qodeInitCommentRating;
    listingSingle.qodeInitCommentSorting = qodeInitCommentSorting;
    listingSingle.qodeInitNewCommentShowHide = qodeInitNewCommentShowHide;
    listingSingle.qodeShowHideEnquiryForm = qodeShowHideEnquiryForm;
    listingSingle.qodeSubmitEnquiryForm = qodeSubmitEnquiryForm;
    listingSingle.qodeListingInitFitVids = qodeListingInitFitVids;

    /*
     All functions to be called on $(document).ready() should be in this function
     */
    function qodeOnDocumentReady() {
        qodeInitCommentRating();
        qodeInitCommentSorting();
        qodeInitNewCommentShowHide();
        qodeShowHideEnquiryForm();
        qodeSubmitEnquiryForm();
        qodeListingInitFitVids();
        qodeInitSingleListingSlider();
    }

    /*
     ** All functions to be called on $(window).load() should be in this function
     */
    function qodeOnWindowLoad() {
        qodeSingleListingSliderHeight();
    }

    /*
     ** All functions to be called on $(window).resize() should be in this function
     */
    function qodeOnWindowResize() {

    }

    function qodeInitCommentRating() {

        var article = $('.qode-listing-single-holder .qode-ls-single-item'),
            ratingInput = article.find('#qode-rating'),
            ratingValue = ratingInput.val(),
            stars = article.find('.qode-star-rating');

        var addActive = function () {
            for (var i = 0; i < stars.length; i++) {
                var star = stars[i];
                if (i < ratingValue) {
                    $(star).addClass('active');
                } else {
                    $(star).removeClass('active');
                }
            }
        };

        addActive();

        stars.click(function () {
            ratingInput.val($(this).data('value')).trigger('change');
        });

        ratingInput.change(function () {
            ratingValue = ratingInput.val();
            addActive();
        });

    }

    function qodeInitCommentSorting() {

        var articles = $('.qode-ls-single-item');

        if (articles.length) {
            articles.each(function () {
                var article = $(this),
                    postId = article.attr('id'),
                    selectButton = article.find('.qode-ls-single-comments .qode-ls-single-sort'),
                    holder = article.find('.qode-ls-single-comments .qode-comment-list');

                selectButton.on('change', function () {
                    var value = $(this).val();
                    if (qode.modules.listings.qodeIsValidObject(value)) {
                        holder.fadeOut(300);
                        var result = value.split('-'),
                            orderBy = result[0],
                            order = result[1],
                            ajaxData = {
                                action: 'qode_listing_get_post_reviews_ajax',
                                order: order,
                                orderBy: orderBy,
                                postId: postId
                            };

                        $.ajax({
                            type: "POST",
                            url: QodeListingAjaxUrl,
                            data: ajaxData,
                            success: function (data) {
                                if (data === 'error') {
                                    //error handler
                                } else {
                                    //set new item in global var
                                    var response = $.parseJSON(data);
                                    var responseHtml = response.html;
                                    holder.fadeIn(300, function () {
                                        holder.html(responseHtml);
                                    });
                                }
                            }
                        });
                    }
                });

            });
        }
    }

    function qodeInitNewCommentShowHide() {
        var articles = $('.qode-ls-single-item');

        if (articles.length) {
            articles.each(function () {
                var article = $(this),
                    panelHolderTrigger = article.find('.qode-rating-form-trigger'),
                    panelHolder = article.find('.qode-comment-form .comment-respond');

                panelHolderTrigger.on('click', function () {
                    panelHolder.slideToggle('slow');
                });
            });
        }
    }

    function qodeShowHideEnquiryForm() {
        var article = $('.qode-ls-single-item'),
            enquiryHolder = $('.qode-ls-enquiry-holder'),
            button = article.find('.qode-ls-single-contact-listing'),
            buttonClose = $('.qode-ls-enquiry-close');

        button.on('click', function () {
            enquiryHolder.fadeIn(300);
            enquiryHolder.addClass('opened');
        });

        enquiryHolder.add(buttonClose).on('click', function () {
            if (enquiryHolder.hasClass('opened')) {
                enquiryHolder.fadeOut(300);
                enquiryHolder.removeClass('opened');
            }
        });

        $(".qode-ls-enquiry-inner").click(function (e) {
            e.stopPropagation();
        });
        // on esc too
        $(window).on('keyup', function (e) {
            if (enquiryHolder.hasClass('opened') && e.keyCode == 27) {
                enquiryHolder.fadeOut(300);
                enquiryHolder.removeClass('opened');
            }
        });

    }

    function qodeSubmitEnquiryForm() {
        var enquiryHolder = $('.qode-ls-enquiry-holder'),
            enquiryMessageHolder = $('.qode-listing-enquiry-response'),
            enquiryForm = enquiryHolder.find('.qode-ls-enquiry-form');


        enquiryForm.on('submit', function () {
            enquiryMessageHolder.empty();
            var enquiryData = {
                name: enquiryForm.find('#enquiry-name').val(),
                email: enquiryForm.find('#enquiry-email').val(),
                message: enquiryForm.find('#enquiry-message').val(),
                itemId: enquiryForm.find('#enquiry-item-id').val(),
                nonce: enquiryForm.find('#qode_nonce_listing_item_enquiry').val()
            };

            var requestData = {
                action: 'qode_listing_send_listing_item_enquiry',
                data: enquiryData
            };

            $.ajax({
                type: "POST",
                url: QodeListingAjaxUrl,
                data: requestData,
                success: function (response) {
                    if (data === 'error') {
                        enquiryMessageHolder.html(response.data);
                        //error handler
                    } else {
                        enquiryMessageHolder.html(response.data);
                        enquiryForm.fadeOut(300);
                        setTimeout(function () {
                            enquiryForm.remove();
                        }, 300);
                    }
                }
            });
        });

    }

    function qodeListingInitFitVids() {

        $('.qode-ls-content-video-part').fitVids();
    }

    /*
     ** Init Single Listing Slider
     */

    function qodeInitSingleListingSlider() {
        var singleListingSlider = $('.qode-listing-single-holder .qode-ls-single-item .qode-ls-single-gallery-holder');

        if (singleListingSlider.length) {
            singleListingSlider.each(function () {
                var thisSlider = $(this),
                    numberOfItems = 3,
                    loop = true,
                    autoplay = true,
                    number = 0,
                    speed = 5000,
                    animationSpeed = 600,
                    center = true,
                    autoWidth = true,
                    navArrows = false,
                    navDots = false,
                    margin = 0;

                if (typeof singleListingSlider.data('number') !== 'undefined' && singleListingSlider.data('number') !== false) {
                    number = parseInt(singleListingSlider.data('number'));
                }

                if (typeof singleListingSlider.data('number-visible') !== 'undefined' && singleListingSlider.data('number-visible') !== false) {
                    numberOfItems = parseInt(singleListingSlider.data('number-visible'));
                }

                if (typeof singleListingSlider.data('speed') !== 'undefined' && singleListingSlider.data('speed') !== false) {
                    speed = singleListingSlider.data('speed');
                }

                if (typeof singleListingSlider.data('animation-speed') !== 'undefined' && singleListingSlider.data('animation-speed') !== false) {
                    animationSpeed = singleListingSlider.data('animation-speed');
                }

                if (typeof singleListingSlider.data('nav-arrows') !== 'undefined' && singleListingSlider.data('nav-arrows') !== false && singleListingSlider.data('nav-arrows') === 'no') {
                    navArrows = false;
                }

                if (typeof singleListingSlider.data('nav-dots') !== 'undefined' && singleListingSlider.data('nav-dots') !== false && singleListingSlider.data('nav-dots') === 'no') {
                    navDots = false;
                }

                if (number === 1) {
                    loop = false;
                    autoplay = false;
                    navArrows = false;
                    navDots = false;
                }

                var responsiveNumberOfItems1 = 1,
                    responsiveNumberOfItems2 = 2;

                if (numberOfItems < 3) {
                    responsiveNumberOfItems1 = numberOfItems;
                    responsiveNumberOfItems2 = numberOfItems;
                }

                singleListingSlider.owlCarousel({
                    items: numberOfItems,
                    loop: loop,
                    autoplay: autoplay,
                    autoplayTimeout: speed,
                    smartSpeed: animationSpeed,
                    margin: margin,
                    center: center,
                    autoWidth: autoWidth,
                    nav: navArrows,
                    dots: navDots,
                    responsive: {
                        0: {
                            items: responsiveNumberOfItems1,
                            margin: 0,
                            center: true,
                            autoWidth: true
                        },
                        769: {
                            items: responsiveNumberOfItems2
                        },
                        1025: {
                            items: numberOfItems
                        }
                    },
                    navText: [
                        '<span class="qode-prev-icon fa fa-angle-left"></span>',
                        '<span class="qode-next-icon fa fa-angle-right"></span>'
                    ]
                });
                thisSlider.css({'visibility': 'visible'});
            });
        }
    }

    /*
     ** Set Single Listing Slider Height
     */

    function qodeSingleListingSliderHeight() {
        var singleListingSlider = $('.qode-listing-single-holder .qode-ls-single-item .qode-ls-single-gallery-holder');

        //Set the responsive height of the slider

        if (singleListingSlider.length) {
            singleListingSlider.each(function () {
                var singleListingSlider = $(this),
                    sliderItem = singleListingSlider.find('.qode-ls-single-gallery-item img'),
                    maxHeight = singleListingSlider.outerHeight();

                qodeSingleListingSliderRecalculateHeight(singleListingSlider, maxHeight);

                $(window).resize(function () {
                    qodeSingleListingSliderRecalculateHeight(singleListingSlider, maxHeight);
                });

                if (singleListingSlider.data('enable-auto-width') === 'yes') {

                    sliderItem.each(function () {
                        var thisItem = $(this),
                            itemInitialHeight = thisItem[0].clientHeight;
                        qodeSingleListingSliderRecalculateItemsHeight(thisItem, itemInitialHeight);

                        $(window).resize(function () {
                            qodeSingleListingSliderRecalculateItemsHeight(thisItem, itemInitialHeight);
                        });
                    });

                }

                if (typeof singleListingSlider.data('owl.carousel') !== 'undefined') {
                    singleListingSlider.trigger('refresh.owl.carousel');
                }

            });
        }
    }

    function qodeSingleListingSliderRecalculateHeight(holder, height){
        var newHeight = qodeSingleListingSliderUpdateHeightCoefficient() * height;

        holder.css('height', newHeight)
    }

    function qodeSingleListingSliderRecalculateItemsHeight(item, height) {
        var newHeight = qodeSingleListingSliderUpdateHeightCoefficient() * height;

        item.css('height', newHeight);
    }

    function qodeSingleListingSliderUpdateHeightCoefficient() {

        var heightCoefficient = 1;

        if ($window_width < 481) {
            heightCoefficient = 0.667;
        } else if ($window_width < 600) {
            heightCoefficient =  0.87;
        } else if ($window_width < 769) {
            heightCoefficient =  0.968;
        } else if ($window_width < 1025) {
            heightCoefficient =  0.804;
        } else if ($window_width < 1281) {
            heightCoefficient = 0.779;
        } else if ($window_width < 1441) {
            heightCoefficient = 0.916;
        }

        return heightCoefficient;
    }

})(jQuery);