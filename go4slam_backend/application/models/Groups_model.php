<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups_model extends MY_Model {

    public $table = 'groups';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct()
    {
        $this->soft_deletes = true;
        $this->return_as = 'array';
        $this->has_many['users_groups'] = array(
            'foreign_model' => 'users_groups_model',
            'foreign_table' => 'users_groups',
            'foreign_key' => 'group_id',
            'local_key' => 'id');

        parent::__construct();
    }
}