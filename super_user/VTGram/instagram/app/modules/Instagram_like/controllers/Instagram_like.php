<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_like extends MX_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model(get_class($this).'_model', 'model');
	}

	public function index(){
		$accounts = $this->model->fetch("*", INSTAGRAM_ACCOUNT_TB, "uid = '".session("uid")."' AND status = 1", "created", "asc");
		$data = array(
			'accounts' => $accounts,
			'countSchedule' => $this->model->countSchedules("like")
		);
		$this->template->title(TITLE);
		$this->template->build('index', $data);
	}

	public function ajax_page(){
		$result = $this->model->getSchedules("like");
        ms(array(
			'st' 	 => 'success',
			'page'   => !empty($result)?(int)post("page")+1:-1,
			'result' => json_encode($this->load->view("ajax_list", array("schedule" => $result), true)),
			'txt' 	 => l('successfully')
		));
	}
}