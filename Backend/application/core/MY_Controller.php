<?php

class MY_Controller extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
    }
    
    protected function load_view($view, $data = array())
    {        
        $this->load->view('template', array('content' => $this->load->view($view, $data, TRUE)));
    }
}