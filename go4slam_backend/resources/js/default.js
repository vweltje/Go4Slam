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
        $('.thumbnail').each(function (i, e) {
            var type = $(e).attr('data-type');
            $.post(site_url + 'analytics/get_results', {type: type}, function (response) {
                response = JSON.parse(response);
                if (typeof response['error'] === 'undefined' && typeof response['count'] !== 'undefined') {
                    $(e).find('span').html(response['count']);
                } else {
                    $('.panel').before('<div class="alert alert-danger" role="alert">Something went wrong, please reload this page. <a href=".">Reload</a></div>');
                }
            });
        });
    }

    jQuery.datetimepicker.setLocale('nl');
    $('.datetimepicker').datetimepicker({
        format: 'Y-m-d H:i:s'
    });

    if ($('#scheduler').length > 0) {
        schedule_fields();
        $('#scheduler input[type="checkbox"]').on('change', schedule_fields);
    }
});

function preview_image(el) {
    var reader = new FileReader();
    reader.readAsDataURL((typeof el !== 'undefined' ? el : document.getElementById('upload-img')).files[0]);
    reader.onload = function (e) {
        if (typeof el !== 'undefined') {
            console.log($(el).parent().index());
            $('.thumbnail:nth-child(' + ($(el).parent().index() + 1) + ')').find('img').attr('src', e.target.result);
            return;
        }
        document.getElementById('preview-img').src = e.target.result;
    };
}

function schedule_fields() {
    if ($('#scheduler input[type="checkbox"]').is(':checked')) {
        $('#scheduler #scheduler-fields').slideDown();
    } else {
        $('#scheduler #scheduler-fields').slideUp();
    }
}