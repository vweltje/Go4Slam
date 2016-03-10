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

            if ($this->ion_auth_model->login($input['email'], $input['password'])) {
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
    
    public function cms_users_overview() {
        $this->load_view('pages/cms_users_overview');
    }
    
    public function add_or_edit_user($user_id = false, $type = false) {
        $data = array();
        
        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email')
                ->set_rules('password', 'password', 'trim|required|matches[passconf]')
                ->set_rules('passconf', 'password confirmation', 'trim|required')
                ->set_rules('first_name', 'first name', 'trim|required')
                ->set_rules('prefix', 'prefix name', 'trim')
                ->set_rules('last_name', 'last name', 'trim|required');
        
        if ($this->form_validation->run()) {
            $data = $this->input->post();
            
            unset($data['passconf']);
            
            if (!$user_id || $user_id !== 0) {
                
                $email = $this->input->post('email');

                unset($data['email']);
                unset($data['password']);

                if (!$user_id) {
                    $groups = array('2');
                    
                    if ($type === 'admin') array_push ($groups, '1');
                    
                    $success = $this->ion_auth_model->register($email, $this->input->post('password'), $email, $data, $groups);
                }
            }
            else {
                $success = $this->ion_auth_model->update($user_id, $data);
            }
            
            if ($success) redirect('user/cms_users_overview');
            
            $data['error'] = 'Invalid input, please try it again.';
        }
        
        if (validation_errors()) $data['error'] = validation_errors();
        
        $this->load_view('pages/alter_cms_user');
    }
    
    public function delete_user($user_id = false) {
        $data['success'] = false;
        
        if ($user_id) {
            if ($this->ion_auth->delete_user($user_id)) $data['success'] = true;
        }
        
        echo json_encode($data);
        return;
    }
    
    public function app_users_overview() {
        $this->load_view('pages/app_users_overview');
    }
}
