<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pie-chart"></i> <?= isset($user) ? 'alter' : 'add' ?> <?= $type === 'cms' ? 'cms' : 'app' ?> user</h3>
        </div>
        <div class="panel-body">
            <?php if (isset($error) && $error) : ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <?= form_open() ?>
            <div class="form-group">
                <label for="name">First name</label>
                <input type="text" class="form-control" name="first_name" placeholder="First name" maxlength="100" value="<?= set_value('first_name', isset($user) ? $user['first_name'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="name">Prefix name</label>
                <input type="text" class="form-control" name="prefix" placeholder="Prefix name" maxlength="20" value="<?= set_value('prefix', isset($user) ? $user['prefix'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="name">Last name</label>
                <input type="text" class="form-control" name="last_name" placeholder="Last name" maxlength="100" value="<?= set_value('last_name', isset($user) ? $user['last_name'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="name">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Email" maxlength="100" value="<?= set_value('email', isset($user) ? $user['email'] : ''); ?>">
            </div>
            <div class="form-group">
                <label for="name">Password</label> <small>minimal length of 8</small>
                <input type="password" class="form-control" name="password" placeholder="Password" maxlength="100" value="<?= set_value('password'); ?>">
            </div>
            <div class="form-group">
                <label for="name">Password confirmation</label>
                <input type="password" class="form-control" name="passconf" placeholder="Password confirmation" maxlength="100" value="<?= set_value('passconf'); ?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> <?= isset($user) ? 'Save' : 'Create' ?></button>
            <?= form_close() ?>
        </div>
    </div>
</div>