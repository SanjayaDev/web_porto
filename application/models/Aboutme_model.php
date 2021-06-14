<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Aboutme_model extends CR_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function get_aboutme()
  {
    return $this->db->get("list_aboutme")->row();
  }

  public function change_about_me($input, $description)
  {
    $response = create_response();
    $error = FALSE;
    $data = [
      "aboutme_fullName" => $input->aboutme_fullName,
      "aboutme_profesionalName" => $input->aboutme_profesionalName,
      "aboutme_description" => $description,
      "aboutme_linkedin" => $input->aboutme_linkedin,
      "aboutme_github" => $input->aboutme_github,
      "aboutme_dribbble" => $input->aboutme_dribbble
    ];
    if (isset($_FILES["photo_path"]["name"]) && !empty($_FILES["photo_path"]["name"])) {
      // var_dump("Mausk");
      $file_upload = $this->upload_image();
      if ($file_upload->success === TRUE) {
        $data["photo_path"] = $file_upload->file_path;
      } else {
        $error = TRUE;
        $response->message = $file_upload->message;
      }
    }

    if ($error === FALSE) {
      $query = $this->db->update("list_aboutme", $data, ["aboutme_id" => 1]);
      if ($query) {
        $response->success = TRUE;
        $response->message = "Success updated about me!";
      } else {
        $response->message = "Query updated about me failed!";
      }
    }
    return $response;
  }

  private function deleted_old_photo()
  {
    $response = create_response();
    $query = $this->db->get_where("list_aboutme", ["aboutme_id" => 1]);
    $old_data = $query->row();
    $array = explode("/", $old_data->photo_path);
    if (count($array) > 2) {
      $old_photo = "./assets/img/" . end($array);
      if (unlink($old_photo)) {
        $response->success = TRUE;
      } else {
        $response->message = "Failed deleted old photo!";
      }
    } else {
      $response->success = TRUE;
    }
    return $response;
  }

  private function upload_image()
  {
    $response = create_response();
    $file = explode(".", $_FILES["photo_path"]["name"]);
    $file_name = "PHOTO_ABOUTME.".end($file);
    $config['upload_path']          = './assets/img/';
    $config['allowed_types']        = 'gif|jpg|png|jpeg';
    $config['max_size']             = 10000;
    $config['max_width']            = 5000;
    $config['max_height']           = 3500;
    $config["detect_mime"]          = TRUE;
    $config["mod_mime_fix"]         = TRUE;
    $config["file_name"]            = $file_name;
    $config["overwrite"]            = TRUE;

    $this->load->library('upload');
    $this->upload->initialize($config);

    if ($this->upload->do_upload("photo_path")) {
      $response->success = TRUE;
      $response->file_path = base_url("assets/img/".$file_name);
    } else {
      $response->message = $this->upload->display_errors();
    }
    return $response;
  }
}
