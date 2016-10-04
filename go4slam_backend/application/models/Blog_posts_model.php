<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_posts_model extends MY_Model {

    public $table = 'blog_posts';
    public $primary_key = 'id';
    public $protected = array('id');

    public function __construct() {
        $this->load->model('users_model');
        $this->soft_deletes = false;
        $this->return_as = 'array';
        $this->after_get[] = 'get_publisher';

        parent::__construct();
    }

    protected function get_publisher($row) {
        if (isset($row[0]) && (is_array($row[0]) || is_object($row[0]))) {
            foreach ($row as &$item) {
                $item = $this->get_publisher($item);
            }
        } else {
            $select = array(
                'id',
                'first_name',
                'prefix',
                'last_name',
                'image'
            );
            if (is_array($row)) {
                $row['publisher'] = false;
                if ($row['publisher_id']) {
                    $row['publisher'] = $this->users_model->fields($select)->get($row['publisher_id']);
                }
            } elseif (is_object($row)) {
                $row->publisher = false;
                if ($row->publisher_id) {
                    $row->publisher = $this->users_model->fields($select)->get($row->publisher_id);
                }
            }
        }
        unset($row['publisher_id']);
        return $row;
    }

}
