<?php

class MY_Controller extends CI_Controller {
    
    /**
     * Functions the user don't have to be logged in for
     */
    private $login_exceptions = array('password_forgotten', 'password_reset', 'login');
    
    public function __construct()
    {
        parent::__construct();

        if (!$this->ion_auth->logged_in() && !in_array($this->router->fetch_method(), $this->login_exceptions)) {
            redirect('user/login');
        }
    }
    
    protected function load_view($view, $data = array())
    {        
        $this->load->view('template', array('content' => $this->load->view($view, $data, TRUE)));
    }
}