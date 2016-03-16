<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('users_model');
    }

    public function index() {
        $this->login();
    }

    public function login() {
        if ($this->ion_auth->is_admin())
            redirect('analytics');

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

        if ($this->ion_auth->errors())
            $data['error'] = $this->ion_auth->errors();
        if (validation_errors())
            $data['error'] = validation_errors();

        $this->load_view('pages/login', $data);
    }

    public function logout() {
        $this->ion_auth->logout();

        redirect('user/login');
    }

    public function users_overview($type = false) {
        $data = array();
        $this->load->model('groups_model');

        $data['type'] = $type;
        $user_ids = $this->groups_model->fields('id')->where('name', $type === 'cms' ? 'admin' : 'general')->with_users_groups('fields: user_id')->get_all()[0]['users_groups'];

        if ($user_ids) {

            foreach ($user_ids as &$user_id) {
                $user_id = $user_id['user_id'];
            }
            $fields = array('id', 'email', 'first_name', 'prefix', 'last_name');
            $data['users'] = $this->users_model->where('id', $user_ids)->fields($fields)->get_all();
        } else {
            $data['users'] = array();
        }

        $this->load_view('pages/users_overview', $data);
    }

    public function add_or_edit_user($type = false, $user_id = false) {
        $data = array();

        $this->form_validation->set_rules('email', 'email', 'trim|required|valid_email')
                ->set_rules('password', 'password', 'trim'.($user_id ? '' : '|required').'|matches[passconf]|min_length[8]')
                ->set_rules('passconf', 'password confirmation', 'trim'.($user_id ? '' : '|required').'')
                ->set_rules('first_name', 'first name', 'trim|required')
                ->set_rules('prefix', 'prefix name', 'trim')
                ->set_rules('last_name', 'last name', 'trim|required');
        
        if ($user_id) {
            $fields = array('first_name', 'prefix', 'last_name', 'email');
            $data['user'] = $this->users_model->fields($fields)->get($user_id);
        }

        if ($this->form_validation->run()) {
            $data = $this->input->post();

            $data['first_name'] = ucfirst($data['first_name']);
            $data['prefix'] = ucfirst($data['prefix']);
            $data['last_name'] = ucfirst($data['last_name']);

            unset($data['passconf']);

            if (!$user_id || $user_id === 0) {

                $email = $this->input->post('email');

                unset($data['email']);
                unset($data['password']);

                if (!$user_id) {
                    $groups = array('2');

                    if ($type === 'cms')
                        $groups = array('1');

                    $success = $this->ion_auth_model->register($email, $this->input->post('password'), $email, $data, $groups);
                }
            }
            else {
                if (!$data['password']) unset($data['password']);
                
                $success = $this->ion_auth_model->update($user_id, $data);
            }

            if (isset($success) && $success) {
                $this->session->set_flashdata('message', 'User is successfully saved.');

                redirect($type . '_users');
            }

            $data['error'] = 'Invalid input, please try it again.';
        }

        if (validation_errors())
            $data['error'] = validation_errors();

        $data['type'] = $type;
        
        $this->load_view('pages/alter_user', $data);
    }

    public function delete_user($user_id = false) {
        $data['success'] = false;

        if ($user_id) {
            if ($this->ion_auth->delete_user($user_id))
                $data['success'] = true;
        }
        
        $this->session->set_flashdata('message', 'User successfully deleted.');
        
        redirect($this->agent->referrer());
    }

    public function app_users_overview() {
        $this->load_view('pages/app_users_overview');
    }

}
