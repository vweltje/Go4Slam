<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_items_model');
        $this->load->model('galleries_model');
        $this->load->model('gallery_items_model');
    }

    public function index() {
        $data['newsletters'] = $this->news_items_model->get_all();
        $data['galleries'] = $this->galleries_model->get_all();

        $this->load_view('pages/content_items_overview', $data);
    }

    public function add_or_edit_newsletter($news_item_id = false) {
        $data = array();
        $this->form_validation->set_rules('title', 'title', 'trim|required')
                ->set_rules('short_description', 'short_description', 'trim|required')
                ->set_rules('newsletter', 'newsletter');
        if ($news_item_id) {
            $data['newsletter'] = $this->news_items_model->fields(array('title', 'short_description', 'pdf'))->get($news_item_id);
        }
        if ($this->form_validation->run()) {
            $insert = array(
                'title' => $this->input->post('title'),
                'short_description' => $this->input->post('short_description')
            );
            if (!$news_item_id) {
                $this->load->helper('file_upload_helper');
                $insert['pdf'] = do_file_upload(config_item('src_path_newsletters'), 'pdf', 'newsletter');
                if (isset($insert['pdf']['error'])) {
                    $data['error'] = $insert['pdf']['error'];
                    return $this->load_view('pages/alter_newsletter', $data);
                }
                $insert['pdf'] = $insert['pdf']['file_name'];
            }
            if (!$news_item_id) {
                $this->news_items_model->insert($insert);
            } else {
                $this->news_items_model->update($insert, $news_item_id);
            }
            $this->session->set_flashdata('message', 'Newsletter successfully saved.');
            redirect('content');
        }
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
        $this->load_view('pages/alter_newsletter', $data);
    }

    public function delete_newsletter($news_item_id = false) {
        $data['success'] = false;
        if ($news_item_id) {
            $newsletter = $this->sponsors_model->fields('pdf')->get($news_item_id)['pdf'];
            if ($this->sponsors_model->delete($news_item_id)) {
                unlink(config_item('src_path_newsletters') . $newsletter);
                $data['success'] = true;
            }
        }
        $this->session->set_flashdata('message', 'Newsletter successfully deleted.');
        redirect($this->agent->referrer());
    }

    public function add_or_edit_gallery($gallery_id = false) {
        $data = array();
        $this->form_validation->set_rules('title', 'title', 'trim|required')
                ->set_rules('description', 'description', 'trim|required')
                ->set_rules('images', 'images');
        if ($gallery_id) {
            $data['gallery'] = $this->galleries_model->with_items()->get($gallery_id);
        }
        if ($this->form_validation->run()) {
            $insert = array(
                'title' => $this->input->post('title'),
                'description' => $this->input->post('description')
            );
            $this->load->helper('image_upload_helper');
            $images = do_image_upload(config_item('src_path_gallery_images'), 10000, 500);
            if (isset($images['error'])) {
                $data['error'] = $images['error'];
                return $this->load_view('pages/alter_newsletter', $data);
            }
            if (!$gallery_id) {
                $id = $this->galleries_model->insert($insert);
                foreach ($images as $image) {
                    $this->gallery_items_model->insert(array('gallery_id' => $id, 'src' => $image));
                }
            } else {
                if ($images) {
                    foreach ($images as $image) {
                        $this->gallery_items_model->insert(array('gallery_id' => $gallery_id, 'src' => $image));
                    }
                }
                $this->galleries_model->update($insert, $gallery_id);
            }
            $this->session->set_flashdata('message', 'Gallery successfully saved.');
            redirect('content');
        }
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
        $this->load_view('pages/alter_gallery', $data);
    }
    
    public function ajax_delete_gallery_image($image_id = false) {
        $data['success'] = false;
        if($image_id) {
            $this->gallery_items_model->delete($image_id);
            $data['success'] = true;
        }
        echo json_encode($data);
        return;
    }

    public function delete_gallery($gallery_id = false) {
        $data['success'] = false;
        if ($gallery_id) {
            $gallery = $this->galleries_model->with_items()->get($gallery_id);
            if ($this->galleries_model->delete($gallery_id)) {
                foreach ($gallery['items'] as $item) {
                    unlink(config_item('src_path_gallery_images') . $item['src']);
                    $this->gallery_items_model->delete($item['id']);
                }
                $data['success'] = true;
            }
        }
        $this->session->set_flashdata('message', 'Gallery successfully deleted.');
        redirect($this->agent->referrer());
    }

}
