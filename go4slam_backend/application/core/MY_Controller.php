<?php

class MY_Controller extends CI_Controller {

    /**
     * Functions the user don't have to be logged in for
     */
    private $login_exceptions = array(
        'password_forgotten',
        'reset_password',
        'login',
        'add_cms_user',
        'get_sponsors',
        'get_timeline'
    );

    public function __construct() {
        parent::__construct();

        if (!$this->ion_auth->logged_in() && !in_array($this->router->fetch_method(), $this->login_exceptions)) {
            redirect('user/login');
        }
    }

    protected function load_view($view, $data = array()) {
        $this->load->view('template', array('content' => $this->load->view($view, $data, TRUE)));
    }

    protected function timeline_item($action, $item_id, $type) {
        $this->load->model('timeline_model');
        if ($action === 'insert') {
            $timeline = array('item_id' => $item_id, 'type' => $type);
            if ($this->input->post('instant_publish') === 'false') {
                $timeline['publish_from'] = $this->input->post('publish_from');
                $timeline['publish_till'] = $this->input->post('publish_till');
            } else {
                $timeline['publish_from'] = null;
                $timeline['publish_till'] = null;
            }
            return $this->timeline_model->insert($timeline);
        } elseif ($action === 'update') {
            if ($this->input->post('instant_publish') === 'false') {
                $update_timeline = array('publish_from' => $this->input->post('publish_from'), 'publish_till' => $this->input->post('publish_till'));
            } else {
                $update_timeline = array('publish_from' => null, 'publish_till' => null);
            }
            return $this->timeline_model->update($update_timeline, array('item_id' => $item_id, 'type' => $type));
        }
    }

}
