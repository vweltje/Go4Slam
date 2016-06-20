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
                ->set_rules('password', 'password', 'trim' . ($user_id ? '' : '|required') . '|matches[passconf]|min_length[8]')
                ->set_rules('passconf', 'password confirmation', 'trim' . ($user_id ? '' : '|required') . '')
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
                if (!$data['password'])
                    unset($data['password']);

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

    public function password_forgotten() {
        $this->form_validation->set_rules('email', 'E-mail', 'trim|required');
        if ($this->form_validation->run()) {
            $email = $this->input->post('email');
            if ($this->users_model->fields(array('id'))->where('email', $email)->limit(1)->get()) {
                $this->load->helper('string');
                $this->load->library('email');
                $token = random_string('sha1');
                $this->users_model->where('email', $this->input->post('email'))->update(array('forgotten_password_code' => $token, 'forgotten_password_time' => time()));
                $this->email->from($this->config->item('email_from'), $this->config->item('email_from_name'))
                        ->to($email)
                        ->subject('Password reset - GO4SLAM')
                        ->message('Hello, <br><br> Press the link below to set a new password. <br><br><a href="' . base_url() . 'user/reset_password/' . urlencode($email) . '/' . urlencode($token) . '">Click here</a><br><br>Best regards, <br>GO4SLAM.')
                        ->set_mailtype('html');
                if ($this->email->send()) {
                    $this->session->set_flashdata('message', 'We have send a verification email.');
                    redirect('user/login');
                } else {
                    $data['error'] = 'We are not able to send you an email, please contact the administrator.';
                }
            } else {
                $data['error'] = 'This emailaddres seems to be not registered.';
            }
        } else {
            if (validation_errors()) {
                $data['error'] = validation_errors();
            }
        }
        $this->load_view('pages/password_forgotten');
    }

    public function reset_password($email = false, $token = false) {
        $data = array();
        $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Password confirmation', 'trim|required');
        $result = $this->users_model->fields(array('forgotten_password_time'))->where(array('email' => urldecode($email), 'forgotten_password_code' => $token))->get();
        if (isset($result['forgotten_password_time'])) {
            $forgotten_time = $result['forgotten_password_time'];
            $expire_in = config_item('forgotten_password_expire_time');
            if (time() <= ($forgotten_time + $expire_in)) {
                if ($this->form_validation->run()) {
                    $salt = $this->ion_auth_model->salt();
                    $password = $this->ion_auth_model->hash_password($this->input->post('password'), $salt);
                    if (!$this->users_model->where(array('email' => urldecode($email), 'forgotten_password_code' => $token))->update(array('password' => $password, 'salt' => $salt, 'forgotten_password_time' => '', 'forgotten_password_code' => ''))) {
                        $data['run_error'] = 'ERROR!';
                    }
                    $data['success'] = true;
                }
                if (validation_errors()) {
                    $data['run_error'] = validation_errors();
                }
            } else {
                $data['error'] = 'Time expired';
            }
        } else {
            $data['error'] = 'Invalid input';
        }
        $this->load->view('reset_password', $data);
    }

}
