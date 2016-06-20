<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pie-chart"></i> <?= isset($event) ? 'Alter' : 'Add' ?> newsletter</h3>
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
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" placeholder="Title" maxlength="255" value="<?= set_value('first_name', isset($event) ? $event['title'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="short_description">Short description</label>
                <textarea class="form-control" name="short_description" placeholder="Short description" maxlength="255"><?= set_value('image', isset($event) ? $event['short_description'] : ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="short_description">Start date</label>
                <input type="text" class="form-control datetimepicker" name="start_date" value="<?= set_value('start_date', isset($event) ? $event['start_date'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="short_description">End date</label>
                <input type="text" class="form-control datetimepicker" name="end_date" value="<?= set_value('end_date', isset($event) ? $event['end_date'] : ''); ?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>