<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events_model extends MY_Model {

    public $table = 'events';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->return_as = 'array';

        parent::__construct();
    }
    
//    public function get_events($start, $end) {
//        $this->db->select('*')
//                ->where(array('start_date' => $start, 'end_date' => $end))
//                ->get('events');
//        return;
//    }
}