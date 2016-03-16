<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-info"></i> About Go4Slam <i title="add" class="fa fa-plus"></i></h3>
        </div>
        <div class="panel-body">
            <?= form_open() ?>
            <div class="form-group">
                <textarea name="text" class="text-editor"><?= isset($text) ? $text->text : '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>