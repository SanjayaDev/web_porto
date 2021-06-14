<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CR_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Front_model", "Front");
	}
	
	public function index()
	{
		$data = [
			"title" => "Under Construction",
			"aboutme" => $this->Front->get_aboutme(),
			"anticache" => $this->generate_anti_cache(),
			"list_skill_kategori" => $this->Front->get_skill_kategori_listed(),
			"list_skill" => $this->Front->get_skill_listed(),
			"list_portofolio" => $this->Front->get_portofolio_listed(),
			"list_social" => $this->Front->get_social_listed()
		];
		$this->load->view('public_index', $data);
	}

	public function validate_inquiry_add()
	{
		$this->form_validation->set_rules('inquiry_name', 'Name', 'required|alpha_numeric_spaces');
		$this->form_validation->set_rules('inquiry_email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('inquiry_message', 'Message', 'required|alpha_numeric_spaces');
		
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', 'Data yang anda input tidak lengkap/valid!')</script>");
			redirect(base_url());
			// var_dump(validation_errors());
		} else {
			$this->process_inquiry_add();
		}
	}

	private function process_inquiry_add()
	{
		$input = (object)html_escape($this->input->post());
		$add_inquiry = $this->Front->add_inquiry($input);
		if ($add_inquiry->success === TRUE) {
			$this->session->set_flashdata("pesan", "<script>sweet('success', 'Sukses!', '$add_inquiry->message!')</script>");
		} else {
			$this->session->set_flashdata("pesan", "<script>sweet('error', 'Gagal!', '$add_inquiry->message!')</script>");
		}
		redirect(base_url());
	}

	public function view_404_page()
	{
		$this->load->view("v_404");
	}
}
