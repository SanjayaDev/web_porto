<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Portofolio extends CR_Controller
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
    $this->load->model("Portofolio_model", "model");
  }

  public function view_portofolio_management()
  {
    $data = [
      "title" => "Portofolio Management",
      "menu" => "Portofolio",
      "content" => "admin/v_portofolio",
      "list_kategori" => $this->model->get_kategori_listed()
    ];
    $this->load->view("layout/wrapper", $data);
  }

  public function get_portofolio_listed()
  {
    $list = $this->model->get_portofolio_listed();
    $data = [];
    $no = $_POST["start"];
    foreach ($list as $item) {
      $no++;
      $row = [];
      $row[] = $no;
      $row[] = $item->portofolio_name;
      $row[] = $item->is_active == 1 ? "Aktif" : "Non Aktif";
      $button = "<button class='btn btn-info btn-sm m-1' onclick=\"getPortofolioDetail('" . encrypt_url($item->portofolio_id) . "', 'detail')\" data-toggle='modal' data-target='#detailPortofolio'><i class='fas fa-search fa-sm'></i></button>";
      $button .= "<button class='btn btn-primary btn-sm m-1' onclick=\"getPortofolioDetail('" . encrypt_url($item->portofolio_id) . "', 'edit')\" data-toggle='modal' data-target='#editPortofolio'><i class='fas fa-edit fa-sm'></i></button>";
      $button .= "<button class='btn btn-danger btn-sm m-1' onclick=\"promptDelete('" . base_url("delete_portofolio?id=" . encrypt_url($item->portofolio_id)) . "')\"><i class='fas fa-trash fa-sm'></i></button>";
      $row[] = $button;
      $data[] = $row;
    }

    $output = [
      "draw" => $_POST["draw"],
      "recordsTotal" => $this->model->count_portofolio_all(),
      "recordsFiltered" => $this->model->count_portofolio_filtered(),
      "data" => $data
    ];

    echo json_encode($output);
  }

  public function validate_portofolio_add()
  {
    // var_dump($this->input->post());
    $this->form_validation->set_rules('portofolio_name', 'Name', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('portofolio_description', 'Desription', 'required');
    $this->form_validation->set_rules('is_active', 'Active', 'required|numeric');
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
      $this->process_portofolio_add();
    }
  }

  private function process_portofolio_add()
  {
    $input = (object)html_escape($this->input->post());
    $description = $this->input->post("portofolio_description");
    $add_portofolio = $this->model->add_portofolio($input, $description);
    if ($add_portofolio->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $add_portofolio->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $add_portofolio->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    }
    echo json_encode($data);
  }

  public function get_portofolio_detail_json()
  {
    $id = decrypt_url(html_escape($this->input->get("id")));
    $get_portofolio = $this->model->get_portofolio_detail_json($id);
    echo json_encode($get_portofolio);
  }

  public function validate_portofolio_edit()
  {
    $this->form_validation->set_rules('portofolio_id', 'Id', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('portofolio_name', 'Name', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('portofolio_description', 'Desription', 'required');
    $this->form_validation->set_rules('is_active', 'Active', 'required|numeric');
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
      $this->process_portofolio_edit();
    }
  }

  private function process_portofolio_edit()
  {
    $input = (object)html_escape($this->input->post());
    $description = $this->input->post("portofolio_description");
    $edit_portofolio = $this->model->edit_portofolio($input, $description);
    if ($edit_portofolio->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $edit_portofolio->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $edit_portofolio->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    }
    echo json_encode($data);
  }

  public function process_portofolio_delete()
  {
    $id = html_escape(decrypt_url($this->input->get("id")));
    $delete_portofolio = $this->model->delete_portofolio($id);
    if ($delete_portofolio->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $delete_portofolio->message
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $delete_portofolio->message
      ];
    }
    echo json_encode($data);
  }
}
