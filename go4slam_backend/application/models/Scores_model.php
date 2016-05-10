<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scores_model extends MY_Model {

    public $table = 'scores';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->return_as = 'array';

        parent::__construct();
    }
}