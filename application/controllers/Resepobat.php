<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resepobat extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		logged_in();

		$this->load->model('ResepObatModel', 'resepobat');
	}

	public function nonracikan()
	{
		$data = array(
			'menu'	=> 'Resep Obat',
			'title' => 'Non Racikan',
			'obat'	=> $this->_list_obat()['select'],
			'signa'	=> $this->_list_signa(),
		);

		$this->include->layout('index_nonracikan', $data);
	}

	public function list_nonracikan()
	{
		$bulider = $this->resepobat->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$kode 			= $field->kode ? $field->kode : '-';
			$obat 			= $field->id_obatalkes ? $field->obatalkes_kode .' - '. $field->obatalkes_nama : '-';
			$signa 			= $field->id_signa ? $field->signa_kode .' - '. $field->signa_nama : '-';
			$qty 			= $field->qty ? $field->qty : '0';
			$keterangan 	= $field->keterangan ? $field->keterangan : '-';

            $aksi = '<div class="btn-group btn-group-sm">';
            $aksi .= '<a href="'. site_url('print/prescription/'. base64_encode($field->id_resep_obat)) .'" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i></a>';
            $aksi .= '<a href="'. site_url('edit/nonracikan/'. base64_encode($field->id_resep_obat)) .'" class="btn btn-success"><i class="fas fa-edit"></i></a>';
            $aksi .= '<button type="button" onclick="delete_data('. "'". base64_encode($field->id_resep_obat) ."'" .')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
            $aksi .= '</div>';
			
			$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]  = '<div style="text-align: left;">'. $kode .'</div>';
			$row[]  = '<div style="text-align: left;">'. $obat .'</div>';
			$row[]  = '<div style="text-align: left;">'. $qty .'</div>';
			$row[]  = '<div style="text-align: left;">'. $signa .'</div>';
			$row[]  = '<div style="text-align: left;">'. $keterangan .'</div>';
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

	private function _list_obat($obatalkes_id = null)
	{
		$this->load->model('ObatModel', 'obat');

		$getResult = $this->obat->getDataTable()['dataTable'];

		$select = '<option value="">-- Obat --</option> ';
		foreach ($getResult as $row) {
			$disabled = intval($row->stok) == $this->obat->findObatTerpakai($row->obatalkes_id) ? 'disabled' : '';
			$terpakai = intval($row->stok) == $this->obat->findObatTerpakai($row->obatalkes_id) ? '(STOK HABIS)' : '';
			$selected = $row->obatalkes_id == $obatalkes_id ? 'selected' : '';
			$select .= '<option value="'. $row->obatalkes_id .'" '. $disabled .' '. $selected .'>'. $row->obatalkes_kode .' - '. $row->obatalkes_nama .' '. $terpakai .'</option>';
		}

		return array(
			'select' 	=> $select,
			'result'	=> $getResult,
			'row' 		=> $this->obat->getRow($obatalkes_id),
			'bulider'	=> $this->obat->getDataTable(),
			'stok'		=> $this->obat->findObatTerpakai($obatalkes_id),
		);
	}

	public function add_edit_nonracikan($id_resep_obat = null)
	{
		$query = $this->resepobat->getRow(base64_decode($id_resep_obat));

		$header = isset($query->id_resep_obat) ? 'Ubah Non Racikan' : 'Tambah Non Racikan';

		$data = array(
			'menu' 		=> 'Resep Obat',
			'title' 	=> 'Non Racikan',
			'header'	=> $header,
			'obat'		=> $this->_list_obat(base64_decode($id_resep_obat))['select'],
			'signa'		=> $this->_list_signa(),
			'row'		=> $query,
		);

		if (isset($query->id_resep_obat)) {
			$stok 			= $this->_list_obat($query->id_obatalkes)['row'];
			$data['stok'] 	= isset($stok->obatalkes_id) ? intval($stok->stok) : 0;
		}


		$this->include->layout('add_edit_nonracikan', $data);

	}

	private function _list_signa()
	{
		$this->load->model('SignaModel', 'signa');
		$query = $this->signa->getDataTable()['dataTable'];
		return $query;
	}

	public function list_qty($obatalkes_id = null)
	{
		$query  = $this->_list_obat($obatalkes_id)['row'];
		if (isset($query->obatalkes_id)) {
			$qty = $query->stok - $this->_list_obat($query->obatalkes_id)['stok'];
		} else {
			$qty = 0;
		}
		echo json_encode($qty);
	}

	public function racikan()
	{
		$data = array(
			'menu'	=> 'Resep Obat',
			'title' => 'Racikan',
		);

		$this->include->layout('index_racikan', $data);
	}

	public function list_racikan()
	{
		$bulider = $this->resepobat->getDataTable();
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();
			
			$kode 	 = $field->kode ? $field->kode : '-';
			$racikan = $this->resepobat->findRacikan($field->id_resep_obat);

			if (count($racikan) > 0) {

				$obat = '<ul class="list-group list-group-unbordered">';
				$qty  = '<ul class="list-group list-group-unbordered">';
				foreach ($racikan as $r) {
	                $obat 	.= '<li class="list-group-item" style="">';
	                $obat 	.= '<a>'. $r->obatalkes_kode .' - '. $r->obatalkes_nama .'</a>';
	                $obat 	.= '</li>';
	                $qty 	.= '<li class="list-group-item" style="">';
	                $qty 	.= '<a>'. $r->qty .'</a>';
	                $qty 	.= '</li>';
				}
	            $obat 	.= '</ul>';
	            $qty 	.= '</ul>';

			} else {
				$obat = $field->id_obatalkes ? $field->obatalkes_kode .' - '. $field->obatalkes_nama : '-';
				$qty  = $field->qty ? $field->qty : '-';
			}


            $signa 			= $field->id_signa ? $field->signa_kode .' - '. $field->signa_nama : '-';
            $keterangan 	= $field->keterangan ? $field->keterangan : '-';

            $aksi = '<div class="btn-group btn-group-sm">';
            $aksi .= '<a href="'. site_url('print/prescription/'. base64_encode($field->id_resep_obat)) .'" class="btn btn-primary" target="_blank"><i class="fas fa-print"></i></a>';
            $aksi .= '<a href="'. site_url('edit/racikan/'. base64_encode($field->id_resep_obat)) .'" class="btn btn-success"><i class="fas fa-edit"></i></a>';
            $aksi .= '<button type="button" onclick="delete_data('. "'". base64_encode($field->id_resep_obat) ."'" .')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
            $aksi .= '</div>';
			
			$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]  = '<div style="text-align: left;">'. $kode .'</div>';
			$row[]  = '<div style="text-align: left;">'. $obat .'</div>';
			$row[]  = '<div style="text-align: left;">'. $qty .'</div>';
			$row[]  = '<div style="text-align: left;">'. $signa .'</div>';
			$row[]  = '<div style="text-align: left;">'. $keterangan .'</div>';
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

	public function add_edit_racikan($id_resep_obat = null)
	{
		$query = $this->resepobat->getRow(base64_decode($id_resep_obat));

		if (isset($query->id_resep_obat)) {
			if ($query->id_obatalkes) {
				redirect('edit/nonracikan/' . $id_resep_obat);
			}
		}

		$header = isset($query->id_resep_obat) ? 'Ubah Racikan' : 'Tambah Racikan';

		$data = array(
			'menu' 		=> 'Resep Obat',
			'title' 	=> 'Racikan',
			'header'	=> $header,
			'signa'		=> $this->_list_signa(),
			'row'		=> $query,
		);

		$this->include->layout('add_edit_racikan', $data);

	}

	public function save_resepobat()
	{
		$query = $this->resepobat->getRow($this->input->post('id_resep_obat'));

		if (isset($query->id_resep_obat)) {
			$unique_kode =  $query->kode != $this->input->post('kode') ? '|is_unique[resep_obat.kode]' : '';
		} else {
			$unique_kode = '|is_unique[resep_obat.kode]';
		}

		$this->load->library('form_validation');

		$list_fields = array(
			'kode' 				=> ['Kode' 		 => 'trim|required'. $unique_kode],
			'id_signa' 			=> ['Signa' 	 => 'trim|required|numeric'],
			'keterangan' 		=> ['Keterangan' => 'trim'],
			'id_obatalkes'	 	=> ['Obat' 		 => 'trim|required|numeric'],
			'qty'	 			=> ['Qty' 		 => 'trim|required|numeric'],
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

			if ($this->input->post('arr_id_obatalkes') && $this->input->post('arr_qty')) {
				for ($i=0; $i < 3; $i++) { 
					$data[$field_names[$i]] = $this->input->post($field_names[$i]) ? $this->input->post($field_names[$i]) : null;
				}
			} else {
				for ($i=0; $i < count($field_names); $i++) { 
					$data[$field_names[$i]] = $this->input->post($field_names[$i]) ? $this->input->post($field_names[$i]) : null;
				}
			}


			if (count($data) >= 1) {
				if (isset($query->id_resep_obat)) {
					$this->db->update('resep_obat', $data, ['id_resep_obat' => $query->id_resep_obat]);
					$this->session->set_flashdata('success', 'Mengubah Resep Obat Berhasil!');
				} else {
					$this->db->insert('resep_obat', $data);
					$id_resep_obat = $this->db->insert_id();
					$this->session->set_flashdata('success', 'Menambah Resep Obat Berhasil!');
					if (is_array($this->input->post('arr_id_obatalkes')) && is_array($this->input->post('arr_qty'))) {
						if (count($this->input->post('arr_id_obatalkes')) > 0 && count($this->input->post('arr_qty')) > 0) {
							foreach (array_combine($this->input->post('arr_id_obatalkes'), $this->input->post('arr_qty')) as $key => $value) {
								$this->db->insert('resep_obat', [
									'id_obatalkes'  => $key,
									'id_racikan'	=> $id_resep_obat,
									'qty'			=> $value,
								]);
							}
						}
					}
				}
			}

			$output = array('status' => true);
		}

		echo json_encode($output);
	}

	public function delete_resepobat($id_resep_obat = null)
	{
		$query = $this->resepobat->getRow(base64_decode($id_resep_obat));
		if (empty($query->id_resep_obat)) {
			show_404();
		}
		$this->db->delete('resep_obat', ['id_resep_obat' => $query->id_resep_obat]);
		echo json_encode([
			'status'	=> true,
			'message'	=> 'Menghapus Resep Obat Berhasil!'
		]);
	}

	public function list_racikanobat()
	{
		# Add / Edit Resep Obat Racikan

		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;

		if ($this->input->post('id_racikan')) {

			$bulider = $this->resepobat->getDataTable();
			foreach ($bulider['dataTable'] as $field) {
				$start++;
				$row 	= array();

				$obatalkes_kode = $field->obatalkes_kode ? $field->obatalkes_kode : '#';

				$obat = $field->obatalkes_nama ? $obatalkes_kode .' - '. $field->obatalkes_nama : '-';

	            $aksi = '<div class="btn-group btn-group-sm">';
	            $aksi .= '<button type="button" onclick="delete_racikan('. "'". base64_encode($field->id_resep_obat) ."'" .')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
	            $aksi .= '</div>';
	            $aksi .= '<input type="hidden" name="arr_id_obatalkes[]" value="'. $field->id_resep_obat .'">';
	            $aksi .= '<input type="hidden" name="arr_qty[]" value="'. $field->qty .'">';
				
				$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
				$row[]  = '<div style="text-align: left;">'. $obat .'</div>';
				$row[]  = '<div style="text-align: left;">'. $field->qty .'</div>';
				$row[]  = '<div style="text-align: center;">'. $aksi .'</div>';

				$data[]	= $row;
			}

		} else {

			if ($this->input->post('obatalkes_id') && is_array($this->input->post('obatalkes_id'))) {

				$bulider = $this->_list_obat()['bulider'];

				$qty  = array();
				for ($i=0; $i < count($this->input->post('obatalkes_id')); $i++) { 
					$qty[$this->input->post('obatalkes_id')[$i]['id']] = $this->input->post('obatalkes_id')[$i]['qty'];
				}
				foreach ($bulider['dataTable'] as $field) {
					$row 	= array();

					$obatalkes_kode = $field->obatalkes_kode ? $field->obatalkes_kode : '#';

					$obat = $field->obatalkes_nama ? $obatalkes_kode .' - '. $field->obatalkes_nama : '-';


					$aksi = '<div class="btn-group btn-group-sm">';
					$aksi .= '<button type="button" onclick="delete_racikan('. $field->obatalkes_id .')" class="btn btn-danger"><i class="fas fa-trash"></i></button>';
					$aksi .= '</div>';
					$aksi .= '<input type="hidden" name="arr_id_obatalkes[]" value="'. $field->obatalkes_id .'">';
					$aksi .= '<input type="hidden" name="arr_qty[]" value="'. $qty[$field->obatalkes_id] .'">';

					$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
					$row[]  = '<div style="text-align: left;">'. $obat .'</div>';
					$row[]  = '<div style="text-align: left;">'. $qty[$field->obatalkes_id] .'</div>';
					$row[]  = '<div style="text-align: center;">'. $aksi .'</div>';

					$data[]	= $row;
				}
			}

		}


		$output = array(
			'draw' 				=> $this->input->post('draw'),
			'recordsTotal'		=> isset($bulider['recordsTotal']) ? $bulider['recordsTotal'] : 0,
			'recordsFiltered' 	=> isset($bulider['recordsFiltered']) ? $bulider['recordsFiltered'] : 0,
			'data' 				=> $data,
		);

		echo json_encode($output);
	}

	public function list_addobat()
	{
		$bulider = $this->_list_obat()['bulider'];
		$data 	 = array();
		$start 	 = $this->input->post('start');
		$no  	 = $start > 0 ? $start + 1 : 1;
		foreach ($bulider['dataTable'] as $field) {
			$start++;
			$row 	= array();

			$obatalkes_kode 	= $field->obatalkes_kode ? $field->obatalkes_kode : '-';
			$obatalkes_nama 	= $field->obatalkes_nama ? $field->obatalkes_nama : '-';
			$stok 				= $field->stok ? intval($field->stok) : 0;
			$tersedia 			= $stok - $this->_list_obat($field->obatalkes_id)['stok'];


            $aksi = '<div class="btn-group btn-group-sm">';
            $aksi .= '<button type="button" onclick="add_racikan('. $field->obatalkes_id .')" class="btn btn-success"><i class="fas fa-plus"></i></button>';
            $aksi .= '</div>';
			
			$qty = '<div class="form-group">';
			$qty .= '<select name="qty_'. $field->obatalkes_id .'" id="qty_'. $field->obatalkes_id .'" class="form-control qty" style="width: 100%;">';
			$qty .= '<option value="">-- Qty --</option>';
			for ($i=1; $i <= $tersedia; $i++) { 
				$qty .= '<option value="'. $i .'">'. $i .'</option>';
			}
			$qty .= '</select>';
			$qty .= '<span id="error-qty_'. $field->obatalkes_id .'" class="error invalid-feedback"></span>';
			$qty .= '</div>';
			$qty .= '<script>$("#qty_'. $field->obatalkes_id .'").select2();</script>';

			$row[]  = '<div style="text-align: center;">'. $no++ .'</div>';
			$row[]  = '<div style="text-align: left;">'. $obatalkes_kode .'</div>';
			$row[]  = '<div style="text-align: left;">'. $obatalkes_nama .'</div>';
			$row[]  = '<div style="text-align: left;">'. $tersedia .'</div>';
			$row[]  = '<div style="text-align: left;">'. $qty .'</div>';
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

	public function edit_qty()
	{
		$list_fields = ['id_obatalkes', 'id_racikan', 'qty'];
		$this->load->library('form_validation');
		for ($i=0; $i < count($list_fields); $i++) { 
			$this->form_validation->set_rules($list_fields[$i], '', 'trim|required|numeric');
		}
		if ($this->form_validation->run() == TRUE) {
			$query = $this->db->where([
				'id_obatalkes' 	=> $this->input->post('id_obatalkes'),
				'id_racikan' 	=> $this->input->post('id_racikan'),
			])->get('resep_obat')->row();
			if (isset($query->id_resep_obat)) {
				$qty = $query->qty + $this->input->post('qty');
				$this->db->update('resep_obat', ['qty' => $qty], ['id_resep_obat' => $query->id_resep_obat]);
			} else {
				$data = array();
				for ($i=0; $i < count($list_fields); $i++) { 
					$data[$list_fields[$i]] = $this->input->post($list_fields[$i]) ? $this->input->post($list_fields[$i]) : null;
				}
				if (count($data) > 0) {
					$this->db->insert('resep_obat', $data);
				}
			}
			echo json_encode(['status' => true]);
		} else {
			echo json_encode(['status' => false]);
		}
	}

	public function print_resepobat($id_resep_obat = null)
	{
		$query = $this->resepobat->getRow(base64_decode($id_resep_obat));
		if (empty($query->id_resep_obat)) {
			show_404();
		}

		echo json_encode($query);
	}

}

/* End of file Resepobat.php */
/* Location: ./application/controllers/Resepobat.php */
