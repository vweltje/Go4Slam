<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-picture-o" aria-hidden="true"></i> <?= isset($gallery) ? 'Alter' : 'Add' ?> Gallery</h3>
        </div>
        <div class="panel-body">
            <?php if (isset($error) && $error) : ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <?= form_open_multipart() ?>
            <div class="form-group">
                <label for="name">Title</label>
                <input type="text" class="form-control" name="title" placeholder="Title" maxlength="255" value="<?= set_value('first_name', isset($gallery) ? $gallery['title'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="image">Short description</label>
                <textarea class="form-control" name="description" maxlength="255"><?= isset($gallery) ? $gallery['description'] : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Images / videos</label>
                <input id="files" onchange="read_files();" class="form-control" type="file" name="userfile[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>

            <?php $this->load->view('pages/inc/blueimp'); ?>

            <div id="links">
                <?php
                if (isset($gallery) && count($gallery['items']) > 0) :
                    foreach ($gallery['items'] as $item) :
                        ?>
                        <a class="thumbnail" href="<?= site_url(config_item('src_path_gallery_images') . $item['src']) ?>" title="<?= $item['src'] ?>" data-gallery>
                            <i onclick="event.preventDefault(); event.stopPropagation(); remove_gallery_img(<?= $item['id'] ?>, this)" class="fa fa-times-circle" aria-hidden="true"></i>
                            <img width="150" src="<?= site_url(config_item('src_path_gallery_images') . $item['src']) ?>" alt="<?= $item['src'] ?>">
                        </a>
                        <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    var data = [];
    function read_files() {
        var files = document.getElementById('files').files;
        for (i = 0;
                i < files.length;
                i++) {
            var reader = new FileReader();
            reader.onloadend = function (e) {
                if (e.target.readyState == FileReader.DONE) {
                    console.log(btoa(e.target.result));
                    $('#links').append('<a class="thumbnail" href="data:image/png;base64,' + btoa(e.target.result) + '" title="' + btoa(e.target.result) + '" data-gallery><img width="150" src="data:image/png;base64,' + btoa(e.target.result) + '" alt="' + btoa(e.target.result) + '"></a>');
                }
            };
            reader.readAsBinaryString(files[i]);
        }
    }

    function remove_gallery_img(id, element) {
        $.post('<?= site_url('content/ajax_delete_gallery_image') ?>/' + id, function (response) {
            response = JSON.parse(response);
            if (response['success']) {
                $(element).parent().remove();
            } else {
                alert('KABOEM!!!');
            }
        });
    }
</script>