<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-calendar-plus-o" aria-hidden="true"></i> <?= isset($event) ? 'Alter' : 'Add' ?> event</h3>
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
                <label for="type">Type</label><br>
                <select name="type" class="form-control">
                    <?php if (isset($types)) : ?>;
                        <option <?= isset($event) ? '' : 'selected' ?> disabled value="">Select a type</option>
                        <?php foreach ($types as $type) : ?>
                        <option value="<?= $type ?>" <?= set_select('type', $type, isset($event['type']) ? true : false) ?>><?= ucfirst($type) ?></option>
                        <?php endforeach;
                    endif;
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" placeholder="Title" maxlength="255" value="<?= set_value('title', isset($event) ? $event['title'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="short_description">Short description</label>
                <textarea class="form-control" name="short_description" placeholder="Short description" maxlength="255"><?= set_value('image', isset($event) ? $event['short_description'] : ''); ?></textarea>
            </div>
			<div class="form-group">
				<label for="short_description">Description</label>
				<textarea class="form-control" name="description" placeholder="Description"><?= set_value('description', isset($score) ? $score['description'] : ''); ?></textarea>
			</div>
            <div class="form-group">
                <label for="short_description">Start date</label>
                <input type="text" class="form-control datetimepicker" name="start_date" value="<?= set_value('start_date', isset($event) ? $event['start_date'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="short_description">End date</label>
                <input type="text" class="form-control datetimepicker" name="end_date" value="<?= set_value('end_date', isset($event) ? $event['end_date'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="upload-img">Image</label>
                <input id="upload-img" onchange="preview_image();" type="file" class="form-control" name="userfile" placeholder="Logo" value="<?= set_value('image', isset($score) ? $score['image'] : ''); ?>">
            </div>
            <div class="form-group">
                <div id="links">
                    <a class="thumbnail" style="display: block; float: none;" href="<?= isset($event['image']) && !empty($event['image']) ? base_url(config_item('src_path_event_images') . $event['image']) : '' ?>" title="<?= isset($event['image']) && !empty($event['image']) ? $event['image'] : '' ?>" data-gallery>
                        <img class="thumbnail" id="preview-img" src="<?= isset($event['image']) && !empty($event['image']) ? base_url(config_item('src_path_event_images') . $event['image']) : '' ?>">
                    </a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>
