<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pie-chart"></i> <?= isset($sponsor) ? 'Alter' : 'Add' ?> sponsor</h3>
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
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" placeholder="First name" value="<?= set_value('first_name', isset($sponsor) ? $sponsor['name'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="image">Logo</label>
                <input id="upload-img" onchange="preview_image();" type="file" class="form-control" name="userfile" placeholder="Logo" value="<?= set_value('image', isset($sponsor) ? $sponsor['image'] : ''); ?>">
            </div>
            <div class="form-group">
                <div id="links">
                    <a class="thumbnail" style="display: block; float: none;" href="<?= isset($sponsor['image']) ? base_url(config_item('src_path_sponsor_images') . $sponsor['image']) : '' ?>" title="<?= isset($sponsor['image']) ? $sponsor['image'] : '' ?>" data-gallery>
                        <img class="thumbnail" id="preview-img" src="<?= isset($sponsor['image']) ? base_url(config_item('src_path_sponsor_images') . $sponsor['image']) : '' ?>">
                    </a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>

            <?php $this->load->view('pages/inc/blueimp'); ?>
        </div>
    </div>
</div>