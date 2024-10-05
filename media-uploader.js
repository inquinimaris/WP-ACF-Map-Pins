jQuery(document).ready(function($) {
    $('.upload-image-button').click(function(e) {
        e.preventDefault();

        const target = $(this).data('target');
        const inputField = $('#input-' + target);
        const imagePreview = $('#img-' + target);

        const mediaUploader = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        mediaUploader.on('select', function() {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            inputField.val(attachment.url); 
            imagePreview.attr('src', attachment.url).show();
        });

        mediaUploader.open();
    });
});