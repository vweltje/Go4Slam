<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('news_items_model');
        $this->load->model('galleries_model');
        $this->load->model('gallery_items_model');
        $this->load->model('scores_model');
        $this->load->model('events_model');
    }

    public function index() {
        $data['newsletters'] = $this->news_items_model->order_by('created_at', 'desc')->get_all();
        $data['galleries'] = $this->galleries_model->order_by('created_at', 'desc')->get_all();
        $data['scores'] = $this->scores_model->order_by('created_at', 'desc')->get_all();
        $data['events'] = $this->events_model->order_by('created_at', 'desc')->get_all();

        foreach ($data['events'] as &$event) {
            $event['start_date'] = date('d-m-Y H:i:s', strtotime($event['start_date']));
        }

        $this->load_view('pages/content_items_overview', $data);
    }

    public function add_or_edit_newsletter($news_item_id = false) {
        $data = array();
        $this->form_validation->set_rules('title', 'title', 'trim|required')
                ->set_rules('number', 'newsletter number', 'trim|required|numeric')
                ->set_rules('short_description', 'short_description', 'trim|required')
                ->set_rules('newsletter', 'newsletter');
        if ($news_item_id) {
            $data['newsletter'] = $this->news_items_model->fields(array('title', 'number', 'short_description', 'pdf'))->get($news_item_id);
        }
        if ($this->form_validation->run()) {
            $insert = array(
                'title' => ucfirst($this->input->post('title')),
                'number' => intval($this->input->post('number')),
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
                $id = $this->news_items_model->insert($insert);
                $this->timeline_item('insert', $id, 'newsletter');
            } else {
                $this->news_items_model->update($insert, $news_item_id);
                $this->timeline_item('update', $news_item_id, 'newsletter');
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
            $newsletter = $this->news_items_model->fields('pdf')->get($news_item_id)['pdf'];
            if ($this->news_items_model->delete($news_item_id)) {
                $this->timeline_model->delete(array('item_id' => $news_item_id, 'type' => 'newsletter'));
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
                'title' => ucfirst($this->input->post('title')),
                'description' => $this->input->post('description')
            );
            $this->load->helper('image_upload_helper');
            $images = do_image_upload(config_item('src_path_gallery_images'), 10000, 400);
            if (isset($images['error'])) {
                $data['error'] = $images['error'];
                return $this->load_view('pages/alter_newsletter', $data);
            }
            if (!$gallery_id) {
                $id = $this->galleries_model->insert($insert);
                foreach ($images as $image) {
                    $this->gallery_items_model->insert(array('gallery_id' => $id, 'src' => $image));
                }
                $this->timeline_item('insert', $id, 'gallery');
            } else {
                if ($images) {
                    foreach ($images as $image) {
                        $this->gallery_items_model->insert(array('gallery_id' => $gallery_id, 'src' => $image));
                    }
                }
                $this->galleries_model->update($insert, $gallery_id);
                $this->timeline_item('update', $gallery_id, 'gallery');
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
        if ($image_id) {
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
                $this->timeline_model->delete(array('item_id' => $gallery_id, 'type' => 'gallery'));
                $data['success'] = true;
            }
        }
        $this->session->set_flashdata('message', 'Gallery successfully deleted.');
        redirect($this->agent->referrer());
    }

    public function add_or_edit_score($score_id = false) {
        $data = array();
        $this->form_validation->set_rules('player_name', 'Player name', 'trim|required')
                ->set_rules('player_score', 'Player score', 'trim|required')
                ->set_rules('player_2_name', 'Player 2 name', 'trim|required')
                ->set_rules('player_2_score', 'Player 2 score', 'trim|required')
                ->set_rules('description', 'Description', 'trim|required')
                ->set_rules('image', 'Image');
        if ($score_id) {
            $data['score'] = $this->scores_model->get($score_id);
        }
        if ($this->form_validation->run()) {
            $this->load->helper('image_upload');
            $insert = array(
                'player_name' => ucfirst($this->input->post('player_name')),
                'player_score' => $this->input->post('player_score'),
                'player_2_name' => ucfirst($this->input->post('player_2_name')),
                'player_2_score' => $this->input->post('player_2_score'),
                'description' => $this->input->post('description')
            );
            if ($_FILES['userfile']['name']) {
                $image = do_image_upload(config_item('src_path_score_images'), 10000, 250);
                if (isset($image['error'])) {
                    return $this->load_view('pages/alter_score', $image);
                }
                $insert['image'] = $image[0];
            }
            if (!$score_id) {
                $id = $this->scores_model->insert($insert);
                $this->timeline_item('insert', $id, 'score');
            } else {
                $this->scores_model->update($insert, $score_id);
                $this->timeline_item('update', $score_id, 'score');
            }
            $this->session->set_flashdata('message', 'Score successfully saved.');
            redirect('content');
        }
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
        $this->load_view('pages/alter_score', $data);
    }

    public function delete_score($score_id = false) {
        $data['success'] = false;
        if ($score_id) {
            $score = $this->scores_model->fields('image')->get($score_id)['image'];
            if ($this->scores_model->delete($score_id)) {
                $this->timeline_model->delete(array('item_id' => $score_id, 'type' => 'score'));
                unlink(config_item('src_path_score_images') . $score_id);
                $data['success'] = true;
            }
        }
        $this->session->set_flashdata('message', 'Score successfully deleted.');
        redirect($this->agent->referrer());
    }

    public function add_or_edit_event($event_id = false) {
        $data = array(
            'types' => config_item('event_types')
        );
        $this->form_validation->set_rules('title', 'title', 'trim|required')
                ->set_rules('short_description', 'short_description', 'trim|required')
                ->set_rules('start_date', 'start date', 'required')
                ->set_rules('type', 'type', 'required')
                ->set_rules('end_date', 'end date', 'required')
                ->set_rules('image', 'Image');
        if ($event_id) {
            $data['event'] = $this->events_model->fields(array('title', 'type', 'short_description', 'start_date', 'end_date', 'image'))->get($event_id);
            $data['event']['start_date'] = date('d-m-Y H:i:s', strtotime($data['event']['start_date']));
            $data['event']['end_date'] = date('d-m-Y H:i:s', strtotime($data['event']['end_date']));
        }
        if ($this->form_validation->run()) {
            $insert = array(
                'type' => $this->input->post('type'),
                'title' => ucfirst($this->input->post('title')),
                'short_description' => $this->input->post('short_description'),
                'start_date' => date('Y-m-d H:i:s', strtotime($this->input->post('start_date'))),
                'end_date' => date('Y-m-d H:i:s', strtotime($this->input->post('end_date')))
            );
            $this->load->helper('image_upload');
            $image = do_image_upload(config_item('src_path_event_images'), 10000, 250);
            if (isset($image['error'])) {
                return $this->load_view('pages/alter_event', $image);
            }
            $insert['image'] = $image[0];
            if (!$event_id) {
                $id = $this->events_model->insert($insert);
            } else {
                $this->events_model->update($insert, $event_id);
            }
            $this->session->set_flashdata('message', 'Event successfully saved.');
            redirect('content');
        }
        if (validation_errors()) {
            $data['error'] = validation_errors();
        }
        $this->load_view('pages/alter_event', $data);
    }

    public function delete_event($event_id = false) {
        $data['success'] = false;
        if ($event_id) {
            if ($this->events_model->delete($event_id)) {
                $data['success'] = true;
            }
        }
        $this->session->set_flashdata('message', 'Event successfully deleted.');
        redirect($this->agent->referrer());
    }

}
