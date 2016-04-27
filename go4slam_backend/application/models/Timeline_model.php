<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timeline_model extends MY_Model {

    public $table = 'timeline';
    public $protected = array('created_at');

    public function __construct()
    {
        $this->soft_deletes = false;
        $this->return_as = 'array';

        parent::__construct();
    }
    
    public function get_timeline($load_count = 0) {
        $offset = $load_count * config_item('timeline_load_limit');
        $timeline = $this->order_by('created_at', 'desc')->limit(config_item('timeline_load_limit'), $offset)->get_all();
        if (!$timeline) {
            return array('error' => 'NO_ITEMS_FOUND');
        }
        $this->load->model('blog_posts_model');
        $this->load->model('news_items_model');
        $this->load->model('galleries_model');
        $this->load->model('gallery_items_model');
        foreach($timeline as &$item) {
            switch ($item['type']) {
                case 'blog_post' :
                    $item = $this->blog_posts_model->get($item['item_id']);
                    $item['type'] = 'blog_post';
                    break;
                case 'newsletter' :
                    $item = $this->news_items_model->get($item['item_id']);
                    $item['type'] = 'newsletter';
                    break;
                case 'gallery' :
                    $item = $this->galleries_model->get($item['item_id']);
                    $item['type'] = 'gallery';
                    $item['items'] = $this->gallery_items_model->get_all(array('gallery_id' => $item['id']));
                    break;
            }
        }
        return $timeline;
    }
}