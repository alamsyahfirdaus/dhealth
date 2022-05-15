<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		$this->load->model('SignaModel', 'signa');

	}

	public function index()
	{
		$data = array(
			'menu'	=> 'Master',
			'title' => 'Signa',
		);

		$this->include->layout('index_signa', $data);
	}

	public function list_signa()
	{
		$bulider = $this->signa->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$signa_kode 	= $field->signa_kode ? $field->signa_kode : '-';
			$signa_nama 	= $field->signa_nama ? $field->signa_nama : '-';

            $aksi = '<div class="btn-group btn-group-sm">';
            $aksi .= '<a href="'. site_url('detail/signa/'. base64_encode($field->signa_id)) .'" class="btn btn-primary"><i class="fas fa-eye"></i></a>';
            $aksi .= '<a href="'. site_url('edit/signa/'. base64_encode($field->signa_id)) .'" class="btn btn-success"><i class="fas fa-edit"></i></a>';
            $aksi .= '<button type="button" onclick="delete_data('. "'". base64_encode($field->signa_id) ."'" .')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
            $aksi .= '</div>';
			
			$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]  = '<div style="text-align: left;">'. $signa_kode .'</div>';
			$row[]  = '<div style="text-align: left;">'. $signa_nama .'</div>';
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

	public function detail_signa($signa_id = null)
	{
		$query = $this->signa->getRow(base64_decode($signa_id));
		if (empty($query->signa_id)) {
			show_404();
		}

		$data = array(
			'menu' 		=> 'Master',
			'title' 	=> 'Signa',
			'header'	=> 'Detail Signa',
			'row'		=> $query,
			'signa'		=> $this->signa->getDataTable()['dataTable'],
		);

		$this->include->layout('detail_signa', $data);
	}

	public function add_edit_signa($signa_id = null)
	{
		$query = $this->signa->getRow(base64_decode($signa_id));

		$header = isset($query->signa_id) ? 'Ubah Signa' : 'Tambah Signa';

		$data = array(
			'menu' 		=> 'Master',
			'title' 	=> 'Signa',
			'header'	=> $header,
			'row'		=> $query,
		);

		$this->include->layout('add_edit_signa', $data);

	}

	public function save_signa()
	{
		$query = $this->signa->getRow($this->input->post('signa_id'));

		if (isset($query->signa_id)) {
			$unique_kode =  $query->signa_kode != $this->input->post('signa_kode') ? '|is_unique[signa_m.signa_kode]' : '';
		} else {
			$unique_kode = '|is_unique[signa_m.signa_kode]';
		}

		$this->load->library('form_validation');

		$list_fields = array(
			'signa_kode' 			=> ['Kode Signa' 	=> 'trim|required'. $unique_kode],
			'signa_nama' 			=> ['Nama Signa'  	=> 'trim|required'],
			'additional_data' 		=> ['Keterangan' 	=> 'trim'],
			'is_active'	 			=> ['Aktif' 		=> 'trim|required|numeric'],
		);

		$this->form_validation->set_error_delimiters('', '');
		foreach ($list_fields as $key1 => $value1) {
			foreach ($value1 as $label => $rules) {
				$this->form_validation->set_rules($key1, $label, $rules);
			}
		}

		$this->form_validation->set_message('required', '{field} harus diisi.');
		$this->form_validation->set_message('numeric', '{field} hanya boleh berisi angka.');
		$this->form_validation->set_message('is_unique', '{field} sudah terdaftar.');

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

			$field_names = array_keys($list_fields);
			$data = array();

			for ($i=0; $i < count($field_names); $i++) { 
				$data[$field_names[$i]] = $this->input->post($field_names[$i]) ? $this->input->post($field_names[$i]) : null;
			}

			if (count($data) >= 1) {
				if (isset($query->signa_id)) {
					$data['last_modified_date']  = date('Y-m-d H:i:s');
					$data['last_modified_by']    = $this->session->id_pengguna;
					$this->db->update('signa_m', $data, ['signa_id' => $query->signa_id]);
					$signa_id = $query->signa_id;
					$this->session->set_flashdata('success', 'Mengubah Signa Berhasil!');
				} else {
					$data['created_date'] = date('Y-m-d H:i:s');
					$data['created_by']   = $this->session->id_pengguna;
					$this->db->insert('signa_m', $data);
					$signa_id = $this->db->insert_id();
					$this->session->set_flashdata('success', 'Menambah Signa Berhasil!');
				}
			}

			$output = array(
				'status' 		=> true,
				'signa_id'		=> base64_encode($signa_id),
			);
		}

		echo json_encode($output);
	}

	public function delete_signa($signa_id = null)
	{
		$query = $this->signa->getRow(base64_decode($signa_id));
		if (empty($query->signa_id)) {
			show_404();
		}
		$this->db->delete('signa_m', ['signa_id' => $query->signa_id]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Signa Berhasil!'
		]);
	}
}

/* End of file Signa.php */
/* Location: ./application/controllers/Signa.php */
