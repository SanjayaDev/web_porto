<?php

defined('BASEPATH') or exit('No direct script access allowed');

class CR_Controller extends CI_Controller
{
  protected $session_token;

  public function __construct()
  {
    parent::__construct();
    $this->load->model("Auth_model", "Auth");
    $this->session_token = $this->session->session_token != NULL ? $this->session->session_token : NULL;
    $check_session = $this->Auth->check_session_login();
    if ($check_session->success === FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('warning', 'Gagal!', '$check_session->message')</script>");
      redirect("login");
      exit;
    }
  }

  protected function generate_anti_cache()
  {
    return $this->Auth->generate_random_string(10);
  }
}