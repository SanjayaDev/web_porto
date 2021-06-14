<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Front_model extends CI_Model {
  
  public function get_aboutme()
  {
    return $this->db->get("list_aboutme")->row();
  }

  public function get_skill_kategori_listed()
  {
    $sub_sql = "(SELECT COUNT(`skill_id`) FROM `list_skill` ls WHERE ls.`kategori_id` = lsk.`kategori_id`)";
    $sql = "SELECT $sub_sql AS `count`, lsk.* "
        .  "FROM `list_skill_kategori` lsk "
        .  "HAVING $sub_sql > 0;";
    $query = $this->db->query($sql);
    if ($query) {
      return $query->result();
    } else {
      return FALSE;
    }
  }

  public function get_skill_listed()
  {
    return $this->db->get("list_skill")->result();
  }

  public function get_portofolio_listed()
  {
    return $this->db->select("*")->from("list_portofolio lp")->join("list_skill_kategori lsk", "lsk.`kategori_id` = lp.`kategori_id`")
            ->where("lp.`is_active`", 1)->get()->result();
  }

  public function get_social_listed()
  {
    return $this->db->get("list_kontak")->result();
  }

  public function add_inquiry($input)
  {
    $response = create_response();
    $data = [
      "inquiry_name" => $input->inquiry_name,
      "inquiry_email" => $input->inquiry_email,
      "inquiry_message" => $input->inquiry_message,
      "is_response" => 0
    ];
    $query = $this->db->insert("list_inquiry", $data);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Terimakasih, pesan anda telah terkirim!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }
}