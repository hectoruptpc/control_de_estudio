<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends MY_Model {
	public function __construct(){
		parent::__construct();
	}

	public function getSchedules(){
        $this->db->select('*');
        $this->db->from(SCHEDULE_TB);
        $this->db->where("schedule_type", "post");
        $this->db->where("uid", session("uid"));
        $this->db->order_by("id", "desc");
        $this->db->limit(30, (int)post("page")*30);
        $query = $this->db->get();
        if($query){
            $result = $query->result();
            return $result;
        }else{
            return false;
        }
	}

    public function countSchedules(){
        $this->db->select('*');
        $this->db->from(SCHEDULE_TB);
        $this->db->where("schedule_type", "post");
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
}