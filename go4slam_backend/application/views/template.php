<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Go4slam admin pannel</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="<?=base_url('resources/css/blueimp.css')?>">
    <link rel="stylesheet" href="<?=base_url('resources/css/style.css')?>">
</head>
<body>
    <?php if ($this->ion_auth->logged_in()) {
        $this->load->view('pages/inc/header');
    } ?>
    
    <main>
        <?=$content?>
    </main>
    
    <script>
        var site_url = '<?= site_url() ?>';
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
    <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
    <script src="//cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>
    <script src="<?=base_url('resources/js/default.js')?>"></script>
    <script src="<?=base_url('resources/js/blueimp.js')?>"></script>
</body>
</html>