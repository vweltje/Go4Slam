<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container" id="login-page">
    <div class="panel panel-primary col-6 col-center" style="height: auto">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-sign-in"></i> Login</h3>
        </div>
        <div class="panel-body">
            <div class="box-header with-border">
                <p class="box-title">Please login with your email address and password below. <br><a href="<?=base_url('user/password_forgotten')?>">Forgot your password?</a></p>
            </div>
            <?php if (isset($error) && strlen($error) > 0): ?>
            <div class="alert alert-danger" role="alert"><?= $error ?></div>
            <?php endif ?>
            <?php if (isset($_SESSION['message'])) : ?>
                <div class="alert alert-success" role="alert"><?php echo json_encode($_SESSION['message'])?></div>
            <?php endif; ?>
            <?= form_open() ?>
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="E-mail">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="remember" value="remember"> Remember me
                </label>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Login</button>
            <?= form_close() ?>
        </div>
    </div>
</div>