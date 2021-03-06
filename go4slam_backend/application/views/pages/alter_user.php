<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= isset($user) ? '<i class="fa fa-user-md" aria-hidden="true"></i> alter' : '<i class="fa fa-user-plus" aria-hidden="true"></i> add' ?> <?= $type === 'cms' ? 'cms' : 'app' ?> user</h3>
        </div>
        <div class="panel-body">
            <?php if (isset($error) && $error) : ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <?= form_open_multipart() ?>
            <?php if (isset($type) && $type !== 'cms') : ?>
                <input type="checkbox" name="is_coach" value="true" <?= set_value('is_coach', isset($is_coach) ? $is_coach ? 'checked' : '' : ''); ?>><label style="margin-left:5px;">is coach</label><br>
            <?php endif; ?>
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
            <?php if (isset($type) && $type !== 'cms') : ?>
                <div id="not_coach">
                    <label>Ranking</label>
                    <div class="well">
                        <?php for ($i = 1; $i <= 3; $i++) : ?>
                            <label><?= $i ?></label> <small>leave empty if you don't need it</small>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="ranking_<?= $i ?>_name" placeholder="Ranking name" maxlength="100" value="<?= set_value('ranking_<?= $i ?>_name', isset($user) && isset($user['ranking'][$i]['name']) ? $user['ranking'][$i]['name'] : ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="name">Score</label>
                                <input type="number" class="form-control" name="ranking_<?= $i ?>_score" placeholder="Ranking score" maxlength="11" value="<?= set_value('ranking_<?= $i ?>_score', isset($user) && $user['ranking'][$i]['score'] ? $user['ranking'][$i]['score'] : ''); ?>">
                            </div>
                        <?php endfor; ?>
                    </div>
                    <label>Pictures</label>
                    <div class="well">
                        <div class="form-group form-group-small">
                            <label for="name">Profile</label>
                            <input class="upload-img" onchange="preview_image(this);" type="file" class="form-control" name="image" placeholder="Profile picture">
                        </div>
                        <div class="form-group form-group-small">
                            <label for="name">Cover</label>
                            <input class="upload-img" onchange="preview_image(this);" type="file" class="form-control" name="cover_image" placeholder="Cover picture">
                        </div>
                        <div id="links" style="margin-top: 80px;">
                            <a class="thumbnail thumbnail-small" style="display: block;" href="<?= isset($user['image']) ? base_url(config_item('src_path_profile_pictures') . $user['image']) : '' ?>" title="<?= isset($user['image']) ? $user['image'] : '' ?>" data-gallery>
                                <img src="<?= isset($user['image']) ? base_url(config_item('src_path_profile_pictures') . $user['image']) : '' ?>">
                            </a>
                            <a class="thumbnail thumbnail-small" style="display: block;" href="<?= isset($user['cover_image']) ? base_url(config_item('src_path_cover_images') . $user['cover_image']) : '' ?>" title="<?= isset($user['cover_image']) ? $user['cover_image'] : '' ?>" data-gallery>
                                <img src="<?= isset($user['cover_image']) ? base_url(config_item('src_path_cover_images') . $user['cover_image']) : '' ?>">
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> <?= isset($user) ? 'Save' : 'Create' ?></button>
            <?= form_close() ?>
        </div>
    </div>
</div>