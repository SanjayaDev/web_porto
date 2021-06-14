<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class CR_Model extends CI_Model {
  protected $session_token;

  public function __construct()
  {
    parent::__construct();
    date_default_timezone_set("Asia/Jakarta");
    $this->session_token = $this->session->session_token != NULL ? $this->session->session_token : NULL;
  }

  public function protect_login()
  {
    $response = create_response();
    if ($this->session->is_login === TRUE) {
      $response->success = TRUE;
    } else {
      $response->message = "Anda wajib melakukan login dahulu!";
    }
    return $response;
  }

  public function check_session_login()
  {
    $response = create_response();
    if ($this->session->is_login != NULL) {
      $query = $this->db->get_where("list_session_token", ["session_token" => $this->session_token]);
      if ($query) {
        if ($query->num_rows() == 1) {
          $data = $query->row();
          $date_now = date("Y-m-d H:i:s");
          if ($data->expire_time < $date_now) {
            $response->message = "Session is expired!";
          } else {
            $update_session = $this->update_session_login();
            if ($update_session->success === TRUE) {
              $response->success = TRUE;
            } else {
              $response->message = $update_session->message;
            }
          }
        } else {
          $response->message = "Session is not valid!";
        }
      } else {
        $response->message = "Query failed!";
      }
    } else {
      $response->success = TRUE;
    }
    return $response;
  }

  private function update_session_login()
  {
    $response = create_response();
    $data = [
      "active_time" => date("Y-m-d H:i:s"),
      "expire_time" => date("Y-m-d H:i:s", strtotime("+15 minutes"))
    ];
    $query = $this->db->update("list_session_token", $data, ["session_token" => $this->session_token]);
    if ($query) {
      $response->success = TRUE;
    } else {
      $response->message = "Query update session failed!";
    }
    return $response;
  }

  protected function check_login_user($admin_id)
  {
    $response = create_response();
    $query = $this->db->select("*")->from("list_session_token")->where("admin_id", $admin_id)->order_by("session_id", "DESC")->limit(1)->get();
    // var_dump($this->db->last_query());
    if ($query) {
      if ($query->num_rows() == 1) {
        $data = $query->row();
        if ($data->is_login == 1) {
          $expire_time = $data->expire_time;
          $now = date("Y-m-d H:i:s");
          if ($expire_time >= $now) {
            $response->message = "Saat ini user sedang digunakan!";
          } else {
            $response->success = TRUE;
          }
        } else {
          $response->success = TRUE;
        }
      } else {
        $response->success = TRUE;
      }
    } else {
      $response->message = "Query check login user failed!";
    }
    return $response;
  }

  protected function create_session_token($admin_id)
  {
    $response = create_response();
    $error = FALSE;
    $duplicate_token = TRUE;
    do {
      $random_string = $this->generate_random_string();
      $query = $this->db->get_where("list_session_token", ["session_token" => $random_string]);
      if ($query) {
        if ($query->num_rows() == 0) {
          $duplicate_token = FALSE;
        } else {
          $duplicate_token = TRUE;
        }
      } else {
        $response->message = "Query check duplicate token failed!";
        $error = TRUE;
        break;
      }
    } while($duplicate_token === TRUE);
    
    if ($error === FALSE) {
      $data = [
        "session_token" => $random_string,
        "admin_id" => $admin_id,
        "active_time" => date("Y-m-d H:i:s"),
        "expire_time" => date("Y-m-d H:i:s", strtotime("+15 minutes")),
        "is_login" => 1
      ];
      $query = $this->db->insert("list_session_token", $data);
      if ($query) {
        $response->success = TRUE;
        $response->session_token = $random_string;
      } else {
        $response->message = "Query create session failed!";
      }
    }

    return $response;
  }

  public function generate_random_string($size = FALSE)
  {
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $length = strlen($characters);
    $random_string = "";
    if ($size === FALSE or !is_integer($size)) {
      $size = 26;
    }
    for ($i = 0; $i < $size; $i++) {
      $random_string .= $characters[rand(0, $length - 1)];
    }
    return $random_string;
  }
}