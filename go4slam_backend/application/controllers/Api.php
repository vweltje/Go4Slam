<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    // userid of the current user false if not singedin
    private $user_id = 0;
    private $privatekey = '';
    private $current_user = false;

    public function __construct() {
        parent::__construct();

        $this->output->set_header("Access-Control-Allow-Origin: *");
        $this->output->set_header("Access-Control-Expose-Headers: Access-Control-Allow-Origin");

        $this->check_api_key();
        $this->load->model('users_model');
        $this->user_id = ($this->input->post('App-Userid') ? $this->input->post('App-Userid') : 0);
        $this->privatekey = ($this->input->post('App-Privatekey') ? $this->input->post('App-Privatekey') : '');
        if (!empty($user_id)) {
            $this->current_user = $this->users_model->where(array('id' => $this->userid, 'privatekey' => $this->privatekey))->get();
        }
    }

    /**
     * Check if request is valid.
     */
    private function check_api_key() {
        return true;
        $post_token = $this->input->post('App-Request-Token');
        $post_datetime = $this->input->post('App-Request-Timestamp');
        if ($post_token && $post_datetime) {
            $date = gmdate('Y-m-d H:i:s');
            if (strtotime($post_datetime) >= (strtotime($date) - 300) && strtotime($post_datetime) <= strtotime($date)) {
                $token = sha1(config_item('api_request_key') . $post_datetime);
                if ($post_token === $token) {
                    return true;
                }
            }
        } else {
            $this->send_error('ERROR');
            exit;
        }
    }

    /**
     * Send the response
     */
    private function send_response($response = array()) {
        header('Content-Type: application/json');
        echo json_encode($response);
        return;
    }

    /**
     * If something fails, send error
     * The error ERROR can be sent as a nonspecific error
     */
    private function send_error($error) {
        header('Content-Type: application/json');
        echo json_encode(array(
            'error' => $error
        ));
        return;
    }

    /**
     * If function is successfully ended
     */
    private function send_success() {
        header('Content-Type: application/json');
        echo json_encode(array('success' => true));
        return;
    }

    /**
     * Checks userid and privatekey against database.
     * If not found, sends error AUTH_FAIL.
     */
    private function check_auth() {
        if (empty($this->user_id) || empty($this->privatekey)) {
            $this->send_error('AUTH_FAIL');
            exit;
        } elseif ($this->users_model->validate_privatekey($this->user_id, $this->privatekey)) {
            return true;
        }
    }

    private function event_log() {
        $this->load->model('logging_model');
        $insert = array(
            'action' => $this->router->method,
            'created_at' => gmdate('Y-m-d H:i:s', time())
        );
        return $this->logging_model->insert($insert);
    }

    /**
     * Checks email and password and generates a new privatekey.
     * POST:
     * - email
     * - password
     * Returns associative array:
     * - privatekey STRING
     * - userid INT
     * If email/password combo is incorrect send error: LOGIN_FAIL
     */
    public function login() {
        $email = $this->input->post('email');
        $pass = $this->input->post('password');
        if (!empty($email) && !empty($pass)) {
            $user = $this->users_model->login($email, $pass);
            if (!$user) {
                return $this->send_error('ERROR');
            }
            $this->event_log();
            return $this->send_response(array(
                        'privatekey' => $user->privatekey,
                        'user_id' => $user->id,
                        'is_coach' => (bool) $this->ion_auth->in_group('coach', $user->id)
            ));
        }
        return $this->send_error('LOGIN_FAIL');
    }

    /**
     * Logs out the current user
     */
    public function logout() {
        if (!$this->users_model->set_privatekey(false)) {
            return $this->send_error('ERROR');
        }
        if ($this->ion_auth->logout()) {
            return $this->send_success();
        }
    }

    /**
     * Generates a random token and mails a link containing a api/reset_password
     * url to reset the password
     * POST:
     * - email
     */
    public function forgotten_password() {
        $email = $this->input->post('email');
        if (!$this->users_model->fields(array('id'))->where('email', $email)->limit(1)->get()) {
            return $this->send_error('NOT_REGISTERED');
        }
        $this->load->helper('string');
        $this->load->library('email');
        $token = random_string('sha1');
        if (!$this->users_model->where('email', $email)->update(array('forgotten_password_code' => $token, 'forgotten_password_time' => time()))) {
            return $this->send_error('ERROR');
        }
        $this->email->from(config_item('email_from'), config_item('email_from_name'))
                ->to($email)
                ->subject('Passwrod reset | Go4Slam app')
                ->message('Hello, <br><br> Press the link below to set a new password. <br><br><a href="' . base_url() . 'user/reset_password/' . urlencode($email) . '/' . urlencode($token) . '">Click here</a>')
                ->set_mailtype('html');
        if (!$this->email->send()) {
            return $this->send_error('UNABLE_TO_SEND_EMAIL');
        }
        return $this->send_success();
    }

    /**
     * Changes the user's password and regenerates the privatekey
     * POST:
     * - user_id: the id of the current user
     * - email: the email of the current user
     * - password: the current password
     * - new_password: the new password
     * Returns associative array:
     * - privatekey STRING: the new privatekey
     * Sends error WRONG_PASSWORD if the current password is incorrect
     */
    public function change_password() {
        $this->check_auth();
        $id = $this->input->post('id');
        $email = $this->input->post('email');
        $pass = $this->input->post('password');
        $new_pass = $this->input->post('new_password');
        if (!empty($id) && !empty($email) && !empty($pass) && !empty($new_pass)) {
            if (intval($id) === intval($this->user_id)) {
                $user = $this->ion_auth_model->login($email, $pass);
                if (!$user) {
                    return $this->send_error('INVALID_LOGIN');
                }
                $update['salt'] = $this->ion_auth_model->salt();
                $update['password'] = $this->ion_auth_model->hash_password($new_pass, $update['salt']);
                if (!$this->users_model->where(array('id' => $this->user_id, 'email' => $email))->update($update)) {
                    return $this->send_error('UPDATE_ERROR');
                }
                $user = $this->ion_auth_model->login($email, $new_pass);
                if (!$user) {
                    return $this->send_error('RELOGIN_FAIL');
                }
                $key = $this->users_model->set_privatekey(true);
                if (!$key) {
                    $this->ion_auth->logout();
                    return $this->send_error('ERROR');
                }
                return $this->send_response(array(
                            'privatekey' => $key,
                            'user_id' => $this->ion_auth->user()->row()->id
                ));
            }
        }
        return $this->send_error('ERROR');
    }

    /**
     * Return all user's details
     * POST:
     * - id INT: the id of the requested user.
     * Returns associative array:
     * - first_name STRING
     * - prefix STRING
     * - last_name STRING
     * - cover_image STRING: url to the user's cover image
     * - image STRING: url to the user's profile image
     * - wta_ranking_double INT
     * - nationale_ranking_single INT
     * - nationale_ranking_double INT
     */
    public function get_user_details() {
        $user_id = intval($this->input->post('user_id'));
        if (!$user_id) {
            return $this->send_error('INVALID_INPUT');
        }
        $fields = array(
            'id',
            'first_name',
            'prefix',
            'last_name',
            'image',
            'cover_image',
            'ranking'
        );
        if ($details = $this->users_model->fields($fields)->get($user_id)) {
            $this->load->model('blog_posts_model');
            $details['blog_posts'] = $this->blog_posts_model->order_by('created_at', 'desc')->get_all(array('user_id' => $user_id));
            $details['ranking'] = unserialize($details['ranking']);
            $this->event_log();
            $this->send_response($details);
        } else {
            return $this->send_error('ERROR');
        }
    }

    /**
     * Edit user information
     * POST:
     * - first_name STRING
     * - perfix STRING
     * - last_name STRING
     * - email STRING
     * - birth_day STRING
     * - city STRING
     * - website STRING
     * - twitter STRING
     * Returns validation errors or updated profile
     */
    public function edit_user_details() {
        $this->check_auth();
        $update_data = array(
            'email' => $this->input->post('email'),
            'first_name' => $this->input->post('first_name'),
            'prefix' => $this->input->post('prefix'),
            'last_name' => $this->input->post('last_name'),
        );
        $this->load->helper('image_upload_helper');
        $cover_pic = do_image_upload(config_item('src_path_cover_images'), 10000, 500, 'image');
        $profile_pic = do_image_upload(config_item('src_path_profile_pictures'), 10000, 500, 'cover_image');
        if ($cover_pic) {
            if (isset($cover_pic['error'])) {
                return $this->send_error($cover_pic['error']);
            }
            $update_data['cover_image'] = $cover_pic[0];
            $update_data['image'] = $cover_pic[0];
        }
        if ($profile_pic) {
            if (isset($profile_pic['error'])) {
                return $this->send_error($profile_pic['error']);
            }
            $update_data['cover_image'] = $profile_pic[0];
            $update_data['image'] = $profile_pic[0];
        }
        if (!$this->db->where('id', $this->user_id)->update('users', $update_data)) {
            return $this->send_error('ERROR');
        }
        return $this->send_success();
    }

    public function edit_ranking() {
        $this->check_auth();
        $ranking = serialize(array(
            '1' => array(
                'name' => $this->input->post('ranking_1_name'),
                'score' => intval($this->input->post('ranking_1_score'))
            ),
            '2' => array(
                'name' => $this->input->post('ranking_2_name'),
                'score' => intval($this->input->post('ranking_2_score'))
            ),
            '3' => array(
                'name' => $this->input->post('ranking_3_name'),
                'score' => intval($this->input->post('ranking_3_score'))
            )
        ));
        if (!$this->db->where('id', $this->user_id)->update('users', array('ranking' => $ranking))) {
            return $this->send_error('ERROR');
        }
        return $this->send_success();
    }

    /**
     * Get timeline contents
     * POST:
     * - filter_type STRING
     * - filter_id INTAGER
     * - search STRING
     * Retruns associative array
     */
    public function get_timeline() {
        $load_count = $this->input->post('load_count');
        if ($load_count) {
            if ($timeline = $this->timeline_model->get_timeline(intval($load_count) - 1)) {
                $this->event_log();
                return $this->send_response($timeline);
            }
        }
        return $this->send_error('ERROR');
    }

    /**
     * Get sponsor logos
     * Returns associative array:
     * - image STRING
     * - name STRING
     */
    public function get_sponsors($view = 'default') {
        $this->load->model('sponsors_model');
        $data = array();
        if ($view === 'default') {
            $data['sponsors'] = $this->sponsors_model->fields(array('name', 'image'))->get_all();
        } else if ($view === 'flashscreen') {
            $data['sponsors'] = $this->sponsors_model->fields(array('name', 'image'))->get_all(array('flashscreen' => 1));
        }
        if ($data['sponsors']) {
            shuffle($data['sponsors']);
            return $this->send_response($data);
        }
        return $this->send_error('ERROR');
    }

    /**
     * go4slam members can post content
     * POST:
     *  type STRING blogpost
     *      - title
     *      - short descriptio
     *      - description
     *      - image
     * Returns validation errors or true
     */
    public function new_blogpost($data = false) {
        $this->check_auth();
        $post_data = array(
            'title' => $data ? $data['title'] : $this->input->post('title'),
            'short_description' => $data ? $data['short_description'] : $this->input->post('short_description'),
            'description' => $data ? $data['description'] : $this->input->post('description'),
            'user_id' => $data ? $data['user_id'] : $this->user_id
        );
        if (is_array($data)) {
            $post_data['publisher_id'] = $data['publisher_id'];
        }
        if (!$post_data['title']) {
            return $this->send_error('NO_TITLE');
        } elseif (!$post_data['short_description']) {
            return $this->send_error('NO_SHORT_DESCRIPTION');
        } elseif (!$post_data['description']) {
            return $this->send_error('NO_DESCRIPTION');
        } elseif (!$_FILES['image']) {
            return $this->send_error('NO_IMAGE');
        }
        foreach ($post_data as $key => $value) {
            $post_data[$key] = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $value);
        }
        $this->load->helper('image_upload');
        if ($img = do_image_upload(config_item('src_path_blog_images'), 10000, 250, 'image')) {
            if (isset($img['error'])) {
                return $this->send_error('ERROR');
            }
            $post_data['image'] = reset($img);
        }
        $this->load->model('blog_posts_model');
        if (!$id = $this->blog_posts_model->insert($post_data)) {
            return $this->error('INSERT_FAIL');
        }
        $this->timeline_model->insert(array('item_id' => $id, 'type' => 'blog_post'));
        $this->event_log();
        return $this->send_success();
    }

    /**
     * Users can see upcomming events
     * POST:
     *  start_date - STRING
     *  end_date - STRING
     *  filter - STRING
     * Returns validation errors or associative array
     */
    public function get_calendar() {
        $start = date('Y-m-d H:i:s', strtotime($this->input->post('start_date')));
        $end = date('Y-m-d H:i:s', strtotime($this->input->post('end_date')));
        $filter = $this->input->post('filter');
        if ($start && $end) {
            if (isset($filter) && !empty($filter) && !in_array($filter, config_item('event_types'))) {
                return $this->send_error('INVALID_FILTER');
            }
            $this->load->model('events_model');
            $where = array('start_date >=' => $start, 'end_date <=' => $end);
            if (isset($filter) && !empty($filter)) {
                $where['type'] = $filter;
            }
            if ($events = $this->events_model->get_all($where)) {
                setlocale(LC_TIME, 'nl_NL.UTF-8');
                foreach ($events as &$event) {
                    $event['readable_start_date'] = strftime('%A %d %B %G', strtotime($event['start_date']));
                    $event['readable_end_date'] = strftime('%A %d %B %G', strtotime($event['end_date']));
                }
                $this->event_log();
                return $this->send_response($events);
            }
            return $this->send_error('NO_RESULTS');
        } else {
            return $this->send_error('ERROR');
        }
    }

    /**
     * Get all default images for in the app
     * Returns associative array:
     * - image STRING
     * - location STRING
     */
    public function get_default_images() {
        $this->load->model('app_images_model');
        $data = array();
        if ($data['default_images'] = $this->app_images_model->fields(array('image', 'location'))->get_all()) {
            if (true || count($data['default_images']) === 0) {
                return $this->send_error('NO_RESULTS');
            }
            return $this->send_response($data);
        }
        return $this->send_error('ERROR');
    }

    public function new_blogpost_for_player() {
        $this->check_auth();
        $data = $this->input->post();
        if (!$this->ion_auth->in_group('coach', $this->user_id)) {
            return $this->send_error('AUTH_ERROR');
        }
        if (!$this->users_model->get($data['user_id'])) {
            return $this->send_error('INVALID_USER');
        }
        $data['publisher_id'] = $this->user_id;
        return $this->new_blogpost($data);
    }

	public function get_players() {
		$this->load->model('groups_model');
		$this->load->model('users_groups_model');
		$this->load->model('users_model');
		$group = $this->groups_model->get(array('name' => 'general'));
		$users_in_group = $this->users_groups_model->get_all(array('group_id' => $group['id']));
		foreach ($users_in_group as &$user) {
			$user = $user['user_id'];
		}
		$this->db->where_in('id', $users_in_group);
		$select = array('id', 'email', 'image', 'cover_image', 'ranking', 'first_name', 'prefix', 'last_name');
		$users = $this->users_model->fields($select)->get_all();
		if (count($users) < 1) {
			return $this->send_error('NO_RESULTS');
		}
		return $this->send_response($users);
	}

	public function get_about_contents() {
		if ($text = $this->db->select('text')->limit(1)->order_by('text')->get('about')->row()) {
			return $this->send_response($text);
		}
		return $this->send_error('ERROR');
	}

	public function get_event_details() {
		if ($event_id = intval($this->input->post('event_id'))) {
			$this->load->model('events_model');
			if ($event = $this->events_model->limit(1)->get($event_id)) {
				return $this->send_response($event);
			}
			return $this->send_error('NO_EVENT');
		}
		return $this->send_error('NO_EVENT_ID');
	}

}
