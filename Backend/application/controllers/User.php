<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->login();
    }
    
    public function login() {
        if ($this->ion_auth->is_admin()) redirect('analytics');
        
        $data = array();
        
        $this->form_validation->set_rules('email', 'e-mail', 'trim|required|valid_email')
                ->set_rules('password', 'password', 'required')
                ->set_rules('remember', 'remember');

        if ($this->form_validation->run()) {
            $input = $this->input->post();

            if ($this->ion_auth_model->login($input['email'], $input['password'], false)) {
                if ($this->ion_auth->in_group('admin')) {
                    return redirect('analytics');           
                }
                
                $this->ion_auth->logout();
                
                $data['error'] = 'Invalid login';
            }
        }
        
        if ($this->ion_auth->errors()) $data['error'] = $this->ion_auth->errors();
        if (validation_errors()) $data['error'] = validation_errors();
            
        $this->load_view('pages/login', $data);
    }
    
    public function logout() {
        $this->ion_auth->logout();
        
        redirect('user/login');
    }
    
    public function add_cms_user() {
        $data = array();
        
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email')
                ->set_rules('password', 'password', 'trim|required')
                ->set_rules('first_name', 'first name', 'trim|required')
                ->set_rules('prefix', 'prefix name', 'trim')
                ->set_rules('last_name', 'last name', 'trim|required');
        
        if ($this->form_validation->run()) {
            $data = $this->input->post();
            $email = $this->input->post('email');
            
            unset($data['email']);
            unset($data['password']);
            
            $user_id = $this->ion_auth_model->register($email, $this->input->post('password'), $email, $data, array('1'));

            if ($user_id) {
                echo json_encode('success');
                exit;
            }
            
            echo json_encode('error');
            exit;
        }
        
        if (validation_errors()) $data['error'] = validation_errors();
        
        echo json_encode($data);
        exit;
    }
}
