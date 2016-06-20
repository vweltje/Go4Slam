<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container" id="login-page">
    <div class="panel panel-primary col-6 col-center" style="height: auto">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-unlock-alt"></i> Forgotten password</h3>
        </div>
        <div class="panel-body">
            <div class="box-header with-border" style="margin-bottom: 10px;">
                Please enter your email address below.
            </div>
            <?php if (isset($error) && strlen($error) > 0): ?>
                <div class="alert alert-danger" role="alert"><?= $error ?></div>
            <?php endif ?>
            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success" role="alert"><?php echo json_encode($_SESSION['message']) ?></div>
            <?php endif; ?>
            <?= form_open() ?>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="E-mail">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-unlock-alt"></i> Send verification</button>
            <?= form_close() ?>
        </div>
    </div>
</div>