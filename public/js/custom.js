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