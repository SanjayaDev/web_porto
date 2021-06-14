<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CR_Controller {

  public function __construct()
  {
    parent::__construct();
    $protect_login = $this->Auth->protect_login();
    if ($protect_login->success === FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$protect_login->message')</script>");
      redirect("login");
      exit;
    }
    $this->load->model("Dashboard_model", "model");
  }

  public function view_dashboard_page()
  {
    $data = [
      "title" => "Dashboard",
      "menu" => "Dashboard",
      "content" => "admin/v_dashboard",
      "statistik" => $this->model->get_statistik()
    ];
    $this->load->view("layout/wrapper", $data);
  }
}