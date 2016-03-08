<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->load_view('pages/content_items_overview');
    }
}
