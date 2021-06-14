<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CR_Model {
  public function __construct()
  {
    parent::__construct();
  }

  public function get_statistik()
  {
    $response = new stdClass();
    $response->count_skill = $this->db->select("COUNT(`skill_id`) AS `count`")->from("list_skill")->get()->row()->count;
    $response->count_portofolio = $this->db->select("COUNT(`portofolio_id`) AS `count`")->from("list_portofolio")->where("is_active", 1)->get()->row()->count;
    $response->count_inquiry = $this->db->select("COUNT(`inquiry_id`) AS `count`")->from("list_inquiry")->get()->row()->count;
    return $response;
  }
}