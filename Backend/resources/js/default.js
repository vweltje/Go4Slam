$(function () {
    $('.datatable').DataTable();
});

function preview_image() {
    var reader = new FileReader();
    reader.readAsDataURL(document.getElementById('upload-img').files[0]);
    reader.onload = function (e) {
        document.getElementById('preview-img').src = e.target.result;
    };
};