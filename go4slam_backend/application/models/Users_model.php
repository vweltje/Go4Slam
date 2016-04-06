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
}
