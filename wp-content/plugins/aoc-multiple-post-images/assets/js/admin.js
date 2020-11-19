var frame;
jQuery(document).on('click', '.aoc_add_image_link', function (event) {
    event.preventDefault();

    // If the media frame already exists, reopen it.
    if (frame) {
        frame.open();
        return;
    }

    // Create a new media frame
    frame = wp.media({
        title: 'Select or upload the image',
        button: {
            text: 'Use this media'
        },
        multiple: false  // Set to true to allow multiple files to be selected
    });


    // When an image is selected in the media frame...
    frame.on('select', function () {

        // Get media attachment details from the frame state
        var attachment = frame.state().get('selection').first().toJSON();

        // Send the attachment URL to our custom image input field.
        jQuery('.aoc-img-container').append('<div class="aoc-img-wrap"><img src="' + attachment.url + '" alt="" style="max-width:150px;"/><input type="hidden" name="aoc_images[]" id="aoc_img_input_' + attachment.id + '" value="' + attachment.id + '"><button type="button" data-img-id="' + attachment.id + '" class="aoc-del-img">X</button></div>');

    });

    // Finally, open the modal on click
    frame.open();
});

jQuery(document).on('click', '.aoc-del-img', function(){
   jQuery(this).parent().remove(); 
});