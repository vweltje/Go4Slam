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
    
    if ($('#analytics').length === 1) {
        $('.thumbnail').each(function(i, e) {
            var type = $(e).attr('data-type');
            $.post(site_url + 'analytics/get_results', {type: type}, function(response) {
                response = JSON.parse(response);
                if (typeof response['error'] === 'undefined' && typeof response['count'] !== 'undefined') {
                    $(e).find('span').html(response['count']);
                } else {
                    $('.panel').before('<div class="alert alert-danger" role="alert">Something went wrong, please reload this page. <a href=".">Reload</a></div>');
                }
            });
        });
    }
});

function preview_image() {
    var reader = new FileReader();
    reader.readAsDataURL(document.getElementById('upload-img').files[0]);
    reader.onload = function (e) {
        document.getElementById('preview-img').src = e.target.result;
    };
}