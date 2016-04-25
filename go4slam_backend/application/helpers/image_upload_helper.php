<?php

function do_image_upload($path = '', $max_filesize = 10000, $target_size = 250) {
    $ci = & get_instance();
    $ci->load->helper('string');
    $ci->load->library('upload');
    $ci->load->library('image_lib');
    $names = array();
    $upload_conf = array(
        'upload_path' => $path,
        'max_size' => $max_filesize, // 1000 = 1MB
        'allowed_types' => 'png|PNG|jpeg|JPEG|jpg|JPG',
    );
    if (empty($_FILES)) {
        return array('error' => 'No file to upload');
    }
    foreach ($_FILES['userfile'] as $key => $val) {
        $i = 1;
        foreach ($val as $v) {
            $field_name = "file_" . $i;
            $_FILES[$field_name][$key] = $v;
            $i++;
        }
    }
    unset($_FILES['userfile']);
    foreach ($_FILES as $img_name => $img_data) {
        $img_info = pathinfo($_FILES[$img_name]['name']);
        if (isset($img_info['extension'])) {
            $img_ext = $img_info['extension'];
            $new_name = random_string('alnum', 16) . '.' . $img_ext;
            while (file_exists($upload_conf['upload_path'] . $new_name)) {
                $new_name = random_string('alnum', 16) . '.' . $img_ext;
            }
            $upload_conf['file_name'] = $new_name;
            $ci->upload->initialize($upload_conf);
            if ($ci->upload->do_upload($img_name)) {
                $img = $ci->upload->data();
                if ($img['image_width'] < $target_size || $img['image_height'] < $target_size) {
                    unlink($upload_conf['upload_path'] . $img['file_name']);
                    return array('error' => '<p>The image you chose is to small.</p>');
                }
                $short_side = min($img['image_width'], $img['image_height']);
                $short_width = $short_side === $img['image_width'] ? true : false;
                $long_side_size = max($img['image_width'], $img['image_height']) / ($short_side / $target_size);
                $resize_conf = array(
                    'source_image' => $upload_conf['upload_path'] . $img['file_name'],
                    'width' => $short_width ? $target_size : $long_side_size,
                    'height' => $short_width ? $long_side_size : $target_size,
                    'maintain_ratio' => true,
                    'quality' => '100'
                );
                $ci->image_lib->initialize($resize_conf);
                if ($ci->image_lib->resize()) {
                    $names[] = $new_name;
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
    return $names;
}