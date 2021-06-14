<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Social_model extends CR_Model {
  protected $column_order_kontak = [NULL, "kontak_name", "kontak_value", "kontak_icon", "kontak_link"];
  protected $column_search_kontak = ["kontak_name", "kontak_value", "kontak_icon", "kontak_link"];
  protected $order_kontak = ["kontak_id" => "ASC"];

  protected $column_order_inquiry = [NULL, "inquiry_name", "inquiry_email", "inquiry_message"];
  protected $column_search_inquiry = [ "inquiry_name", "inquiry_email", "inquiry_message"];
  protected $order_inquiry = ["inquiry_id" => "ASC"];

  public function __construct()
  {
    parent::__construct();
  }

  public function query_kontak()
  {
    $this->db->select("*")->from("list_kontak");
    $i = 1;
    foreach ($this->column_search_kontak as $item) {
      if ($_POST["search"]["value"]) {
        if ($i == 1) {
          $this->db->group_start();
          $this->db->like($item, $_POST["search"]["value"]);
        } else {
          $this->db->or_like($item, $_POST["search"]["value"]);
        }
        if (count($this->column_search_kontak) == $i) {
          $this->db->group_end();
        }
      }
      $i++;
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($this->column_order_kontak[$_POST["order"]["0"]["column"]], $_POST["order"]["0"]["dir"]);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_kontak_listed()
  {
    $this->query_kontak();
    if ($_POST["length"] != -1) {
      $this->db->limit($_POST["length"], $_POST["start"]);
      $query = $this->db->get();
      // var_dump($this->db->last_query());
      return $query->result();
    }
  }

  public function count_kontak_filtered()
  {
    $this->query_kontak();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_kontak_all()
  {
    $this->db->from("list_kontak");
    return $this->db->count_all_results();
  }

  public function query_inquiry()
  {
    $this->db->select("*")->from("list_inquiry");
    $i = 1;
    foreach ($this->column_search_inquiry as $item) {
      if ($_POST["search"]["value"]) {
        if ($i == 1) {
          $this->db->group_start();
          $this->db->like($item, $_POST["search"]["value"]);
        } else {
          $this->db->or_like($item, $_POST["search"]["value"]);
        }
        if (count($this->column_search_inquiry) == $i) {
          $this->db->group_end();
        }
      }
      $i++;
    }
    if (isset($_POST["order"])) {
      $this->db->order_by($this->column_order_inquiry[$_POST["order"]["0"]["column"]], $_POST["order"]["0"]["dir"]);
    } else if (isset($this->order)) {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  public function get_inquiry_listed()
  {
    $this->query_inquiry();
    if ($_POST["length"] != -1) {
      $this->db->limit($_POST["length"], $_POST["start"]);
      $query = $this->db->get();
      // var_dump($this->db->last_query());
      return $query->result();
    }
  }

  public function count_inquiry_filtered()
  {
    $this->query_inquiry();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_inquiry_all()
  {
    $this->db->from("list_inquiry");
    return $this->db->count_all_results();
  }

  public function add_kontak($input)
  {
    $response = create_response();
    $data = [
      "kontak_name" => $input->kontak_name,
      "kontak_value" => $input->kontak_value,
      "kontak_icon" => $input->kontak_icon,
      "kontak_link" => $input->kontak_link
    ];
    $query = $this->db->insert("list_kontak", $data);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Sukses menambah kontak!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function get_kontak_detail_json($id)
  {
    $response = new stdClass();
    $query = $this->db->get_where("list_kontak", ["kontak_id" => $id]);
    if ($query) {
      $data = $query->row();
      $response->id = encrypt_url($data->kontak_id);
      $response->kontakName = $data->kontak_name;
      $response->kontakValue = $data->kontak_value;
      $response->kontakIcon = $data->kontak_icon;
      $response->kontakLink = $data->kontak_link;
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function edit_kontak($input)
  {
    $response = create_response();
    $data = [
      "kontak_name" => $input->kontak_name,
      "kontak_value" => $input->kontak_value,
      "kontak_icon" => $input->kontak_icon,
      "kontak_link" => $input->kontak_link
    ];
    $where = ["kontak_id" => decrypt_url($input->kontak_id)];
    $query = $this->db->update("list_kontak", $data, $where);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Sukses mengubah kontak!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function delete_kontak($id)
  {
    $response = create_response();
    $query = $this->db->delete("list_kontak", ["kontak_id" => $id]);
    if ($query) {
      $response->success = TRUE;
      $response->message = "Sukses menghapus kontak!";
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function response_inquiry($id)
  {
    $response = create_response();
    $query = $this->db->get_where("list_inquiry", ["inquiry_id" => $id, "is_response" => 0]);
    if ($query) {
      if ($query->num_rows() == 1) {
        $query2 = $this->db->update("list_inquiry", ["is_response" => 1], ["inquiry_id" => $id]);
        if ($query2) {
          $response->success = TRUE;
          $response->message = "Inquiry berhasil diresponse!";
        } else {
          $response->message = "Query 2 failed!";
        }
      } else {
        $response->message = "Inquiry sudah di response!";
      }
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }
}