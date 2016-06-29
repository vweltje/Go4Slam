<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <?= isset($newsletter) ? 'Alter' : 'Add' ?> newsletter</h3>
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
                <input type="text" class="form-control" name="title" placeholder="Title" maxlength="255" value="<?= set_value('first_name', isset($newsletter) ? $newsletter['title'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="title">Version number</label>
                <input type="number" class="form-control" name="number" placeholder="Version number" maxlength="11" value="<?= set_value('numver', isset($newsletter) ? $newsletter['number'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="short_description">Short description</label>
                <textarea class="form-control" name="short_description" placeholder="Short description" maxlength="255"><?= set_value('image', isset($newsletter) ? $newsletter['short_description'] : ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="short_description">Newsletter</label>
                <?= isset($newsletter) ? '<br><a target="_blank" href="' . base_url(config_item('src_path_newsletters') . $newsletter['pdf']) . '">' . $newsletter['pdf'] . '</a>' : '<input class="form-control" type="file" name="newsletter">' ?>
            </div>
            <?php $this->load->view('pages/inc/scheduler.php', array('scheduled' => false, 'publish_from' => false, 'publish_till' => false)); ?>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>