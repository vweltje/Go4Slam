$(function () {
    $('.datatable').DataTable();

    $('.fa-trash').on('click', function (e) {
        e.preventDefault();
        var link = $(this).attr('data-link');
        console.log(link);
        bootbox.confirm("Are you sure you want to delete this item?", function (response) {
            if (response) {
                window.location.href = link;
            }
        });
    });

    tinymce.init({
        selector: '.text-editor',
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code'
        ]
    });
});

function preview_image() {
    var reader = new FileReader();
    reader.readAsDataURL(document.getElementById('upload-img').files[0]);
    reader.onload = function (e) {
        document.getElementById('preview-img').src = e.target.result;
    };
}