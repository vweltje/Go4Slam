<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Analytics extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $sort = 'hour';
        if ($this->input->get('sort_results_by')) {
            $sort = $this->input->get('sort_results_by');
        }
        $this->session->set_userdata('sort_results_by', $sort);
        $data = array(
            'logging_types' => config_item('logging_types')
        );
        $this->load_view('pages/analytics', $data);
    }

    public function get_results($sort = 'hour') {
        $type = $this->input->post('type');
        switch ($this->session->userdata('sort_results_by')) {
            case 'hour' :
                $seconds = 3600;
                break;
            case 'day' :
                $seconds = 86400;
                break;
            case 'week' :
                $seconds = 604800;
                break;
            case 'month' :
                $seconds = 2620800;
                break;
            case 'year' :
                $seconds = 31449600;
                break;
            default :
                $seconds = false;
        }
        $time = time();
        $min_time = gmdate('Y-m-d H:i:s', ($time - $seconds));
        $this->load->model('logging_model');
        if (in_array($type, config_item('logging_types'))) {
            $where = array('action' => $type);
            if ($seconds) {
                $where['created_at >='] = $min_time;
            }
            $count = $this->logging_model->count_rows($where);
            echo json_encode(array('count' => $count));
            return;
        }
        echo json_encode(array('error' => 'ERROR'));
        return;
    }

}
