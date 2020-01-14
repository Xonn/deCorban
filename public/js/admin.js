$(function() {
    $('input[type="datetime-local"]').attr('type', 'text');

    $.datetimepicker.setLocale('fr');
    $('.datepicker').datetimepicker({
        format: 'Y-m-d\\TH:i',
        timepicker: false,
        //inline: true,
        theme: 'dark',
    });
});