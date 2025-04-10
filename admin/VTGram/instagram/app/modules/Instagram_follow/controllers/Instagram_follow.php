<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_follow extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$page_size      = 100;
        $page_num       = ((int)get('p')) ? (int)get('p') : 1;
        $total_row      = $this->model->getList(-1,-1);
        $start_row      = ((int)get('p'))?$page_num:0;

        $config['base_url'] = PATH."instagram/follow/log"."?type=".(int)get('type')."&id=".(int)get("id");
        $config['total_rows'] = $total_row;
        $config['per_page'] = $page_size;
        $config['query_string_segment'] = 'p';
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

		$data= array(
			'accounts' => $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."'"),
			'result'   => $this->model->getList($page_size, $start_row)
		);

		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function follow(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$data = array(
			'accounts' => $accounts,
			'countSchedule' => $this->model->countFollowSchedules("follow")
		);
		$this->template->title(TITLE);
		$this->template->build('follow', $data);
	}

	public function ajax_follow_page(){
		$result = $this->model->getFollowSchedules("follow");
        ms(array(
			'st' 	 => 'success',
			'page'   => !empty($result)?(int)post("page")+1:-1,
			'result' => json_encode($this->load->view("ajax_follow_list", array("schedule" => $result), true)),
			'txt' 	 => l('successfully')
		));
	}

	public function ajax_edit_follow_page(){
		$item = $this->model->get("*", SCHEDULE_TB, "id = '".(int)post("id")."' AND uid = '".session("uid")."'");
		if(!empty($item)){
			$data = array(
				"item" => $item
			);
			$this->load->view('ajax_edit_follow_page', $data, false);
		}
	}

	public function like(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$data = array(
			'accounts' => $accounts,
			'countSchedule' => $this->model->countFollowSchedules("auto_like")
		);
		$this->template->title(TITLE);
		$this->template->build('like', $data);
	}

	public function ajax_like_page(){
		$result = $this->model->getFollowSchedules("auto_like");
        ms(array(
			'st' 	 => 'success',
			'page'   => !empty($result)?(int)post("page")+1:-1,
			'result' => json_encode($this->load->view("ajax_like_list", array("schedule" => $result), true)),
			'txt' 	 => l('successfully')
		));
	}

	public function ajax_edit_like_page(){
		$item = $this->model->get("*", SCHEDULE_TB, "id = '".(int)post("id")."' AND uid = '".session("uid")."'");
		if(!empty($item)){
			$data = array(
				"item" => $item
			);
			$this->load->view('ajax_edit_like_page', $data, false);
		}
	}

	public function comment(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$data = array(
			'accounts' => $accounts,
			'countSchedule' => $this->model->countFollowSchedules("auto_comment")
		);
		$this->template->title(TITLE);
		$this->template->build('comment', $data);
	}

	public function ajax_comment_page(){
		$result = $this->model->getFollowSchedules("auto_comment");
        ms(array(
			'st' 	 => 'success',
			'page'   => !empty($result)?(int)post("page")+1:-1,
			'result' => json_encode($this->load->view("ajax_like_list", array("schedule" => $result), true)),
			'txt' 	 => l('successfully')
		));
	}

	public function ajax_edit_comment_page(){
		$item = $this->model->get("*", SCHEDULE_TB, "id = '".(int)post("id")."' AND uid = '".session("uid")."'");
		if(!empty($item)){
			$data = array(
				"item" => $item
			);
			$this->load->view('ajax_edit_comment_page', $data, false);
		}
	}

	public function unfollow(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$data = array(
			'accounts' => $accounts,
			'countSchedule' => $this->model->countFollowSchedules("unfollow")
		);
		$this->template->title(TITLE);
		$this->template->build('unfollow', $data);
	}

	public function ajax_unfollow_page(){
		$result = $this->model->getFollowSchedules("unfollow");
        ms(array(
			'st' 	 => 'success',
			'page'   => !empty($result)?(int)post("page")+1:-1,
			'result' => json_encode($this->load->view("ajax_unfollow_list", array("schedule" => $result), true)),
			'txt' 	 => l('successfully')
		));
	}

	public function ajax_edit_unfollow_page(){
		$item = $this->model->get("*", SCHEDULE_TB, "id = '".(int)post("id")."' AND uid = '".session("uid")."'");
		if(!empty($item)){
			$data = array(
				"item" => $item
			);
			$this->load->view('ajax_edit_unfollow_page', $data, false);
		}
	}

	public function followback(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$data = array(
			'accounts' => $accounts,
			'countSchedule' => $this->model->countFollowSchedules("followback")
		);
		$this->template->title(TITLE);
		$this->template->build('followback', $data);
	}

	public function ajax_followback_page(){
		$result = $this->model->getFollowSchedules("followback");
        ms(array(
			'st' 	 => 'success',
			'page'   => !empty($result)?(int)post("page")+1:-1,
			'result' => json_encode($this->load->view("ajax_followback_list", array("schedule" => $result), true)),
			'txt' 	 => l('successfully')
		));
	}

	public function ajax_edit_followback_page(){
		$item = $this->model->get("*", SCHEDULE_TB, "id = '".(int)post("id")."' AND uid = '".session("uid")."'");
		if(!empty($item)){
			$data = array(
				"item" => $item
			);
			$this->load->view('ajax_edit_followback_page', $data, false);
		}
	}

	public function ajax_action_follow(){
		$account = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1 AND username = '".post("username")."'");
		if(!empty($account)){
			$resutl = INSTAGRAM_FOLLOW(post("action"), $account->username, post("id"));
		}

		if(is_object($resutl)){
			print_r(json_encode(array(
				'st' 	=> 'success',
				'txt' 	=> l('successfully')
			)));
		}else{
			print_r(json_encode(array(
				'st' 	=> 'error',
				'txt' 	=> $resutl
			)));
		}
		
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					$this->db->delete(INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
					break;
				
				case 'active':
					$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
					break;

				case 'disable':
					$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
					break;
			}
		}

		$json= array(
			'st' 	=> 'success',
			'txt' 	=> l('successfully')
		);

		print_r(json_encode($json));
	}

	public function ajax_action_multiple(){
		$ids =$this->input->post('id');
		if(!empty($ids)){
			foreach ($ids as $id) {
				$POST = $this->model->get('*', INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							$this->db->delete(INSTAGRAM_FOLLOW_TB, "id = '{$id}' AND uid = '".session("uid")."'");
							break;
						
						case 'active':
							$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
							break;

						case 'disable':
							$this->db->update(INSTAGRAM_FOLLOW_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
							break;
					}
				}
			}
		}

		print_r(json_encode(array(
			'st' 	=> 'success',
			'txt' 	=> l('-successfully')
		)));
	}
} 