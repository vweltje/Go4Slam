<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class News_items_model extends MY_Model {

    public $table = 'news_items';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->return_as = 'array';

        parent::__construct();
    }
}