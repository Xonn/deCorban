$(function() {
    // Dynamicaly change image preview
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                let widget = $(input).parent().parent();
                var image = e.target.result;

                if (!widget.siblings('.ea-lightbox-thumbnail').length) {
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

    // We want to preview images, so we register
    // the Image Preview plugin, We also register 
    // exif orientation (to correct mobile image
    // orientation) and size validation, to prevent
    // large files from being added
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
    );
    
    let entityId = $('#galery_attachments').data('entityid');

    if (entityId) {
        $.ajax({
            url: '/getImages/' + entityId,
            type: 'GET',
            success: function (data) {
                //if (data.length != 0) {
                    $('.filepond').filepond({
                        //pagination: { sortBy: 'scanStatus', descending: true },
                        server: {
                            process: {url: '/uploadImages/' + entityId, onload: (res) => { console.log(res); }},
                            remove: '/deletdeImage/' + entityId,
                            load: (source, load, error, progress, abort, headers) => {
                                var myRequest = new Request(source);
                                fetch(myRequest).then(function(response) {
                                    response.blob().then(function(myBlob) {
                                        load(myBlob)
                                    });
                                });         
                            },
                        },
                        files: data,
                        labelIdle: 'Faites glisser vos fichiers ou <span class = "filepond--label-action"> Parcourir <span>',
                        labelInvalidField: "Le champ contient des fichiers invalides",
                        labelFileWaitingForSize: "En attente de taille",
                        labelFileSizeNotAvailable: "Taille non disponible",
                        labelFileLoading: "Chargement",
                        labelFileLoadError: "Erreur durant le chargement",
                        labelFileProcessing: "Traitement",
                        labelFileProcessingComplete: "Traitement effectué",
                        labelFileProcessingAborted: "Traitement interrompu",
                        labelFileProcessingError: "Erreur durant le traitement",
                        labelFileProcessingRevertError: "Erreur durant la restauration",
                        labelFileRemoveError: "Erreur durant la suppression",
                        labelTapToCancel: "appuyez pour annuler",
                        labelTapToRetry: "appuyer pour réessayer",
                        labelTapToUndo: "appuyer pour revenir en arrière",
                        labelButtonRemoveItem: "Retirer",
                        labelButtonAbortItemLoad: "Annuler",
                        labelButtonRetryItemLoad: "Recommencer",
                        labelButtonAbortItemProcessing: "Annuler",
                        labelButtonUndoItemProcessing: "Retour en arrière",
                        labelButtonRetryItemProcessing: "Recommencer",
                        labelButtonProcessItem: "Charger",
                        labelMaxFileSizeExceeded: "Le fichier est trop volumineux",
                        labelMaxFileSize: "La taille maximale de fichier est {filesize}",
                        labelMaxTotalFileSizeExceeded: "Taille totale maximale dépassée",
                        labelMaxTotalFileSize: "La taille totale maximale des fichiers est {filesize}",
                        labelFileTypeNotAllowed: "Fichier non valide",
                        fileValidateTypeLabelExpectedTypes: "Attendez {allButLastType} ou {lastType}",
                        imageValidateSizeLabelFormatError: "Type d'image non pris en charge",
                        imageValidateSizeLabelImageSizeTooSmall: "L'image est trop petite",
                        imageValidateSizeLabelImageSizeTooBig: "L'image est trop grande",
                        imageValidateSizeLabelExpectedMinSize: "La taille minimale est {minWidth} × {minHeight}",
                        imageValidateSizeLabelExpectedMaxSize: "La taille maximale est {maxWidth} × {maxHeight}",
                        imageValidateSizeLabelImageResolutionTooLow: "La résolution est trop faible",
                        imageValidateSizeLabelImageResolutionTooHigh: "La résolution est trop élevée",
                        imageValidateSizeLabelExpectedMinResolution: "La résolution minimale est {minResolution}",
                        imageValidateSizeLabelExpectedMaxResolution: "La résolution maximale est {maxResolution}",
                        //instantUpload: false,
                    });
                //}
            }
        });
    }

    
});