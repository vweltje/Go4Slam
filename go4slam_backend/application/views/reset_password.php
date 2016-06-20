<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>GO4SLAM | Reset Password</title>
        <link rel="shortcut icon" type="image/png" href="<?=base_url()?>resources/img/favicon.png"/>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url('resources/css/style.css') ?>">
    </head>
    <body>

        <main>
            <div class="container" id="login-page">
                <div class="panel panel-primary col-6 col-center" style="height: auto">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-sign-in"></i> Reset password</h3>
                    </div>
                    <div class="panel-body">
                        <?php if (!isset($success)) : ?>
                            <div class="box-header with-border">
                                <p class="box-title">Please set your new password below.</p>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($error) && strlen($error) > 0): ?>
                            <div class="alert alert-danger" role="alert"><?= $error ?></div>
                        <?php endif ?>
                        <?php if (isset($run_error) && strlen($run_error) > 0): ?>
                            <div class="alert alert-danger" role="alert"><?= $run_error ?></div>
                        <?php endif ?>
                        <?php if (isset($success)) : ?>
                            <div class="alert alert-success" role="alert">Your password has successfully saved.</div>
                        <?php endif; ?>
                        <?php if (!isset($error) && !isset($success)): ?>
                            <?= form_open() ?>
                            <div class="form-group">
                                <label for="email">New password</label>
                                <input type="password" class="form-control" name="password" id="email" placeholder="New password">
                            </div>
                            <div class="form-group">
                                <label for="password">Confirmation</label>
                                <input type="password" class="form-control" name="passconf" id="password" placeholder="Confirmation">
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Reset password</button>
                            <?= form_close() ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="<?= base_url('resources/js/default.js') ?>"></script>
    </body>
</html>