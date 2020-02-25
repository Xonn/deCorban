$(function() {
    $('input[type="datetime-local"]').attr('type', 'text');

    $.datetimepicker.setLocale('fr');
    $('.datepicker').datetimepicker({
        format: 'Y-m-d\\TH:i',
        timepicker: false,
        //inline: true,
        theme: 'dark',
    });

    document.querySelectorAll( '.awesome-ckeditor textarea' )
             .forEach(function(el){
                 el.removeAttribute('required');
                 ClassicEditor
                     .create( el ,{
                        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
                     })
                     .then( function (editor) {
                         var div = el.parentNode.querySelector('.ck-editor__editable');
                         //div.style.backgroundColor = 'white'; 
                         //div.style.minHeight = '300px';
                     } )
                     .catch( function (error) {
                         console.error( error );
                     });
                });

    // Dynamicaly change image preview
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                let widget = $(input).parent().parent();
                
                // Update image preview & lightbox
                widget.siblings('.easyadmin-thumbnail').children('img').attr('src', e.target.result);
                widget.siblings('.easyadmin-lightbox').children('img').attr('src', e.target.result);

                $(input).closest('div.col-6').addClass('edited');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('.preview input[type="file"]').change(function () {
        readURL(this);
    });

    let input = $("#galery_pictureFiles");

    input.fileinput({
        showCaption: false,
        showUploadedThumbs: false,
        uploadUrl: 'http://0.0.0.0:8000/uploadImages',
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
        theme: 'explorer-fas',
        language: 'fr',
        }).on('fileuploaded', function(event, previewId, index, fileId) {
            console.log('File Uploaded', 'ID: ' + fileId + ', Thumb ID: ' + previewId);
        }).on('fileuploaderror', function(event, data, msg) {
            console.log('File Upload Error', 'ID: ' + data.fileId + ', Thumb ID: ' + data.previewId);
        }).on('filebatchuploadcomplete', function(event, preview, config, tags, extraData) {
            console.log('File Batch Uploaded', preview, config, tags, extraData);
    });

    let view = $('form').data('view');
    if (view && view != 'new') {
        let galery_id = $('form').data('entityId');
        let pictures;

        $.ajax({
            method: 'GET',
            url: 'http://0.0.0.0:8000/getImages/' + galery_id,
            success: function(data) {
                console.log(data)
                pictures = data;
            }
    
        }).done(function() {
            input.fileinput({
                initialPreview: pictures.preview,
                initialPreviewConfig: pictures.config,
            });
        });
    }

    input.on('filesorted', function(event, params) {
        // $.ajax({
        //     method: 'GET',
        //     url: 'http://0.0.0.0:8000/imagesPosition',
        //     success: function(data) {
        //         pictures = data;
        //     }
    
        // });
        input.fileinput({previewClass: 'bg-warning'});
        console.log('File sorted ', params.previewId, params.oldIndex, params.newIndex, params.stack);
    });
});