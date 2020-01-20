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
                     } );
             });

});