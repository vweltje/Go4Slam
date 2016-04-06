<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pie-chart"></i> <?= isset($gallery) ? 'Alter' : 'Add' ?> Gallery</h3>
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
                <input type="text" class="form-control" name="title" placeholder="Title" value="<?= set_value('first_name', isset($gallery) ? $gallery['title'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="image">Short description</label>
                <textarea class="form-control" name="short_description"><?=isset($gallery) ? $gallery['short_description'] : ''?></textarea>
            </div>
            <div class="form-group">
                <input class="form-control" type="file" name="items[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>