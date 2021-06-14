<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Social extends CR_Controller {

  public function __construct()
  {
    parent::__construct();
    $protect_login = $this->Auth->protect_login();
    if ($protect_login->success === FALSE) {
      $this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$protect_login->message')</script>");
      redirect("login");
      exit;
    }
    $this->load->model("Social_model", "model");
  }

  public function view_social_management()
  {
    $data = [
      "title" => "Social Management",
      "menu" => "Social",
      "content" => "admin/v_social"
    ];
    $this->load->view("layout/wrapper", $data);
  }

  public function get_kontak_listed()
  {
    $list = $this->model->get_kontak_listed();
    $data = [];
    $no = $_POST["start"];
    foreach ($list as $item) {
      $no++;
      $row = [];
      $row[] = $no;
      $row[] = $item->kontak_name;
      $row[] = $item->kontak_value;
      $row[] = "<i class='$item->kontak_icon'></i> $item->kontak_icon";
      $row[] = $item->kontak_link;
      $button = "<button class='btn btn-primary btn-sm m-1' onclick=\"getKontakDetail('" . encrypt_url($item->kontak_id) . "')\" data-toggle='modal' data-target='#editKontak'><i class='fas fa-edit fa-sm'></i></button>";
      $button .= "<button class='btn btn-danger btn-sm m-1' onclick=\"promptDelete('" . base_url("delete_kontak?id=" . encrypt_url($item->kontak_id)) . "')\"><i class='fas fa-trash fa-sm'></i></button>";
      $row[] = $button;
      $data[] = $row;
    }

    $output = [
      "draw" => $_POST["draw"],
      "recordsTotal" => $this->model->count_kontak_all(),
      "recordsFiltered" => $this->model->count_kontak_filtered(),
      "data" => $data
    ];

    echo json_encode($output);
  }

  public function validate_kontak_add()
  {
    $this->form_validation->set_rules('kontak_name', 'Name', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('kontak_value', 'Value', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('kontak_icon', 'Icon', 'required');
    $this->form_validation->set_rules('kontak_link', 'Link', 'required|valid_url');

    if ($this->form_validation->run() == FALSE) {
      // var_dump(validation_errors());
      $data = [
        "success" => 201,
        "message" => "Data input tidak lengkap!",
        "csrf" => $this->security->get_csrf_hash(),
        "error" => validation_errors()
      ];
      echo json_encode($data);
    } else {
      $this->process_kontak_add();
    }
  }

  private function process_kontak_add()
  {
    $input = (object)html_escape($this->input->post());
    $add_kontak = $this->model->add_kontak($input);
    if ($add_kontak->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $add_kontak->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $add_kontak->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    }
    echo json_encode($data);
  }

  public function get_kontak_detail_json()
  {
    $id = decrypt_url(html_escape($this->input->get("id")));
    $get_kontak = $this->model->get_kontak_detail_json($id);
    echo json_encode($get_kontak);
  }

  public function validate_kontak_edit()
  {
    $this->form_validation->set_rules('kontak_id', 'Id', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('kontak_name', 'Name', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('kontak_value', 'Value', 'required|alpha_numeric_spaces');
    $this->form_validation->set_rules('kontak_icon', 'Icon', 'required');
    $this->form_validation->set_rules('kontak_link', 'Link', 'required|valid_url');

    if ($this->form_validation->run() == FALSE) {
      // var_dump(validation_errors());
      $data = [
        "success" => 201,
        "message" => "Data input tidak lengkap!",
        "csrf" => $this->security->get_csrf_hash(),
        "error" => validation_errors()
      ];
      echo json_encode($data);
    } else {
      $this->process_kontak_edit();
    }
  }

  private function process_kontak_edit()
  {
    $input = (object)html_escape($this->input->post());
    $edit_kontak = $this->model->edit_kontak($input);
    if ($edit_kontak->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $edit_kontak->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $edit_kontak->message,
        "csrf" => $this->security->get_csrf_hash()
      ];
    }
    echo json_encode($data);
  }

  public function process_kontak_delete()
  {
    $id = html_escape(decrypt_url($this->input->get("id")));
    $delete_kontak = $this->model->delete_kontak($id);
    if ($delete_kontak->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $delete_kontak->message
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $delete_kontak->message
      ];
    }
    echo json_encode($data);
  }

  public function get_inquiry_listed()
  {
    $list = $this->model->get_inquiry_listed();
    $data = [];
    $no = $_POST["start"];
    foreach ($list as $item) {
      $no++;
      $row = [];
      $row[] = $no;
      $row[] = $item->inquiry_name;
      $row[] = $item->inquiry_email;
      $row[] = $item->inquiry_message;
      $row[] = $item->is_response == 0 ? "Belum di response" : "Sudah di response";
      $row[] = $item->is_response == 0 ? "<button class='btn btn-success btn-sm' onclick=\"responseInquiry('".encrypt_url($item->inquiry_id)."')\">Response</button>" : "";
      $data[] = $row;
    }

    $output = [
      "draw" => $_POST["draw"],
      "recordsTotal" => $this->model->count_inquiry_all(),
      "recordsFiltered" => $this->model->count_inquiry_filtered(),
      "data" => $data
    ];

    echo json_encode($output);
  }

  public function process_inquiry_response()
  {
    $id = decrypt_url(html_escape($this->input->get("id")));
    $response = $this->model->response_inquiry($id);
    if ($response->success === TRUE) {
      $data = [
        "success" => 200,
        "message" => $response->message
      ];
    } else {
      $data = [
        "success" => 201,
        "message" => $response->message
      ];
    }
    echo json_encode($data);
  }
}