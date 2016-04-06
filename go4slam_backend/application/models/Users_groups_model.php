<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_groups_model extends MY_Model {

    public $table = 'users_groups';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->return_as = 'array';
        $this->has_one['user'] = array(
            'foreign_model' => 'users_model',
            'foreign_table' => 'users',
            'foreign_key' => 'group_id',
            'local_key' => 'id');

        parent::__construct();
    }
}