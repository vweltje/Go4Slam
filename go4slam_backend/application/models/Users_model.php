<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Model {

    public $table = 'users';
    public $primary_key = 'id';
    public $protected = array('id', 'created_on', 'last_login', 'updated_at');

    public function __construct() {
        $this->soft_deletes = true;
        $this->return_as = 'array';

        parent::__construct();
    }

    public function login($email, $password) {

        $this->load->model('Ion_auth_model');

        if (empty($email) || empty($password)) {
            return FALSE;
        }

        $query = $this->db->select('email, id, password, privatekey')
                ->where('email', $email)
                ->limit(1)
                ->order_by('id', 'desc')
                ->get('users');

        if ($query->num_rows() === 1) {
            $user = $query->row();
            $salt = $this->Ion_auth_model->salt();
            $privatekey = $this->Ion_auth_model->hash_password($email . $password . uniqid(), $salt);
            $password = $this->hash_password_db($user->id, $password);

            if ($password === TRUE) {
                if ($this->db->where('id', $user->id)->update('users', array('privatekey' => $privatekey))) {
                    $user->privatekey = $privatekey;
                    return $user;
                } else {
                    return false;
                }
            }
        }

        // Just to take up time.
        sleep(1);

        return FALSE;
    }

    private function hash_password_db($id, $password) {
        if (empty($id) || empty($password)) {
            return FALSE;
        }

        $query = $this->db->select('password, salt')
                ->where('id', $id)
                ->limit(1)
                ->order_by('id', 'desc')
                ->get('users');

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        if ($this->Ion_auth_model->hash_password($password, $hash_password_db->salt) === $hash_password_db->password) {
            return TRUE;
        }

        return FALSE;
    }

    public function set_privatekey($insert = true) {
        $id = $this->ion_auth->user()->row()->id;
        $email = $this->ion_auth->user()->row()->email;
        $time = gmdate(DateTime::W3C);
        if ($insert) {
            $key = sha1($email . $id . $time . uniqid());
        } else {
            $key = null;
        }
        return $this->db->where('id', $id)->update('users', array('privatekey' => $key)) ? ($key === null ? true : $key) : false;
    }

    public function validate_privatekey($user_id = false, $privatekey = false) {
        if (!$user_id || !$privatekey) {
            return false;
        }
        $query = $this->db->where(array('id' => $user_id, 'privatekey' => $privatekey))
                ->limit(1)
                ->order_by('id', 'desc')
                ->get('users');
        $user = $query->row();
        if ($query->num_rows() !== 1 || !$user) {
            return false;
        }
        return true;
    }

    public function change_password($email, $new_pass, $current_pass_hash) {
        $this->load->model('Ion_auth_model');
        $data = array(
            'salt' => $this->Ion_auth_model->salt()
        );
        $data['password'] = $this->Ion_auth_model->hash_password($new_pass, $data['salt']);
        return $this->db->where(array('email' => $email, 'password' => $current_pass_hash))
                        ->update('users', $data);
    }

}
