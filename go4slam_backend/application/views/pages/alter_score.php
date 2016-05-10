<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-pie-chart"></i> <?= isset($score) ? 'Alter' : 'Add' ?> score</h3>
        </div>
        <div class="panel-body">
            <?php if (isset($error) && $error) : ?>
                <div class="alert alert-danger" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= $error ?>
                </div>
            <?php endif; ?>
            <?= form_open_multipart() ?>
            <label>Player 1</label>
            <div class="well">
                <div class="form-group">
                    <label for="title">Name</label>
                    <input type="text" class="form-control" name="player_name" placeholder="Name" value="<?= set_value('player_name', isset($score) ? $score['player_name'] : ''); ?>">
                </div>
                <div class="form-group">
                    <label for="title">Score</label> <small>for example 2/4</small>
                    <input type="text" class="form-control" name="player_score" placeholder="Score" value="<?= set_value('player_score', isset($score) ? $score['player_score'] : ''); ?>">
                </div>
            </div>
            <label>Player 2</label>
            <div class="well">
                <div class="form-group">
                    <label for="title">Name</label>
                    <input type="text" class="form-control" name="player_2_name" placeholder="Name" value="<?= set_value('player_2_name', isset($score) ? $score['player_2_name'] : ''); ?>">
                </div>
                <div class="form-group">
                    <label for="title">Score</label> <small>for example 2/4</small>
                    <input type="text" class="form-control" name="player_2_score" placeholder="Score" value="<?= set_value('player_2_score', isset($score) ? $score['player_2_score'] : ''); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="short_description">Description</label>
                <textarea class="form-control" name="description" placeholder="Description"><?= set_value('image', isset($score) ? $score['description'] : ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="short_description">Image</label>
                <input id="upload-img" onchange="preview_image();" type="file" class="form-control" name="userfile" placeholder="Logo" value="<?= set_value('image', isset($score) ? $score['image'] : ''); ?>">
            </div>
            <div class="form-group">
                <div id="links">
                    <a class="thumbnail" style="display: block; float: none;" href="<?= isset($score['image']) ? base_url(config_item('src_path_score_images') . $score['image']) : '' ?>" title="<?= isset($score['image']) ? $score['image'] : '' ?>" data-gallery>
                        <img class="thumbnail" id="preview-img" src="<?= isset($score['image']) ? base_url(config_item('src_path_score_images') . $score['image']) : '' ?>">
                    </a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Save</button>
            <?= form_close() ?>
            
            <?php $this->load->view('pages/inc/blueimp'); ?>
        </div>
    </div>
</div>