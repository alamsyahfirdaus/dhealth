<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		$this->load->model('ObatModel', 'obat');
	}

	public function index()
	{
		$data = array(
			'menu'	=> 'Master',
			'title' => 'Obat',
		);

		$this->include->layout('index_obat', $data);
	}

	public function list_obat()
	{
		$bulider = $this->obat->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$obatalkes_kode 	= $field->obatalkes_kode ? $field->obatalkes_kode : '-';
			$obatalkes_nama 	= $field->obatalkes_nama ? $field->obatalkes_nama : '-';
			$stok 				= $field->stok ? $field->stok - $this->obat->findObatTerpakai($field->obatalkes_id) : 0;

            $aksi = '<div class="btn-group btn-group-sm">';
            $aksi .= '<a href="'. site_url('detail/obat/'. base64_encode($field->obatalkes_id)) .'" class="btn btn-primary"><i class="fas fa-eye"></i></a>';
            $aksi .= '<a href="'. site_url('edit/obat/'. base64_encode($field->obatalkes_id)) .'" class="btn btn-success"><i class="fas fa-edit"></i></a>';
            $aksi .= '<button type="button" onclick="delete_data('. "'". base64_encode($field->obatalkes_id) ."'" .')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
            $aksi .= '</div>';
			
			$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]  = '<div style="text-align: left;">'. $obatalkes_kode .'</div>';
			$row[]  = '<div style="text-align: left;">'. $obatalkes_nama .'</div>';
			$row[]  = '<div style="text-align: left;">'. intval($field->stok) .'</div>';
			$row[]  = '<div style="text-align: left;">'. $stok .'</div>';
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

	public function detail_obat($obatalkes_id = null)
	{
		$query = $this->obat->getRow(base64_decode($obatalkes_id));
		if (empty($query->obatalkes_id)) {
			show_404();
		}

		$data = array(
			'menu' 				=> 'Master',
			'title' 			=> 'Obat',
			'header'			=> 'Detail Obat',
			'row'				=> $query,
			'obat'				=> $this->obat->getDataTable()['dataTable'],
			'stok_tersedia'		=> $query->stok - $this->obat->findObatTerpakai($query->obatalkes_id),
			'stok_terpakai'		=> $this->obat->findObatTerpakai($query->obatalkes_id),
		);

		$this->include->layout('detail_obat', $data);
	}

	public function add_edit_obat($obatalkes_id = null)
	{
		$query = $this->obat->getRow(base64_decode($obatalkes_id));

		$header = isset($query->obatalkes_id) ? 'Ubah Obat' : 'Tambah Obat';

		$data = array(
			'menu' 		=> 'Master',
			'title' 	=> 'Obat',
			'header'	=> $header,
			'row'		=> $query,
		);

		if (isset($query->obatalkes_id)) {
			$data['stok_tersedia'] = $query->stok - $this->obat->findObatTerpakai($query->obatalkes_id);
			$data['stok_terpakai'] = $this->obat->findObatTerpakai($query->obatalkes_id);
		}

		$this->include->layout('add_edit_obat', $data);

	}

	public function save_obat()
	{
		$query = $this->obat->getRow($this->input->post('obatalkes_id'));

		if (isset($query->obatalkes_id)) {

			$terpakai = $this->obat->findObatTerpakai($query->obatalkes_id);

			$unique_kode   =  $query->obatalkes_kode != $this->input->post('obatalkes_kode') ? '|is_unique[obatalkes_m.obatalkes_kode]' : '';
			$required_stok =  $terpakai > 0 ? '|greater_than_equal_to['. $terpakai .']' : '';
		} else {
			$unique_kode   = '|is_unique[obatalkes_m.obatalkes_kode]';
			$required_stok = '|required';
		}

		$this->load->library('form_validation');

		$list_fields = array(
			'obatalkes_kode' 		=> ['Kode Obat' 	=> 'trim|required'. $unique_kode],
			'obatalkes_nama' 		=> ['Nama Obat'  	=> 'trim|required'],
			'stok'	 				=> ['Stok' 		 	=> 'trim|numeric'. $required_stok],
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
		$this->form_validation->set_message('greater_than_equal_to', '{field} harus berisi angka yang lebih besar dari atau sama dengan {param}.');

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
				if (isset($query->obatalkes_id) && $i != 2) {
					$data[$field_names[$i]] = $this->input->post($field_names[$i]) ? $this->input->post($field_names[$i]) : null;
				}
			}

			if ($this->input->post('stok')) {
				$data['stok'] = $this->input->post('stok');
			}

			if (count($data) >= 1) {
				if (isset($query->obatalkes_id)) {
					$data['last_modified_date']  = date('Y-m-d H:i:s');
					$data['last_modified_by']    = $this->session->id_pengguna;
					$this->db->update('obatalkes_m', $data, ['obatalkes_id' => $query->obatalkes_id]);
					$obatalkes_id = $query->obatalkes_id;
					$this->session->set_flashdata('success', 'Mengubah Obat Berhasil!');
				} else {
					$data['created_date'] = date('Y-m-d H:i:s');
					$data['created_by']   = $this->session->id_pengguna;
					$this->db->insert('obatalkes_m', $data);
					$obatalkes_id = $this->db->insert_id();
					$this->session->set_flashdata('success', 'Menambah Obat Berhasil!');
				}
			}

			$output = array(
				'status' 		=> true,
				'obatalkes_id'	=> base64_encode($obatalkes_id),
			);
		}

		echo json_encode($output);
	}

	public function delete_obat($obatalkes_id = null)
	{
		$query = $this->obat->getRow(base64_decode($obatalkes_id));
		if (empty($query->obatalkes_id)) {
			show_404();
		}
		$this->db->delete('obatalkes_m', ['obatalkes_id' => $query->obatalkes_id]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Obat Berhasil!'
		]);
	}


	
}

/* End of file Obat.php */
/* Location: ./application/controllers/Obat.php */
