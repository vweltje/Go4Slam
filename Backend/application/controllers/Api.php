<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
    
    // userid of the current user false if not singedin
    public $current_user_id = false;
    
    public function __construct()
    {
        parent::__construct();
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
            
            if ($this->event_log_model->insert($insert)) return true;
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
    }
    
    /**
     * Logs out the current user
     */
    public function logout() {
        
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
