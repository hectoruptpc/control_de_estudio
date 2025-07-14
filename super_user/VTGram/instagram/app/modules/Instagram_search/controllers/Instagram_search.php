<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_search extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$result = array();
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$account = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1 AND username = '".get("username")."'");
		if(!empty($account) && get("keyword") != ""){
			$result = INSTAGRAM_SEARCH(get("type"), $account->username, get("keyword"));
		}

		$data = array(
			'result'   => $result,
			'accounts' => $accounts
		);

		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function ajax_search_feed(){
		if(post("account")){
			if(post('type') == "user" && post("user") == ""){
				echo l('please-enter-keyword');
			}else{
				$result = array();
				$account = $this->model->get("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1 AND id = '".post("account")."'");
				if(!empty($account)){
					$result = INSTAGRAM_SEARCH_FEED(post("type"), $account->username, post("user"));
				}
			
				$data = array(
					"result" => $result
				);

				$this->load->view((post("type") == "following" || post("type") == "followers")?"ajax_search_user":"ajax_search_feed", $data, false);
			}
		}else{
			echo l('please-select-an-account');
		}
	}
}