<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();
	}

	public function index()
	{
		$obatalkes_m = $this->db->where('is_active', '1')->get('obatalkes_m')->num_rows();
		$signa_m 	 = $this->db->where('is_active', '1')->get('signa_m')->num_rows();

		$list_info = array(
			'Obat' 		=> ['<i class="fas fa-pills" style="color: white;"></i>' => $obatalkes_m], 
			'Signa' 	=> ['<i class="fas fa-book-medical" style="color: white;"></i>' => $signa_m],
		);

		$data = array(
			'title' 		=> 'Beranda',
			'list_info'		=> $list_info,
		);

		$this->include->layout('index_home', $data);
	}

	public function settings()
	{
		$data = array(
			'title' 		=> 'Settings',
			'pengguna'		=> $this->db->get_where('pengguna', ['id_pengguna' => $this->session->id_pengguna])->row(),
		);

		$this->include->layout('index_settings', $data);
	}

	public function list_pengguna()
	{
		$this->load->model('PenggunaModel', 'pengguna');
		$bulider = $this->pengguna->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$foto_profile = $field->foto_profile ? 'assets/img/' . $field->foto_profile : 'assets/dist/img/default-150x150.png';

			$foto = '<img class="img-fluid" src="'. base_url($foto_profile) .'" alt="" style="max-width: 100px; height: 100px;">';

            $aksi = '<div class="btn-group btn-group-sm">';
            $aksi .= '<button type="button" onclick="ubah_foto('. "'". base64_encode($field->id_pengguna) ."'" .')" class="btn btn-primary"><i class="fas fa-image"></i></button>';
            $aksi .= '<button type="button" onclick="edit_pengguna('. $field->id_pengguna .')" class="btn btn-success"><i class="fas fa-edit"></i></button>';
            $aksi .= '<button type="button" class="btn btn-danger" onclick="delete_pengguna('. "'". base64_encode($field->id_pengguna) ."'" .')"><i class="fas fa-trash"></i></button>';
            $aksi .= '</div>';
            $aksi .= '<input type="hidden" name="nama_pengguna_'. $field->id_pengguna .'" value="'. $field->nama_pengguna .'">';
            $aksi .= '<input type="hidden" name="email_'. $field->id_pengguna .'" value="'. $field->email .'">';
			
			$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]  = '<div style="text-align: left;">'. $field->nama_pengguna .'</div>';
			$row[]  = '<div style="text-align: left;">'. $field->email .'</div>';
			$row[]  = '<div style="text-align: center;">'. $foto .'</div>';
			$row[]  = '<div style="text-align: center;">'. $aksi .'</div>';

			$data[]	= $row;
		}

		$output = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> $bulider['recordsTotal'],
			'recordsFiltered' 	=> $bulider['recordsFiltered'],
			'data' 				=> $data,
		);

		echo json_encode($output);
	}

	public function update_foto_profile($id_pengguna = null)
	{
		$query 	= $this->db->get_where('pengguna', ['id_pengguna' => base64_decode($id_pengguna)])->row();

		if (empty($query->id_pengguna)) {
			show_404();
		}

		$this->_do_upload();

		if ($this->upload->do_upload('foto_profile')) {
		    if ($query->foto_profile) {
		        unlink('./assets/img/' . $query->foto_profile);
		    }

		    $this->db->update('pengguna', ['foto_profile' => $this->upload->data('file_name')], ['id_pengguna' => $query->id_pengguna]);
		    
		}

		if ($this->input->is_ajax_request()) {
			$output = array(
				'status' 	=> true,
				'message'	=> 'Mengubah Foto Berhasil!',
			);

			echo json_encode($output);
		} else {
			$this->session->set_flashdata('success', 'Mengubah Foto Berhasil!');
			redirect($this->agent->referrer());
		}

	}

	private function _do_upload()
	{
        $config['upload_path']   = './assets/img/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|GIF|JPG|PNG|JPEG|BMP|';
        $config['max_size']    	 = 10000;
        $config['max_width']   	 = 10000;
        $config['max_height']  	 = 10000;
        $config['file_name']     = time();
        $this->upload->initialize($config);
	}

	public function delete_foto_profile($id_pengguna = null)
	{
		$query 	= $this->db->get_where('pengguna', ['id_pengguna' => base64_decode($id_pengguna)])->row();

		if (empty($query->id_pengguna)) {
			show_404();
		}

		if ($query->foto_profile) {
		    unlink('./assets/img/' . $query->foto_profile);
		}

		$this->db->update('pengguna', ['foto_profile' => null], ['id_pengguna' => $query->id_pengguna]);

		if ($this->input->is_ajax_request()) {
			$output = array(
				'status' 	=> true,
				'message'	=> 'Menghapus Foto Berhasil!',
			);

			echo json_encode($output);
		} else {
			$this->session->set_flashdata('success', 'Menghapus Foto Berhasil!');
			redirect($this->agent->referrer());
		}

	}

	public function update_password_pengguna($id_pengguna = null)
	{
		$query = $this->db->get_where('pengguna', ['id_pengguna' => base64_decode($id_pengguna)])->row();

		$this->load->library('form_validation');

		$list_fields = array(
			'password1' 		=> ['Password Sekarang' 	=> 'trim|required'],
			'password2' 		=> ['Password Baru' 		=> 'trim|required|min_length[6]'],
			'password3' 		=> ['Konfirmasi Password' 	=> 'trim|required|matches[password2]'],
		);

		$this->form_validation->set_error_delimiters('', '');
		foreach ($list_fields as $key1 => $value1) {
			foreach ($value1 as $label => $rules) {
				$this->form_validation->set_rules($key1, $label, $rules);
			}
		}

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');
		$this->form_validation->set_message('matches', '{field} tidak sama dengan {param}.');

		if ($this->form_validation->run() == FALSE) {

			$list_errors = array();
			foreach ($list_fields as $key => $value) {
				$list_errors[$key] = form_error($key);
			}

			$output = array(
				'status'	=> false,
				'errors'	=> $list_errors,
			);

		} else {

			if (isset($query->id_pengguna)) {
				if ($query->password == sha1($this->input->post('password1'))) {
					$this->db->update('pengguna', ['password' => sha1($this->input->post('password2'))], ['id_pengguna' => $query->id_pengguna]);
					$output = array(
						'status' 	=> true,
						'type'		=> 'success',
						'message'	=> 'Mengubah Password Berhasil!',
					);
				} else {
					$output = array(
						'status' 	=> false,
						'errors'	=> ['password1' => 'Password Sekarang salah, coba lagi.']
					);
				}
			} else {
				$output = array(
					'status' 	=> true,
					'type'		=> 'danger',
					'message'	=> 'Mengubah Password Gagal!',
				);
			}

		}

		echo json_encode($output);
	}

	public function save_pengguna()
	{
		$query = $this->db->get_where('pengguna', ['id_pengguna' => $this->input->post('id_pengguna')])->row();

		if (isset($query->id_pengguna)) {
			$unique_email =  $query->email != $this->input->post('email') ? '|is_unique[pengguna.email]' : '';
			$required_password =  '';
		} else {
			$unique_email = '|is_unique[pengguna.email]';
			$required_password =  '|required';
		}

		$this->load->library('form_validation');

		$list_fields = array(
			'nama_pengguna' 	=> ['Nama pengguna' 	=> 'trim|required'],
			'email' 		=> ['Email' 		=> 'trim|required|valid_email'. $unique_email],
			'password' 		=> ['Password' 		=> 'trim|min_length[6]'. $required_password],
		);

		$this->form_validation->set_error_delimiters('', '');
		foreach ($list_fields as $key1 => $value1) {
			foreach ($value1 as $label => $rules) {
				$this->form_validation->set_rules($key1, $label, $rules);
			}
		}

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar.');
		$this->form_validation->set_message('valid_email', '{field} tidak valid.');
		$this->form_validation->set_message('min_length', '{field} minimal {param} karakter.');

		if ($this->form_validation->run() == FALSE) {

			$list_errors = array();
			foreach ($list_fields as $key => $value) {
				$list_errors[$key] = form_error($key);
			}

			$output = array(
				'status'	=> false,
				'errors'	=> $list_errors,
			);

		} else {

			$data = array(
				'nama_pengguna' 	=> ucwords(strtolower($this->input->post('nama_pengguna'))), 
				'email' 		=> $this->input->post('email'),
			);

			if ($this->input->post('password')) {
				$data['password'] = sha1($this->input->post('password'));
			}

			if (isset($query->id_pengguna)) {
				$this->db->update('pengguna', $data, ['id_pengguna' => $query->id_pengguna]);
				$id_pengguna = $query->id_pengguna;
				$message = 'Mengubah Pengguna Berhasil!';
			} else {
				$this->db->insert('pengguna', $data);
				$id_pengguna = $this->db->insert_id();
				$message = 'Menambah Pengguna Berhasil!';
			}

			if ($id_pengguna == $this->session->id_pengguna) {
				$this->session->set_flashdata('success', 'Mengubah Profile Berhasil!');
				$output = array(
					'status' 	 => true,
					'id_pengguna' => $id_pengguna
				);
			} else {			
				$output = array(
					'status' 	=> true,
					'message'	=> $message,
				);
			}

		}

		echo json_encode($output);
	}

	public function delete_pengguna($id_pengguna = null)
	{
		$query = $this->db->get_where('pengguna', ['id_pengguna' => base64_decode($id_pengguna)])->row();
		if (empty($query->id_pengguna)) {
			show_404();
		}
		if ($query->foto_profile) {
		    unlink('./assets/img/' . $query->foto_profile);
		}
		$this->db->delete('pengguna', ['id_pengguna' => $query->id_pengguna]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Pengguna Berhasil!'
		]);
	}


}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
