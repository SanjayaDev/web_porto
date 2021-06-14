<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Skill extends CR_Controller
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
    $this->load->model("Skill_model", "model");
  }

  public function view_skill_management()
  {
    $data = [
      "title" => "Skill Management",
      "menu" => "Skill",
      "content" => "admin/v_skill",
      "list_kategori" => $this->model->get_skill_kategori_listed()
    ];
    $this->load->view("layout/wrapper", $data);
  }

  public function get_skill_listed()
  {
    $list = $this->model->get_skill_listed();
    $data = [];
    $no = $_POST["start"];
    foreach ($list as $item) {
      $no++;
      $row = [];
      $row[] = $no;
      $row[] = $item->skill_name;
      $row[] = $item->kategori;
      $button = "<button class='btn btn-primary btn-sm m-1' onclick=\"getSkillDetail('" . encrypt_url($item->skill_id) . "')\" data-toggle='modal' data-target='#editSkill'><i class='fas fa-edit fa-sm'></i></button>";
      $button .= "<button class='btn btn-danger btn-sm m-1' onclick=\"promptDelete('" . base_url("delete_skill?id=" . encrypt_url($item->skill_id)) . "')\"><i class='fas fa-trash fa-sm'></i></button>";
      $row[] = $button;
      $data[] = $row;
    }

    $output = [
      "draw" => $_POST["draw"],
      "recordsTotal" => $this->model->count_skill_all(),
      "recordsFiltered" => $this->model->count_skill_filtered(),
      "data" => $data
    ];

    echo json_encode($output);
  }

  public function validate_skill_add()
  {
    // var_dump($this->input->post());
    $this->form_validation->set_rules('skill_name', 'Name', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $data = [
        "success" => 201,
        "message" => "Data input tidak lengkap!",
        "csrf" => $this->security->get_csrf_hash(),
        "error" => validation_errors()
      ];
      echo json_encode($data);
    } else {
      $this->process_skill_add();
    }
  }

  private function process_skill_add()
  {
    $input = (object)html_escape($this->input->post());
    $add_skill = $this->model->add_skill($input);
    if ($add_skill->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $add_skill->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $add_skill->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    }
    echo json_encode($data);
  }

  public function get_skill_detail_json()
  {
    $id = html_escape(decrypt_url($this->input->get("id")));
    $get_skill = $this->model->get_skill_detail_json($id);
    echo json_encode($get_skill);
  }

  public function validation_skill_edit()
  {
    $this->form_validation->set_rules('skill_id', 'id', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('skill_name', 'Name', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');

    if ($this->form_validation->run() == FALSE) {
      $data = [
        "success" => 201,
        "message" => "Data input tidak lengkap!",
        "csrf" => $this->security->get_csrf_hash(),
        "error" => validation_errors()
      ];
      echo json_encode($data);
    } else {
      $this->process_skill_edit();
    }
  }

  private function process_skill_edit()
  {
    $input = (object)html_escape($this->input->post());
    $edit_skill = $this->model->edit_skill($input);
    if ($edit_skill->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $edit_skill->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $edit_skill->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    }
    echo json_encode($data);
  }

  public function process_skill_delete()
  {
    $id = html_escape(decrypt_url($this->input->get("id")));
    $delete_skill = $this->model->delete_skill($id);
    if ($delete_skill->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $delete_skill->message
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $delete_skill->message
      ];
    }
    echo json_encode($data);
  }
}
