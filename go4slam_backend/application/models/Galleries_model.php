<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Galleries_model extends MY_Model {

    public $table = 'galleries';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->return_as = 'array';
        $this->has_many['items'] =  array(
            'foreign_model'=>'gallery_items_model',
            'foreign_table'=>'gallery_items',
            'foreign_key'=>'gallery_id',
            'local_key'=>'id'
        );

        parent::__construct();
    }
}