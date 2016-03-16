<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-money"></i> Sponsors <i title="add" onclick="window.location.href = '<?= base_url('add_sponsor') ?>'" class="fa fa-plus"></i></h3>
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
                        <th>Name</th>
                        <th>Logo</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($sponsors) :
                        foreach ($sponsors as $sponsor) :
                            ?>
                            <tr>
                                <td>
                                    <?= $sponsor['name'] ?>
                                </td>
                                <td>
                                    <a target="_blank" href="<?=base_url(config_item('src_path_sponsor_images').$sponsor['image'])?>"><?= $sponsor['image'] ?></a>
                                </td>
                                <td class="manage">
                                    <i title="edit" onclick="window.location.href = '<?= base_url('edit_sponsor/' . $sponsor['id']) ?>'" class="fa fa-pencil"></i>
                                    <i title="remove" data-link="<?= base_url('delete_sponsor/' . $sponsor['id']) ?>" class="fa fa-trash"></i>
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