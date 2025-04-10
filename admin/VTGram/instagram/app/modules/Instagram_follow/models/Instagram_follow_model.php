<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instagram_follow_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	public function getFollowSchedules($type){
        $this->db->select('*');
        $this->db->from(SCHEDULE_TB);
        $this->db->where("schedule_type", $type);
        $this->db->where("uid", session("uid"));
        $this->db->order_by("id", "desc");
        $this->db->limit(30, (int)post("page")*30);
        $query = $this->db->get();
        if($query){
            $result = $query->result();
            if(!empty($result)){
            	foreach ($result as $key => $row) {
            		$av = $this->model->get("avatar", INSTAGRAM_ACCOUNT_TB, "id = '".$row->account."'");
            		if(!empty($av)){
	            		$result[$key]->avatar = $av->avatar;
            		}else{
            			$result[$key]->avatar = BASE."assets/img/no-avatar.png";
            		}
            	}
            }

            return $result;
        }else{
            return false;
        }
	}

    public function countFollowSchedules($type){
        $this->db->select('*');
        $this->db->from(SCHEDULE_TB);
        $this->db->where("schedule_type", $type);
        $this->db->where("uid", session("uid"));
        $this->db->order_by("id", "desc");
        $this->db->limit(30, (int)post("page")*30);
        $query = $this->db->get();
        if($query){
            $result = (int)$query->num_rows();
            return $result;
        }else{
            return 0;
        }
    }

	function getList($limit=-1, $page=-1){
		if($limit == -1){
			$this->db->select('count(*) as sum');
		}else{
			$this->db->select('*');
		}
		
		$this->db->from(INSTAGRAM_FOLLOW_TB);

		if($limit != -1) {
			$this->db->limit($limit,$page);
		}

		if(get("type")){
			$this->db->where("type = '".get("type")."'");
		}

		if(get("id")){
			$this->db->where("account_id = '".(int)get("id")."'");
		}

		$this->db->where("uid = '".session("uid")."'");

		$this->db->order_by('created','desc');
		$query = $this->db->get();

		if($query->result()){
			if($limit == -1){
				return $query->row()->sum;
			}else{
				$result =  $query->result();
				return $result;
			}
		}else{
			return false;
		}
	}

}