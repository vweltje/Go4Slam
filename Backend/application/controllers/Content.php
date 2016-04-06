<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('news_items_model');
    }
    
    public function index() {
        $data['newsletters'] = $this->news_items_model->get_all();
        
        $this->load_view('pages/content_items_overview', $data);
    }
    
    public function add_or_edit_newsletter($news_item_id = false) {
        $data = array();
        $this->form_validation->set_rules('title', 'title', 'trim|required')
                ->set_rules('short_description', 'short_description', 'trim|required')
                ->set_rules('newsletter', 'newsletter');
        if ($news_item_id) {
            $data['news_item'] = $this->news_items_model->fields(array('name', 'description', 'pdf'))->get($news_item_id);
        }
        if ($this->form_validation->run()) {
            $insert = array(
                'title' => $this->input->post('title'),
                'short_description' => $this->input->post('short_description')
            );
            $this->load->helper('file_upload_helper');
            $insert['pdf'] = do_file_upload(config_item('src_path_newsletters'), 'pdf', 'newsletter');
            if (isset($insert['pdf']['error'])) {
                return $this->send_error($insert['pdf']['error']);
            }
            $insert['pdf'] = $insert['pdf']['file_name'];
            if (!$news_item_id) {
                $this->news_items_model->insert($insert);
            } 
            else {
                $this->news_items_model->update($insert, $news_item_id);
            }
            $this->session->set_flashdata('message', 'Newsletter successfully saved.');
            redirect('newsletters');
        }
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
        $this->load_view('pages/alter_newsletter', $data);
    }

    public function delete_newsletter($news_item_id = false) {
        $data['success'] = false;
        if ($news_item_id) {
            $newsletter = $this->sponsors_model->fields('pdf')->get($news_item_id)['pdf'];
            if ($this->sponsors_model->delete($news_item_id)) {
                unlink(config_item('src_path_newsletters').$newsletter);
                $data['success'] = true;
            }
        }
        $this->session->set_flashdata('message', 'Newsletter successfully deleted.');
        redirect($this->agent->referrer());
    }
    
    public function add_or_edit_gallery() {
        $data = array();
        $this->load_view('pages/alter_gallery', $data);
    }
}
