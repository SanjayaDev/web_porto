<?php

defined('BASEPATH') or exit('No direct script access allowed');

class About_me extends CR_Controller
{
  public function __construct()
  {
    parent::__construct();
    $protect_login = $this->Auth->protect_login();
    if ($protect_login->success === FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$protect_login->message')</script>");
      redirect("login");
      exit;
    }
    $this->load->model("Aboutme_model", "Aboutme");
  }

  public function view_about_me_management()
  {
    $data = [
      "title" => "About Me Management",
      "menu" => "About Me",
      "content" => "admin/v_about_me",
      "aboutme" => $this->Aboutme->get_aboutme()
    ];
    $this->load->view("layout/wrapper", $data);
  }

  public function validation_about_me_add()
  {
    $this->form_validation->set_rules('aboutme_fullName', 'Fullname', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('aboutme_profesionalName', 'profesionalName', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('aboutme_description', 'Fullname', 'required');
    $this->form_validation->set_rules('aboutme_linkedin', 'Fullname', 'valid_url');
    $this->form_validation->set_rules('aboutme_github', 'Fullname', 'valid_url');
    $this->form_validation->set_rules('aboutme_dribbble', 'Fullname', 'valid_url');

    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal', 'Data anda tidak lengkap/valid!')</script>");
      redirect("about_me");
    } else {
      $this->process_change_about_me();
    }
  }

  private function process_change_about_me()
  {
    $input = (object)html_escape($this->input->post());
    $description = $this->input->post("aboutme_description");
    $check = $this->Aboutme->change_about_me($input, $description);
    if ($check->success === TRUE) {
      $this->session->set_flashdata("pesan", "<script>sweet('success', 'Sukses!', '$check->message')</script>");
    } else {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$check->message')</script>");
    }
    redirect("about_me");
  }
}
