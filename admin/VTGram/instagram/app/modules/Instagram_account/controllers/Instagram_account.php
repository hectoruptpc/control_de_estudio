<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_account extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}
	
	public function index(){
		$page_size      = 25;
        $page_num       = (get('p')) ? get('p') : 1;
        $total_row      = $this->model->getList(-1,-1);
        $start_row      = (get('p'))?$page_num:0;

        $config['base_url'] = PATH."instagram/account"."?";
        $config['total_rows'] = $total_row;
        $config['per_page'] = $page_size;
        $config['query_string_segment'] = 'p';
        $config['page_query_string'] = TRUE;
        $this->pagination->initialize($config);

		$data= array(
			'result' => $this->model->getList($page_size, $start_row)
		);

		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function ajax_update(){
		$username = post('username');
		$password = post('password');

		if($username == "" || $password == ""){
			ms(array(
				"st"  => "error",
				"label" => "bg-red",
				"txt" => l('Please input all fields')
			));
		}

		$IG_Oauth = Instagram_Login($username, $password);
		if(is_array($IG_Oauth) && isset($IG_Oauth['st'])){
			ms($IG_Oauth);
		}

		$Info = $IG_Oauth->getSelfUsernameInfo();

		$data = array(
			"uid"           => session("uid"),
			"username"      => $username,
			"password"      => $password,
			"avatar"        => $Info->user->profile_pic_url
		);
				
		$id = (int)post("id");
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = ".session("uid"));
		if($id == 0){
			if(COUNT_ACCOUNT < MAXIMUM_ACCOUNT){
				$checkAccount = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "username = '".$username."' AND uid = ".session("uid"));
				if(!empty($checkAccount)){
					ms(array(
						"st"    => "error",
						"label" => "bg-red",
						"txt"   => l('This instagram account already exists')
					));
				}

				$this->db->insert(INSTAGRAM_ACCOUNT_TB, $data);
				$id = $this->db->insert_id();
			}else{
				ms(array(
					"st"    => "error",
					"label" => "bg-orange",
					"txt"   => l('Oh sorry! You have exceeded the number of accounts allowed, You are only allowed to update your account')
				));
			}
		}else{
			$checkAccount = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "username = '".$username."' AND id != '".$id."' AND uid = ".session("uid"));
			if(!empty($checkAccount)){
				ms(array(
					"st"    => "error",
					"label" => "bg-red",
					"txt"   => l('This instagram account already exists')
				));
			}

			$this->db->update(INSTAGRAM_ACCOUNT_TB, $data, array("id" => post("id")));
		}

		ms(array(
			"st"    => "success",
			"label" => "bg-light-green",
			"txt"   => l('Update successfully')
		));
	}

	public function ajax_get_groups(){
		$account = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "id = '".post("id")."' AND uid = '".session("uid")."'");
		if(!empty($account)){
			switch (post("type")) {
				case 'page':
					$IG_Oauth = Instagram_Login($account->username, $account->password);
					if(is_array($IG_Oauth) && isset($IG_Oauth['st'])){
						ms($IG_Oauth);
					}else{
						//IG Info 
						$IG_Info = $IG_Oauth->getSelfUsernameInfo();
						if($IG_Info->status != "ok"){
							ms(array(
								"st"  => "error",
								"label" => "bg-red",
								"txt" => l('Connect failure')
							));
						}

						$data = array(
							"avatar" => $IG_Info->user->profile_pic_url
						);

						$this->db->update(INSTAGRAM_ACCOUNT_TB, $data, array("id" => post("id")));
						
						ms(array(
							"st"    => "success",
							"label" => "bg-light-green",
							"txt"   => l('Update successfully')
						));
					}
					break;
			}
			ms(array(
				'st' 	=> 'success',
				"label" => "bg-light-green",
				'txt' 	=> l('Successfully')
			));
		}else{
			ms(array(
				'st' 	=> 'error',
				"label" => "bg-red",
				'txt' 	=> l('Update failure')
			));
		}
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					deleteDir(APPPATH.'libraries/Instagram/data/'.$POST->username);
					$this->db->delete(INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
					break;
				
				case 'active':
					$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
					break;

				case 'disable':
					$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
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
				$POST = $this->model->get('*', INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							deleteDir(APPPATH.'libraries/Instagram/data/'.$POST->username);
							$this->db->delete(INSTAGRAM_ACCOUNT_TB, "id = '{$id}' AND uid = '".session("uid")."'");
							break;
						
						case 'active':
							$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
							break;

						case 'disable':
							$this->db->update(INSTAGRAM_ACCOUNT_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
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