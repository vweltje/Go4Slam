<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-cog" aria-hidden="true"></i> Settings</h3>
        </div>
        <div class="panel-body">
            <?php if (isset($error) && $error) : ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <?= form_open_multipart() ?>
            <label>Change password</label>
            <div class="well">
                <div class="form-group">
                    <label for="title">Current</label>
                    <input type="password" class="form-control" name="password" placeholder="Password" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="title">New</label>
                    <input type="password" class="form-control" name="new_pass" placeholder="New password" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="short_description">Confirmation</label>
                    <input type="password" class="form-control" name="conf_pass" placeholder="Password confirmation" maxlength="100">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
        </div>
    </div>
</div>