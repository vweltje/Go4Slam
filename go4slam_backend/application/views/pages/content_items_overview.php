<div class="container">
    <div class="panel panel-default panel-small">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-newspaper-o"></i> Newsletters <i title="add" class="fa fa-plus" onclick="window.location.href='<?=base_url('add_newsletter')?>'"></i></h3>
        </div>
        <div class="panel-body">
            <?php if ($this->session->flashdata('message')) : ?>
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= $this->session->flashdata('message') ?>
                </div>
            <?php endif; ?>
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
            <h3 class="panel-title"><i class="fa fa-newspaper-o"></i> Galleries <i title="add" class="fa fa-plus" onclick="window.location.href='<?=base_url('add_gallery')?>'"></i></h3>
        </div>
        <div class="panel-body">
            <?php if ($this->session->flashdata('message')) : ?>
                <div class="alert alert-success" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?= $this->session->flashdata('message') ?>
                </div>
            <?php endif; ?>
            <table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Short description</th>
                        <th>Gallery</th>
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
                                <td>
                                    
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
</div>