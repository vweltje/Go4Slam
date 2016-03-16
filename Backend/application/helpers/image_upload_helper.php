<?php

function do_image_upload($path = '', $max_filesize = 10000, $target_size = 250) {
    $ci =& get_instance();
    $config['upload_path'] = $path;
    $config['max_size'] = $max_filesize; // 1000 = 1 MB
    $config['allowed_types'] = 'png|PNG|jpeg|JPEG|jpg|JPG';
    $uploadedFile = pathinfo($_FILES['image']['name']);

    if (isset($uploadedFile['extension'])) {
        $ci->load->helper('string');

        $fileExtension = $uploadedFile['extension'];
        $newFilename = random_string('alnum', 16) . '.' . $fileExtension;

        while (file_exists($config['upload_path'] . $newFilename)) {
            $newFilename = random_string('alnum', 16) . '.' . $fileExtension;
        }

        $config['file_name'] = $newFilename;

        $ci->load->library('upload', $config);

        if ($ci->upload->do_upload('image')) {
            $file = $ci->upload->data();

            if ($file['image_width'] < $target_size || $file['image_height'] < $target_size) {
                unlink($config['upload_path'] . $file['file_name']);

                return array('error' => '<p>The image you chose is too small.</p>');
            }

            $short_side = min($file['image_width'], $file['image_height']);
            $short_width = ($short_side === $file['image_width'] ? true : false);
            $long_side_size = max($file['image_width'], $file['image_height']) / ($short_side / $target_size);

            //$configImg['image_library'] = 'GD';
            $configImg['source_image'] = $config['upload_path'] . $file['file_name'];
            $configImg['width'] = $short_width ? $target_size : $long_side_size;
            $configImg['height'] = $short_width ? $long_side_size : $target_size;
            $configImg['maintain_ratio'] = true;
            $configImg['quality'] = '100';

            $ci->load->library('image_lib');
            $ci->image_lib->initialize($configImg);

            if ($ci->image_lib->resize()) {
                return array('file_name' => $newFilename);
            } else {
                return array('error' => $ci->image_lib->display_errors());
            }
        } else {
            return array('error' => $ci->upload->display_errors());
        }
    } else {
        return false;
    }
}