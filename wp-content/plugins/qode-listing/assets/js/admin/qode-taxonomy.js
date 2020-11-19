(function($){

    $(document).ready(function() {
        qodeInitMediaUploader();
        qodeInitIconSelectDependency();
    });

    function qodeInitMediaUploader() {
        if($('.qode-media-uploader').length) {
            $('.qode-media-uploader').each(function() {
                var fileFrame;
                var uploadUrl;
                var uploadHeight;
                var uploadWidth;
                var uploadImageHolder;
                var attachment;
                var removeButton;

                //set variables values
                uploadUrl           = $(this).find('.qode-media-upload-url');
                uploadHeight        = $(this).find('.qode-media-upload-height');
                uploadWidth        = $(this).find('.qode-media-upload-width');
                uploadImageHolder   = $(this).find('.qode-media-image-holder');
                removeButton        = $(this).find('.qode-media-remove-btn');

                if (uploadImageHolder.find('img').attr('src') != "") {
                    removeButton.show();
                    qodeInitMediaRemoveBtn(removeButton);
                }

                $(this).on('click', '.qode-media-upload-btn', function() {
                    //if the media frame already exists, reopen it.
                    if (fileFrame) {
                        fileFrame.open();
                        return;
                    }

                    //create the media frame
                    fileFrame = wp.media.frames.fileFrame = wp.media({
                        title: $(this).data('frame-title'),
                        button: {
                            text: $(this).data('frame-button-text')
                        },
                        multiple: false
                    });

                    //when an image is selected, run a callback
                    fileFrame.on( 'select', function() {
                        attachment = fileFrame.state().get('selection').first().toJSON();
                        removeButton.show();
                        qodeInitMediaRemoveBtn(removeButton);
                        //write to url field and img tag
                        if(attachment.hasOwnProperty('url') && attachment.hasOwnProperty('sizes')) {
                            uploadUrl.val(attachment.url);
                            if (attachment.sizes.thumbnail)
                                uploadImageHolder.find('img').attr('src', attachment.sizes.thumbnail.url);
                            else
                                uploadImageHolder.find('img').attr('src', attachment.url);
                            uploadImageHolder.show();
                        } else if (attachment.hasOwnProperty('url')) {
                            uploadUrl.val(attachment.url);
                            uploadImageHolder.find('img').attr('src', attachment.url);
                            uploadImageHolder.show();
                        }

                        //write to hidden meta fields
                        if(attachment.hasOwnProperty('height')) {
                            uploadHeight.val(attachment.height);
                        }

                        if(attachment.hasOwnProperty('width')) {
                            uploadWidth.val(attachment.width);
                        }
                        $('.qode-input-change').addClass('yes');
                    });

                    //open media frame
                    fileFrame.open();
                });
            });
        }

        function qodeInitMediaRemoveBtn(btn) {
            btn.on('click', function() {
                //remove image src and hide it's holder
                btn.siblings('.qode-media-image-holder').hide();
                btn.siblings('.qode-media-image-holder').find('img').attr('src', '');

                //reset meta fields
                btn.siblings('.qode-media-meta-fields').find('input[type="hidden"]').each(function(e) {
                    $(this).val('');
                });

                btn.hide();
            });
        }
    }

    function qodeInitIconSelectDependency() {

        var iconPack = $('#icon_pack'),
            holders = $('.term-icons-wrap .icon-collection');

        var checkDependency = function() {
            holders.each(function(){
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
    }

})(jQuery);