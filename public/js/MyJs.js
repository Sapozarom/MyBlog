//bootstrap tooltip
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    });

// show uploaded image in post create/edit
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#uploadedImage')
                .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
}