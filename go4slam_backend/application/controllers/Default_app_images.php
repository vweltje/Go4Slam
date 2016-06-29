<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Default_app_images extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('app_images_model');
    }
    
    public function index() {
        $data = array('default_images' => $this->app_images_model->get_all());
        $this->load_view('pages/default_app_images', $data);
    }
    
    public function add_or_edit_default_image($image_id = false) {
        $data = array();
        $this->form_validation->set_rules('location', 'location', 'required|trim')
                ->set_rules('image', 'image');
        if ($image_id) {
            $data['default_image'] = $this->app_images_model->get($image_id);
        }
        if ($this->form_validation->run()) {
            if (!in_array($this->input->post('location'), config_item('default_app_image_locatios'))) {
                return $this->load_view('pages/alter_default_image', array('error' => 'Disallowed location'));
            }
            $insert = array('location' => $this->input->post('location'));
            $this->load->helper('image_upload');
            $image = do_image_upload(config_item('src_path_default_images'), 10000, 500);
            if (isset($image['error'])) {
                return $this->load_view('pages/alter_default_image', $image);
            }
            $insert['image'] = $image[0];
            if (!$event_id) {
                $id = $this->app_images_model->insert($insert);
            } else {
                $this->app_images_model->update($insert, $image_id);
            }
            $this->session->set_flashdata('message', 'Image successfully saved.');
            redirect('default_app_images');
        }
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
        $data['locations'] = config_item('default_app_image_locatios');
        $this->load_view('pages/alter_default_image', $data);
    }
    
    public function delete_default_image($image_id = false) {
        $data['success'] = false;
        if ($image_id) {
            $image = $this->app_images_model->fields('image')->get($image_id)['image'];
            if ($this->app_images_model->delete($image_id)) {
                unlink(config_item('src_path_default_images') . $image);
                $data['success'] = true;
            }
        }
        $this->session->set_flashdata('message', 'Default image successfully deleted.');
        redirect($this->agent->referrer());
    }
}
