<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Portofolio_model extends CR_Model
{
  protected $column_order = [NULL, "lp.`portofolio_name`", "lp.`is_active`"];
  protected $column_search = ["lp.`portofolio_name`", "lp.`is_active`"];
  protected $order = ["lp.`portofolio_id`" => "ASC"];

  public function __construct()
  {
    parent::__construct();
  }

  public function query_portofolio()
  {
    $this->db->select("*")->from("list_portofolio lp")->join("list_skill_kategori lsk", "lsk.`kategori_id` = lp.`kategori_id`");
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

  public function get_portofolio_listed()
  {
    $this->query_portofolio();
    if ($_POST["length"] != -1) {
      $this->db->limit($_POST["length"], $_POST["start"]);
      $query = $this->db->get();
      // var_dump($this->db->last_query());
      return $query->result();
    }
  }

  public function count_portofolio_filtered()
  {
    $this->query_portofolio();
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_portofolio_all()
  {
    $this->db->from("list_portofolio");
    return $this->db->count_all_results();
  }

  public function get_kategori_listed()
  {
    return $this->db->get("list_skill_kategori")->result();
  }

  public function add_portofolio($input, $description)
  {
    $response = create_response();
    $error = FALSE;
    $data = [
      "portofolio_name" => $input->portofolio_name,
      "portofolio_description" => $description,
      "kategori_id" => $input->kategori_id,
      "is_active" => $input->is_active,
      "created_date" => date("Y-m-d H:i:s")
    ];

    if (isset($_FILES["portofolio_image"]["name"]) && $_FILES["portofolio_image"]["name"] != "") {
      $upload_foto = $this->upload_image();
      if ($upload_foto->success === TRUE) {
        $data["portofolio_image"] = $upload_foto->file_path;
      } else {
        $error = TRUE;
        $response->message = $upload_foto->message;
      }
    }

    if ($error === FALSE) {
      $query = $this->db->insert("list_portofolio", $data);
      if ($query) {
        $response->success = TRUE;
        $response->message = "Sukses membuat portofolio!";
      } else {
        $response->message = "Query 1 failed!";
      }
    }

    return $response;
  }

  private function upload_image()
  {
    $response = create_response();
    $error = FALSE;

    $file_name = $_FILES["portofolio_image"]["name"];
    $array = explode(".", $file_name);
    $name = "PORTOFOLIO_PHOTO_0001." . end($array);
    $file_path = base_url("assets/img/portofolio/$name");
    $duplicate_foto = TRUE;
    $i = 0;
    do {
      $sql = "SELECT `portofolio_image` FROM `list_portofolio` "
        .  "WHERE `portofolio_image` LIKE '%$file_path%' "
        .  "ORDER BY `portofolio_id` DESC "
        .  "LIMIT 1 "
        .  "FOR UPDATE;";
      $query = $this->db->query($sql);
      if ($query) {
        if ($query->num_rows() > 0) {
          $data = $query->row();
          $array2 = explode("/", $data->portofolio_image);
          $array3 = explode(".", end($array2));
          $array4 = explode("_", $array3[0]);
          $number = (int)end($array4);
          $number++;
          $number = "000$number";
          $name = "PORTOFOLIO_PHOTO_" . substr($number, -4) . "." . end($array);
          $file_path = base_url("assets/img/portofolio/$name");
        }
        $check_foto = $this->db->get_where("list_portofolio", ["portofolio_image" => $file_path])->num_rows();
        if ($check_foto == 0) {
          $duplicate_foto = FALSE;
        }
      } else {
        $error = TRUE;
        $response->message = "Query select foto failed!";
        break;
      }

      if ($i > 5) {
        $error = TRUE;
        $response->message = "Infinite Loop!";
        break;
      }
    } while ($duplicate_foto === TRUE);

    if ($error === FALSE) {
      $config['upload_path']    = './assets/img/portofolio';
      $config['allowed_types']  = 'gif|jpg|png|jpeg';
      $config['max_size']       = '7000';
      $config['max_width']      = '5128';
      $config['max_height']     = '3512';
      $config["file_name"]      = $name;
      $config["detect_mime"]    = TRUE;

      $this->load->library("upload");
      $this->upload->initialize($config);

      if (!$this->upload->do_upload("portofolio_image")) {
        $response->message = $this->upload->display_errors();
      } else {
        $response->success = TRUE;
        $response->file_path = $file_path;
      }
    }
    return $response;
  }

  public function get_portofolio_detail_json($portofolio_id)
  {
    $response = new stdClass();
    $query = $this->db->select("*")->from("list_portofolio lp")->join("list_skill_kategori lsk", "lsk.`kategori_id` = lp.`kategori_id`")
      ->where("lp.`portofolio_id`", $portofolio_id)->get();
    if ($query) {
      $data = $query->row();
      $anti_cache = $this->generate_random_string(10);
      $response->id = encrypt_url($data->portofolio_id);
      $response->portofolioName = $data->portofolio_name;
      $response->portofolioDesc = $data->portofolio_description;
      $response->portofolioImg = "$data->portofolio_image?i=$anti_cache";
      $response->kategori = $data->kategori;
      $response->kategoriId = $data->kategori_id;
      $response->isActive = $data->is_active == 1 ? "Aktif" : "Non Aktif";
      $response->isActiveId = $data->is_active;
      $response->created = date("d M Y H:i", strtotime($data->created_date));
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function edit_portofolio($input, $description)
  {
    $response = create_response();
    $error = FALSE;
    $id = decrypt_url($input->portofolio_id);
    $file_path = "";
    $where = ["portofolio_id" => $id];
    $data = [
      "portofolio_name" => $input->portofolio_name,
      "portofolio_description" => $description,
      "kategori_id" => $input->kategori_id,
      "is_active" => $input->is_active
    ];

    if (isset($_FILES["portofolio_image"]["name"]) && $_FILES["portofolio_image"]["name"] != "") {
      $upload_foto = $this->upload_image();
      if ($upload_foto->success === TRUE) {
        $data["portofolio_image"] = $file_path = $upload_foto->file_path;
        $delete_old_photo = $this->delete_old_photo($id);
        if ($delete_old_photo->success === FALSE) {
          $error = TRUE;
          $response->message = $delete_old_photo->message;
        }
      } else {
        $error = TRUE;
        $response->message = $upload_foto->message;
      }
    }

    if ($error === FALSE) {
      $query = $this->db->update("list_portofolio", $data, $where);
      if ($query) {
        $response->success = TRUE;
        $response->message = "Sukses membuat portofolio!";
      } else {
        $response->message = "Query 1 failed!";
      }
    } else {
      if ($file_path != "") {
        $array = explode("/", $file_path);
        if (!unlink("./assets/img/portofolio/" . end($array))) {
          $response->message = "Failed rollback image!";
        }
      }
    }

    return $response;
  }

  private function delete_old_photo($id)
  {
    $response = create_response();
    $query = $this->db->select("`portofolio_image`")->from("list_portofolio lp")->where("lp.`portofolio_id`", $id)->get();
    if ($query) {
      $old_photo = $query->row()->portofolio_image;
      $array = explode("/", $old_photo);
      if (count($array) > 2) {
        if (unlink("./assets/img/portofolio/" . end($array))) {
          $response->success = TRUE;
        } else {
          $response->message = "Failed delete old photo!";
        }
      } else {
        $response->success = TRUE;
      }
    } else {
      $response->message = "Query failed!";
    }
    return $response;
  }

  public function delete_portofolio($id)
  {
    $response = create_response();
    $delete_photo = $this->delete_old_photo($id);
    if ($delete_photo->success === TRUE) {
      $query = $this->db->delete("list_portofolio", ["portofolio_id" => $id]);
      if ($query) {
        $response->success = TRUE;
        $response->message = "Sukses menghapus portofolio!";
      } else {
        $response->message = "Query failed!";
      }
    } else {
      $response->message = $delete_photo->message;
    }
    return $response;
  }
}
