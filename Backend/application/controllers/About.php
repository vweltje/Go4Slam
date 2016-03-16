<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $data = array();
        
        $this->form_validation->set_rules('text', 'text', 'required');
        
        $data['text'] = $this->db->select('text')->limit(1)->order_by('id', 'desc')->get('about')->row();

        if ($this->form_validation->run()) {
            if ($this->db->empty_table('about')) 
                $success = $this->db->insert('about', array('text' => $this->input->post('text')));
            
            if ($success) {
                $this->session->set_flashdata('message', 'About text is successfully updated.');
                    
                redirect('about');
            }
        }
        
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
        
        $this->load_view('pages/about', $data);
    }
}
