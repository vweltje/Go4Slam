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
        $where = array(
            'name' => ($type === 'cms' ? 'admin' : 'general')
            );
        $user_ids = $this->groups_model->fields('id')->where($where)->with_users_groups('fields: user_id')->get_all()[0]['users_groups'];

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
        if ($type !== 'cms') {
            $this->form_validation->set_rules('image', 'profile picture')
                    ->set_rules('cover_image', 'corver picture')
                    ->set_rules('ranking_1_name', 'ranking 1 name', 'trim')
                    ->set_rules('ranking_1_score', 'ranking 1 score', 'trim')
                    ->set_rules('ranking_2_name', 'ranking 2 name', 'trim')
                    ->set_rules('ranking_2_score', 'ranking 2 score', 'trim')
                    ->set_rules('ranking_3_name', 'ranking 3 name', 'trim')
                    ->set_rules('ranking_3_score', 'ranking 3 score', 'trim');
        }
        if ($user_id) {
            $fields = array(
                'email',
                'first_name',
                'prefix',
                'last_name'
            );
            if ($type !== 'cms') {
                $fields[] = 'image';
                $fields[] = 'cover_image';
                $fields[] = 'ranking';
            }
            $data['user'] = $this->users_model->fields($fields)->get($user_id);
            if ($type !== 'cms') {
                $data['user']['ranking'] = unserialize($data['user']['ranking']);
            }
        }
        if ($this->form_validation->run()) {
            $insert_data = $this->input->post();
            $insert_data['first_name'] = ucfirst($insert_data['first_name']);
            $insert_data['prefix'] = ucfirst($insert_data['prefix']);
            $insert_data['last_name'] = ucfirst($insert_data['last_name']);
            if ($type !== 'cms') {
                $insert_data['ranking'] = serialize(array(
                    '1' => array(
                        'name' => $this->input->post('ranking_1_name'),
                        'score' => intval($this->input->post('ranking_1_score'))
                    ),
                    '2' => array(
                        'name' => $this->input->post('ranking_2_name'),
                        'score' => intval($this->input->post('ranking_2_score'))
                    ),
                    '3' => array(
                        'name' => $this->input->post('ranking_3_name'),
                        'score' => intval($this->input->post('ranking_3_score'))
                    )
                ));
                $this->load->helper('image_upload_helper');
                $profile_pic = do_image_upload(config_item('src_path_profile_pictures'), 10000, 400, 'image');
                $cover_pic = do_image_upload(config_item('src_path_cover_images'), 10000, 400, 'cover_image');
                if ($cover_pic) {
                    if (isset($cover_pic['error'])) {
                        $data['error'] = $cover_pic['error'];
                        return $this->load_view('pages/alter_user', $data);
                    }
                    $insert_data['cover_image'] = $cover_pic[0];
                }
                if ($profile_pic) {
                    if (isset($profile_pic['error'])) {
                        $data['error'] = $profile_pic['error'];
                        return $this->load_view('pages/alter_user', $data);
                    }
                    $insert_data['image'] = $profile_pic[0];
                }
            }
            unset($insert_data['passconf']);
            if (!$user_id || $user_id === 0) {
                $email = $insert_data['email'];
                unset($insert_data['email']);
                unset($insert_data['password']);
                $groups = array('2');
                if ($type === 'cms') {
                    $groups = array('1');
                }
                $success = $this->ion_auth_model->register($email, $this->input->post('password'), $email, $insert_data, $groups);
            } else {
                if (!$insert_data['password']) {
                    unset($insert_data['password']);
                }
                $success = $this->ion_auth_model->update($user_id, $insert_data);
            }
            if (isset($success) && $success) {
                $this->session->set_flashdata('message', 'User is successfully saved.');
                redirect($type . '_users');
            }
            $data['error'] = 'Invalid input, please try it again.';
        }
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
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

    public function settings() {
        $data = array();

        $this->form_validation->set_rules('password', 'current password', 'trim|required')
                ->set_rules('new_pass', 'new password', 'trim|required|matches[conf_pass]')
                ->set_rules('conf_pass', 'confirmation password', 'trim|required');

        if ($this->form_validation->run()) {
            $password = $this->input->post('password');
            $new_pass = $this->input->post('new_pass');
            $conf_pass = $this->input->post('conf_pass');
            if ($new_pass === $conf_pass) {
                $email = $this->ion_auth->user()->row()->email;
                $user = $this->users_model->login($email, $password);
                if ($user) {
                    if ($this->users_model->change_password($email, $new_pass, $user->password)) {
                        $this->session->set_flashdata('message', 'Your settings have been successfully saved.');
                        redirect('analytics');
                    } else {
                        $data['error'] = 'ERROR!';
                    }
                } else {
                    $data['error'] = 'Current password seems to be incorect.';
                }
                $update['salt'] = $this->ion_auth_model->salt();
                $update['password'] = $this->ion_auth_model->hash_password($new_pass, $update['salt']);
            } else {
                $data['error'] = 'The new password field does not match the confimation field.';
            }
        }

        if (validation_errors()) {
            $data['error'] = validations_errors();
        }

        $this->load_view('pages/settings', $data);
    }

}
