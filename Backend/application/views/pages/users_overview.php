<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= $type === 'cms' ? '<i class="fa fa-user"></i> CMS' : '<i class="fa fa-mobile"></i> App' ?> Users <i title="add" onclick="window.location = '<?= base_url('add_' . $type . '_user') ?>'" class="fa fa-plus"></i></h3>
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
                        <th>Email</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($users) :
                        foreach ($users as $user) :
                            ?>
                            <tr>
                                <td>
                                    <?= $user['first_name'] . ' ' . ($user['prefix'] !== '' ? $user['prefix'] . ' ' : '') . $user['last_name'] ?>
                                </td>
                                <td>
                                    <?= $user['email'] ?>
                                </td>
                                <td class="manage">
                                    <i title="edit" onclick="window.location.href = '<?= base_url('edit_cms_user/' . $user['id']) ?>'" class="fa fa-pencil"></i>
                                    <i title="remove" onclick="window.location.href = '<?= base_url('delete_user/' . $user['id']) ?>'" class="fa fa-trash"></i>
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