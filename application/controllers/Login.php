<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		logged_out();
		$this->load->view('layout/index_auth');
	}

	public function proses_login()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('valid_email', '{field} tidak valid.');

		if ($this->form_validation->run() == FALSE) {
			$output = array(
				'status'	=> false,
				'errors'	=> array(
					'email' 	=> form_error('email'), 
					'password' 	=> form_error('password'), 
				),
			);
		} else {

			$query = $this->db->get_where('pengguna', ['email' => $this->input->post('email')])->row();

			if (isset($query->id_pengguna)) {

				// Pengguna Sudah Terdaftar
				
				if ($query->password == sha1($this->input->post('password'))) {

					// Pengguna Sudah Terdaftar dan Password Benar
					
					$this->session->set_userdata([
						'id_pengguna' => $query->id_pengguna,
					]);
					
					$output = array(
						'status' => true,
					);

				} else {

					// Password Salah
					
					$output = array(
						'status' => false,
						'errors' => array(
							'password' => 'Password salah, coba lagi.', 
						) 
					);
				}

			} else {

				// Pengguna Belum Terdaftar
				
				$output = array(
					'status' => false,
					'errors' => array(
						'email' => 'Email belum terdaftar.', 
					) 
				);

			}
		}

		echo json_encode($output);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */
