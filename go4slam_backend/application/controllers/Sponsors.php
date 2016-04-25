<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sponsors extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('sponsors_model');
    }

    public function index() {
        $data = array();
        $data['sponsors'] = $this->sponsors_model->get_all();
        
        $this->load_view('pages/sponsor_overview', $data);
    }

    public function add_or_edit_sponsor($sponsor_id = false) {
        $data = array();

        $this->form_validation->set_rules('name', 'name', 'trim|required')
                ->set_rules('userfile', 'image');

        if ($sponsor_id) 
            $data['sponsor'] = $this->sponsors_model->fields(array('name', 'image'))->get($sponsor_id);

        if ($this->form_validation->run()) {
            $this->load->model('sponsors_model');
            $this->load->helper('image_upload');

            $insert = array(
                'name' => ucwords($this->input->post('name'))
            );

            if ($_FILES['userfile']['name']) {
                $image = do_image_upload(config_item('src_path_sponsor_images'), 10000, 250);

                if (isset($image['error'])) {
                    return $this->load_view('pages/alter_sponsor', $image);
                }

                $insert['image'] = $image[0];
            }

            if (!$sponsor_id) {
                $this->sponsors_model->insert($insert);
            } 
            else {
                if ($this->sponsors_model->update($insert, $sponsor_id)) {
                    $image = $this->sponsors_model->fields('image')->get($sponsor_id)['image'];

                    unlink(config_item('src_path_sponsor_images').$image);
                }
            }
            
            $this->session->set_flashdata('message', 'Sponsor successfully saved.');
            
            redirect('sponsors');
        }

        if (validation_errors()) {
            $data['error'] = validation_errors();
        }

        $this->load_view('pages/alter_sponsor', $data);
    }

    public function delete_sponsor($sponsor_id = false) {
        $data['success'] = false;

        if ($sponsor_id) {
            $image = $this->sponsors_model->fields('image')->get($sponsor_id)['image'];

            if ($this->sponsors_model->delete($sponsor_id)) {
                unlink(config_item('src_path_sponsor_images').$image);
                
                $data['success'] = true;
            }
        }

        $this->session->set_flashdata('message', 'Sponsor successfully deleted.');

        redirect($this->agent->referrer());
    }
}