<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pie-chart"></i> <?= isset($newsletter) ? 'Alter' : 'Add' ?> sponsor</h3>
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
                <input type="text" class="form-control" name="title" placeholder="Title" value="<?= set_value('first_name', isset($newsletter) ? $newsletter['title'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="short_description">Short description</label>
                <textarea class="form-control" name="short_description" placeholder="Short description"><?= set_value('image', isset($newsletter) ? $newsletter['short_description'] : ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="short_description">Newsletter</label>
                <?= isset($newsletter) ? '<br><a target="_blank" href="' . base_url(config_item('src_path_newsletters') . $newsletter['pdf']) . '">' . $newsletter['pdf'] . '</a>' : '<input class="form-control" type="file" name="newsletter">' ?>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>