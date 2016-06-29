<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-eye" aria-hidden="true"></i> Default images <i title="add" onclick="window.location = '<?= base_url('add_default_image') ?>'" class="fa fa-plus"></i></h3>
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
                        <th>Location</th>
                        <th>Image</th>
                        <th>Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($default_images) :
                        foreach ($default_images as $img) :
                            ?>
                            <tr>
                                <td>
                                    <?= $img['location'] ?>
                                </td>
                                <td>
                                    <?= $img['image'] ?>
                                </td>
                                <td class="manage">
                                    <i title="edit" onclick="window.location.href = '<?= base_url('edit_default_image/' . $img['id']) ?>'" class="fa fa-pencil"></i>
                                    <i title="remove" data-link="<?= base_url('delete_default_image/' . $img['id']) ?>" class="fa fa-trash"></i>
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