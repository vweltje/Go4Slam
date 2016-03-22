<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    // userid of the current user false if not singedin
    private $user_id = 0;
    private $privatekey = '';
    private $current_user = false;

    public function __construct() {
        parent::__construct();
        $this->check_api_key();
        $this->load->model('users_model');
        $headers = $this->input->request_headers();
        $this->user_id = (isset($headers['user_id']) ? $headers['user_id'] : 0);
        $this->privatekey = (isset($headers['privatekey']) ? $headers['privatekey'] : '');
        if (!empty($user_id)) {
            $this->current_user = $this->users_model->where(array('id' => $this->userid, 'privatekey' => $this->privatekey))->get();
        }
    }

    /**
     * Check if request is valid. 
     */
    private function check_api_key() {
        $post_token = $this->input->get_request_header('token');
        $post_datetime = $this->input->get_request_header('date');
        $test_date = gmdate('Y-m-d H:i:s');
        $post_token = sha1(config_item('api_salt_key') . $test_date);
        $post_datetime = $test_date;
        if ($post_token && $post_datetime) {
            $date = gmdate('Y-m-d H:i:s');
            if (strtotime($post_datetime) >= (strtotime($date) - 300) && strtotime($post_datetime) <= strtotime($date)) {
                $token = sha1(config_item('api_salt_key') . $post_datetime);
                if ($post_token === $token) {
                    return true;
                }
            }
        } else {
            return $this->send_error('ERROR');
        }
    }

    /**
     * Send the response
     */
    private function send_response($response = array()) {
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }

    /**
     * If something fails, send error
     * The error ERROR can be sent as a nonspecific error
     */
    private function send_error($error) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'error' => $error
        ));
        return;
    }

    /**
     * If function is successfully ended
     */
    private function send_success() {
        header('Content-Type: application/json');
        echo json_encode(array('success' => true));
        return;
    }

    /**
     * Checks userid and privatekey against database.
     * If not found, sends error AUTH_FAIL.
     */
    private function check_auth() {
        if (empty($this->user_id) || empty($this->privatekey)) {
            return $this->send_error('NOT_SIGNED_IN');
        } 
        elseif ($this->users_model->validate_privatekey($this->user_id, $this->privatekey)) {
            return true;
        }
        else {
            return $this->send_error('AUTH_FAIL');
        }
    }

    /**
     * Logs an event
     * user_id = id of the user who does the api call
     * type = name of called function
     * data = array with all the post values and containes success or error message
     */
    private function log_event($user_id = false, $type = false, $data = array()) {
        if ($user_id !== false && $type !== false) {
            $this->load->model('event_log_model');
            $insert = array(
                'user_id' => $user_id,
                'type' => $type,
                'data' => serialize($data)
            );
            if ($this->event_log_model->insert($insert)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Checks email and password and generates a new privatekey.
     * POST:
     * - email
     * - password
     * Returns associative array:
     * - privatekey STRING
     * - userid INT
     * If email/password combo is incorrect send error: LOGIN_FAIL
     */
    public function login() {
        $email = $this->input->post('email');
        $pass = $this->input->post('password');
        if (!empty($email) && !empty($pass)) {
            $user = $this->ion_auth_model->login($email, $pass);
            if ($user && $this->ion_auth->in_group(array('admin', 'general'))) {
                $key = $this->users_model->set_privatekey(true);
                if (!$key) {
                    $this->ion_auth->logout();
                    return $this->send_error('ERROR');
                }
                return $this->send_response(array(
                            'privatekey' => $key,
                            'user_id' => $this->ion_auth->user()->row()->id
                ));
            } else {
                $this->ion_auth->logout();
            }
        }
        return $this->send_error('LOGIN_FAIL');
    }

    /**
     * Logs out the current user
     */
    public function logout() {
        if (!$this->users_model->set_privatekey(false)) {
            return $this->send_error('ERROR');
        }
        if ($this->ion_auth->logout()) {
            return $this->send_success();
        }
    }

    /**
     * Generates a random token and mails a link containing a api/reset_password
     * url to reset the password
     * POST:
     * - email
     */
    public function forgotten_password() {
        $email = $this->input->post('email');
    }

    /**
     * NOT ACCESSED FROM THE APP
     * Allows the user to pick a new password.
     * $email STRING: urlencoded email
     * $token STRING: token that was generated in forgotten_password()
     */
    public function reset_password($email = false, $token = false) {
        
    }

    /**
     * Changes the user's password and regenerates the privatekey
     * POST:
     * - password: the current password
     * - new_password: the new password
     * Returns associative array:
     * - privatekey STRING: the new privatekey
     * Sends error WRONG_PASSWORD if the current password is incorrect
     */
    public function change_password() {
        $this->check_auth();

        $pass = $this->input->post('password');
        $new_pass = $this->input->post('new_password');
    }

    /**
     * Return a user's details
     * POST:
     * - id INT: the id of the requested user. 
     * Returns associative array:
     * - first_name STRING
     * - prefix STRING
     * - last_name STRING
     * - function STRING
     * - email STRING
     * - image STRING: url to the user's profile image
     */
    public function get_user_details() {
        
    }

    /**
     * Edit user information
     * POST: 
     * - first_name STRING
     * - perfix STRING
     * - last_name STRING
     * - email STRING
     * - birth_day STRING
     * - city STRING
     * - website STRING
     * - twitter STRING
     * Returns validation errors or updated profile
     */
    public function edit_user_details() {
        $this->check_auth();
    }

    /**
     * Get the scores of the tournaments
     * POST:
     * - userid INTAGER of the requested user scores
     * Returns associative array
     */
    public function get_score_index() {
        
    }

    /**
     * Get timeline contents
     * POST:
     * - filter_type STRING
     * - filter_id INTAGER
     * - search STRING
     * Retruns associative array
     */
    public function get_timeline() {
        
    }

    /**
     * Get sponsor logos
     * Returns associative array:
     * - image STRING
     */
    public function get_sponsors() {
        
    }

    /**
     * go4slam members can post content
     * POST:
     * - type STRING text - image - video
     * - content STRING
     * Returns validation errors or true
     */
    public function post_content() {
        
    }

}
