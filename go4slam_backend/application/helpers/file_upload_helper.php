<?php

function do_file_upload($path, $allowed_types, $post_name) {
    $ci =& get_instance();
    $config['upload_path'] = $path;
    $config['allowed_types'] = $allowed_types;
    if (!isset($_FILES[$post_name])) {
        return array('error' => 'No file to upload');
    }
    $file_info = pathinfo($_FILES[$post_name]['name']);
    if (isset($file_info['extension'])) {
        $ci->load->helper('string');
        $file_extension = $file_info['extension'];
        $new_filename = random_string('alnum', 16) . '.' . $file_extension;
        while (file_exists($config['upload_path'] . $new_filename)) {
            $new_filename = random_string('alnum', 16) . '.' . $file_extension;
        }
        $config['file_name'] = $new_filename;
        $ci->load->library('upload', $config);
        if (!$ci->upload->do_upload($post_name)) {
            return array('error' => $ci->image_lib->display_errors());
        }
        return array('file_name' => $new_filename);
    }
    return false;
}
