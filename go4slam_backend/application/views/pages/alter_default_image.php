<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-eye" aria-hidden="true"></i> <?= isset($default_image) ? 'Edit' : 'Add' ?> default image</h3>
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
                <label for="title">Location</label>
                <select name="location" class="form-control">
                    <?php if (isset($locations)) : ?>
                        <option <?= isset($default_image) ? '' : 'selected' ?> disabled value="">Select a location</option>
                        <?php foreach ($locations as $location) : ?>
                            <option value="<?= $location ?>" <?= set_select('type', $location, isset($default_image['location']) ? true : false) ?>><?= ucfirst($location) ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="upload-img">Image</label>
                <input id="upload-img" onchange="preview_image();" type="file" class="form-control" name="userfile" placeholder="Logo" value="<?= set_value('image', isset($score) ? $score['image'] : ''); ?>">
            </div>
            <div class="form-group">
                <div id="links">
                    <a class="thumbnail" style="display: block; float: none;" href="<?= isset($default_image['image']) && !empty($default_image['image']) ? base_url(config_item('src_path_default_images') . $default_image['image']) : '' ?>" title="<?= isset($default_image['image']) && !empty($default_image['image']) ? $default_image['image'] : '' ?>" data-gallery>
                        <img class="thumbnail" id="preview-img" src="<?= isset($default_image['image']) && !empty($default_image['image']) ? base_url(config_item('src_path_default_images') . $default_image['image']) : '' ?>">
                    </a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>