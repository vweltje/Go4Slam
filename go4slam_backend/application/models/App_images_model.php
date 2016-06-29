<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_images_model extends MY_Model {

    public $table = 'app_images';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->timestamps = false;
        $this->return_as = 'array';

        parent::__construct();
    }
}