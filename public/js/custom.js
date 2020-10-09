// Edit avatar on image click (without input displayed)
$('#avatar, #edit-avatar').click(function(){
	$('#registration_imageFile').click();
});

// Remove avatar (display default)
$('#delete-avatar').click(function(){
    $('#avatar').attr('src', '../../assets/blank-avatar.png');
	$('#registration_imageFile').val('');
});

// Overlay edit avatar
$('.user.overlay, #delete-avatar').hover(function(){
    $('#edit-avatar, #delete-avatar').toggle();
});

// Dynamicaly change avatar preview
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#avatar').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#registration_imageFile").change(function () {
    readURL(this);
});

/* AJAX POST COMMENTS */

$('.comment_reply').on('click', function(e) {
    e.preventDefault();
    $this_link = $(this);

    $.ajax({
        method: 'GET',
        url: Routing.generate('form_comment') + '/' + $this_link.attr('data-galery-id') + '/' + $this_link.attr('data-comment-id'),
        success: function(data) {

            if ($(document).find('#replyTo').length) {
                $('#replyTo').remove();
            }

            var $data = $(data).appendTo($this_link.closest('.message-inner'));
            $data.wrap('<div id="replyTo"></div>').show('slow');
        
            $('html, body').animate({
                scrollTop: $('#replyTo').parent('div').offset().top
            }, 500);   
        }

    });
});
$.fn.serializeFormJSON = function() {
    let o = {};
    let a = this.serializeArray();
    $.each(a, function () {
        let name = this.name;
        let value = this.value || '';
        if (o[name]) {
            if (!Array.isArray(o[name])) {
                o[name] = [o[name]];
            }
            o[name].push(value);
        } else {
            o[name] = value;
        }
    });
    return o;
};
$('form.comment-form').live('submit', function(e) {
    e.preventDefault();
    let form = $(this);
    let data = form.serializeFormJSON();

    $.ajax({
        method: 'POST',
        url: form.attr('action'),
        data: data,
        success: function(data) {
            let target = data.reply ? form.closest('li') : '#singlecomments';
            $(data.view).appendTo(target);
             
            $('#replyTo').hide('slow', function() {
                $(this).remove();
            });
            $('#comment_message').val('');
        }
    });
});

/* TEXTAREA CHAR COUNT */

let textarea = $('textarea[maxlength]');
let maxLength = textarea.attr('maxlength');

// Add counter span
$('<span class="count">0/' + maxLength + '</span>').insertAfter(textarea);

$(textarea).on('change keydown keyup paste', function() {
    $('.count').text(textarea.val().length + '/' + maxLength);
});

/* CONTACT MESSAGE TEMPLATE */

let subject = $('#contact_subject');

$(subject).on('change', function() {
    if (subject.val() === "Candidature") {
        let textarea = $('textarea[maxlength]');
        textarea.val('Date de naissance : \nNationalité : \nSite internet/book : \nÀ propos de vous : \n');
    }
});

$(document).ready(function() {
	
	setTimeout(function(){
        $('#loader-wrapper').fadeOut(500, function() {
            $('body').addClass('loaded');
        });
	}, 1500);
	
});

/* AJAX GALERY LIKE */
// $('#galery-like').on('toggle', function(e) {
//     $('.liked').show();
//     $('.not-liked').hide();
// });

$('#galery-like').on('click', function(e) {
    e.preventDefault();

    if (!$(this).hasClass('not-active')) {
        var galeryId = $(this).attr('data-galery-id');
        var csrfToken = $(this).attr('data-csrf-token');

        $.ajax({
            method: 'POST',
            url: Routing.generate('galery.like', {'galery': galeryId}),
            data: {'csrfToken': csrfToken},
            success: function(likes) {
                $('#likes-count').text(likes);
                
                var show = $('.show');
                var hide = $('.hide');

                show.addClass('hide').removeClass('show');
                hide.addClass('show').removeClass('hide');
            }

        });
    }
});