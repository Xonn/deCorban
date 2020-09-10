$(function() {
    // Dynamicaly change image preview
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                let widget = $(input).parent().parent();
                var image = e.target.result;

                if (!$('.ea-lightbox-thumbnail').length) {
                   addPreview(widget.parent('.ea-vich-image'), image);
                }

                // Update image preview & lightbox
                widget.siblings('.ea-lightbox-thumbnail').children('img').attr('src', image);
                widget.siblings('.ea-lightbox').children('img').attr('src', image);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addPreview(widget, image) {
        widget.prepend('<div id="ea-lightbox-Galery_thumbnailFile" class="ea-lightbox"><img src=""></div>');
        widget.prepend('<a href="#" class="ea-lightbox-thumbnail" data-featherlight="#ea-lightbox-Galery_thumbnailFile" data-featherlight-close-on-click="anywhere"><img src=""></a>');
    }

    $('.preview input[type="file"]').change(function () {
        readURL(this);
    });
    
    /* INPUTFILE BOOTSTRAP */

    let view = $('form').data('view');
    if (view && view != 'new') {
        const galery_id = $('form').data('entityId');

        $.ajax({
            method: 'GET',
            url: '/getImages/' + galery_id,
            success: function(data) {
                console.log(data);
                pictures = data;
            }
    
        }).done(function(data) {
            inputfile_load(data, galery_id);
        });
    }

    function inputfile_load(data, id) {
        const pictureInput = $("#galery_pictureFiles");
        pictureInput.fileinput({
            showCaption: false,
            showUploadedThumbs: false,
            uploadUrl: '/uploadImages/' + id,
            enableResumableUpload: true,
            resumableUploadOptions: {
                // uncomment below if you wish to test the file for previous partial uploaded chunks
                // to the server and resume uploads from that point afterwards
                // testUrl: "http://localhost/test-upload.php"
            },
            allowedFileTypes: ['image'],    // allow only images
            showCancel: true,
            initialPreviewAsData: true,
            overwriteInitial: false,
            initialPreview: data.preview,
            initialPreviewConfig: data.config,
            theme: 'explorer-fas',
            language: 'fr',
            }).on('fileuploaded', function(event, previewId, index, fileId) {
                console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
            }).on('fileuploaderror', function(event, data, msg) {
                console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
            }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
                console.log('File Batch Uploaded', preview, config, tags, extraData);
            }).on('filesorted', function(event, params) {
                // $.ajax({
                //     method: 'GET',
                //     url: '/imagesPosition',
                //     success: function(data) {
                //         pictures = data;
                //     }
            
                // });
        });
    }

});