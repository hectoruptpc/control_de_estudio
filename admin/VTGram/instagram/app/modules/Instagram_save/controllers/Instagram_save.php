<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_save extends MX_Controller {
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
 
	public function ajax_save_post(){
		$json = array();
		switch (post('type')) {
			case 'video':
				if(post('media') == ""){
					$json[] = array(
						"type"   => "media",
						"text"   => l('video-is-required')
					);
				}
				break;
			default:
				if(!post('media')){
					$json[] = array(
						"type"   => "media",
						"text"   => l('image-is-required')
					);
				}
				break;
		}

		if(!empty($json)){
			$json["st"] = "error";
		}else{
			if(post('title') == ""){
				$json["st"] = "title";
			}else{
				$data = array(
					'name'          => post('title'),
					'type'          => post('type'),
					'description'   => post('description'),
					'image'         => post('media'),
					'uid'           => session("uid"),
					'created'       => NOW
	 			);
				$this->db->insert(INSTAGRAM_SAVE_TB, $data);
				$json[] = array(
					"text"   => l('saved-successfully'),
					"st"     => "success"
				);
			}
		}

		print_r(json_encode($json));
	}

	public function get_save_post(){
		$check = $this->model->get("*", INSTAGRAM_SAVE_TB, "id = '".post("value")."' AND uid = '".session("uid")."'");
		print_r(json_encode($check));
	}

	public function ajax_action_item(){
		$id = (int)post('id');
		$POST = $this->model->get('*', INSTAGRAM_SAVE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
		if(!empty($POST)){
			switch (post("action")) {
				case 'delete':
					$this->db->delete(INSTAGRAM_SAVE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
					break;
				
				case 'active':
					$this->db->update(INSTAGRAM_SAVE_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
					break;

				case 'disable':
					$this->db->update(INSTAGRAM_SAVE_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
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
				$POST = $this->model->get('*', INSTAGRAM_SAVE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
				if(!empty($POST)){
					switch (post("action")) {
						case 'delete':
							$this->db->delete(INSTAGRAM_SAVE_TB, "id = '{$id}' AND uid = '".session("uid")."'");
							break;
						
						case 'active':
							$this->db->update(INSTAGRAM_SAVE_TB, array("status" => 1), "id = '{$id}' AND uid = '".session("uid")."'");
							break;

						case 'disable':
							$this->db->update(INSTAGRAM_SAVE_TB, array("status" => 0), "id = '{$id}' AND uid = '".session("uid")."'");
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