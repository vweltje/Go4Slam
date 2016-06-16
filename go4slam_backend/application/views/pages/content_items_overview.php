<div class="container">
    <?php if ($this->session->flashdata('message')) : ?>
        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <?= $this->session->flashdata('message') ?>
        </div>
    <?php endif; ?>
    <div class="panel panel-default panel-small">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-newspaper-o"></i> Newsletters <i title="add" class="fa fa-plus" onclick="window.location.href = '<?= base_url('add_newsletter') ?>'"></i></h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Newsletter</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($newsletters) :
                        foreach ($newsletters as $newsleter) :
                            ?>
                            <tr>
                                <td>
                                    <?= $newsleter['title'] ?>
                                </td>
                                <td>
                                    <a target="_blank" href="<?= base_url(config_item('src_path_newsletters') . $newsleter['pdf']) ?>"><?= $newsleter['pdf'] ?></a>
                                </td>
                                <td class="manage">
                                    <i title="edit" onclick="window.location.href = '<?= base_url('edit_newsletter/' . $newsleter['id']) ?>'" class="fa fa-pencil"></i>
                                    <i title="remove" data-link="<?= base_url('delete_newsletter/' . $newsleter['id']) ?>" class="fa fa-trash"></i>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default panel-small">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-picture-o" aria-hidden="true"></i> Galleries <i title="add" class="fa fa-plus" onclick="window.location.href = '<?= base_url('add_gallery') ?>'"></i></h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>description</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($galleries) :
                        foreach ($galleries as $gallery) :
                            ?>
                            <tr>
                                <td>
                                    <?= $gallery['title'] ?>
                                </td>
                                <td style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;max-width: 200px;">
                                    <?= $gallery['description'] ?>
                                </td>
                                <td class="manage">
                                    <i title="edit" onclick="window.location.href = '<?= base_url('edit_gallery/' . $gallery['id']) ?>'" class="fa fa-pencil"></i>
                                    <i title="remove" data-link="<?= base_url('delete_gallery/' . $gallery['id']) ?>" class="fa fa-trash"></i>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel panel-default panel-small">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-star" aria-hidden="true"></i> Scores <i title="add" class="fa fa-plus" onclick="window.location.href = '<?= base_url('add_score') ?>'"></i></h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Players</th>
                        <th>Score</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($scores) :
                        foreach ($scores as $score) :
                            ?>
                            <tr>
                                <td>
                                    <?= $score['player_name'] . ' - ' . $score['player_2_name'] ?>
                                </td>
                                <td>
                                    <?= $score['player_score'] . ' - ' . $score['player_2_score'] ?>
                                </td>
                                <td class="manage">
                                    <i title="edit" onclick="window.location.href = '<?= base_url('edit_score/' . $score['id']) ?>'" class="fa fa-pencil"></i>
                                    <i title="remove" data-link="<?= base_url('delete_score/' . $score['id']) ?>" class="fa fa-trash"></i>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="panel panel-default panel-small">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-calendar" aria-hidden="true"></i> Events <i title="add" class="fa fa-plus" onclick="window.location.href = '<?= base_url('add_event') ?>'"></i></h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Players</th>
                        <th>Score</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($events) :
                        foreach ($events as $event) :
                            ?>
                            <tr>
                                <td>
                                    <?= $event['title'] ?>
                                </td>
                                <td>
                                    <?= $event['start_date'] ?>
                                </td>
                                <td class="manage">
                                    <i title="edit" onclick="window.location.href = '<?= base_url('edit_event/' . $event['id']) ?>'" class="fa fa-pencil"></i>
                                    <i title="remove" data-link="<?= base_url('delete_event/' . $event['id']) ?>" class="fa fa-trash"></i>
                                </td>
                            </tr>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>