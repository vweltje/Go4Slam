<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery_items_model extends MY_Model {

    public $table = 'gallery_items';
    public $primary_key = 'id';
    public $protected = array('id', 'created_at');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->return_as = 'array';

        parent::__construct();
    }
}