<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends MY_Model {

    public $table = 'users';
    public $primary_key = 'id';
    public $protected = array('id', 'created_on', 'last_login', 'updated_at');

    public function __construct()
    {
        $this->soft_deletes = true;
        $this->return_as = 'array';

        parent::__construct();
    }
}