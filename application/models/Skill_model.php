<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SKill_model extends CR_Model
{
  protected $column_order = [NULL, "ls.`skill_name`", "lsk.`kategori`"];
  protected $column_search = ["ls.`skill_name`", "lsk.`kategori`"];
  protected $order = ["ls.`skill_id`" => "ASC"];

  public function __construct()
  {
    parent::__construct();
  }

  public function query_skill()
  {
    $this->db->select("*")->from("list_skill ls")->join("list_skill_kategori lsk", "lsk.`kategori_id` = ls.`kategori_id`");
    $i = 1;
    foreach ($this->column_search as $item) {
      if ($_POST["search"]["value"]) {
        if ($i == 1) {
          $this->db->group_start();
          $this->db->like($item, $_POST["search"]["value"]);
        } else {
          $this->db->or_like($item, $_POST["search"]["value"]);
        }
        if (count($this->column_search) == $i) {
          $this->db->group_end();
        }
      }
      $i++;
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($this->column_order[$_POST["order"]["0"]["column"]], $_POST["order"]["0"]["dir"]);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_skill_listed()
  {
    $this->query_skill();
    if ($_POST["length"] != -1) {
      $this->db->limit($_POST["length"], $_POST["start"]);
      $query = $this->db->get();
      // var_dump($this->db->last_query());
      return $query->result();
    }
  }

  public function count_skill_filtered()
  {
    $this->query_skill();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_skill_all()
  {
    $this->db->from("list_skill");
    return $this->db->count_all_results();
  }

  public function get_skill_kategori_listed()
  {
    return $this->db->get("list_skill_kategori")->result();
  }

  public function add_skill($input)
  {
    $response = create_response();
    $data = [
      "skill_name" => $input->skill_name,
      "kategori_id" => $input->kategori_id
    ];
    $query = $this->db->insert("list_skill", $data);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Sukses menambah skill!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function get_skill_detail_json($skill_id)
  {
    $response = new stdClass();
    $query = $this->db->select("*")->from("list_skill ls")->join("list_skill_kategori lsk", "lsk.`kategori_id` = ls.`kategori_id`")->where("ls.`skill_id`", $skill_id)->get();
    if ($query) {
      $data = $query->row();
      // var_dump($this->db->last_query());
      $response->id = encrypt_url($data->skill_id);
      $response->skillName = $data->skill_name;
      $response->kategoriId = $data->kategori_id;
      $response->success = TRUE;
    } else {
      $response->success = FALSE;
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function edit_skill($input)
  {
    $response = create_response();
    $data = [
      "skill_name" => $input->skill_name,
      "kategori_id" => $input->kategori_id
    ];
    $where = ["skill_id" => decrypt_url($input->skill_id)];
    $query = $this->db->update("list_skill", $data, $where);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Sukses mengupdate skill!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function delete_skill($skill_id)
  {
    $response = create_response();
    $where = ["skill_id" => $skill_id];
    $query = $this->db->delete("list_skill", $where);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Berhasil menghapus skill!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }
}
