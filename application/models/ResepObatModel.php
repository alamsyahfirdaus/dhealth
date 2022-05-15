<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ResepObatModel extends CI_Model {

	private $table      = 'resep_obat';
	private $primaryKey = 'id_resep_obat';

	private function _setBuilder()
	{
		$listFields  	= $this->db->list_fields($this->table);
		$columnSearch	= array_merge($listFields, ['obatalkes_kode', 'obatalkes_nama', 'stok', 'signa_kode', 'signa_nama']);
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->_setJoin();

		if ($this->input->post('jenis_racikan') == 'nonracikan') {

			$columnOrder = ['resep_obat.id_resep_obat', 'resep_obat.kode', 'resep_obat.id_obatalkes', 'resep_obat.qty', 'resep_obat.id_signa'];
			
			$this->db->where('id_obatalkes !=', NULL);
			$this->db->where('id_signa !=', NULL);
			$this->db->where('id_racikan', NULL);

		} elseif ($this->input->post('jenis_racikan') == 'racikan') {

			$columnOrder = ['resep_obat.id_resep_obat', 'resep_obat.kode', 'resep_obat.id_resep_obat', 'resep_obat.id_resep_obat', 'resep_obat.id_signa'];

			$this->db->where('id_obatalkes', NULL);
			$this->db->where('id_signa !=', NULL);
			$this->db->where('id_racikan', NULL);

		} else {
			if ($this->input->post('id_signa')) {
				$columnOrder = ['resep_obat.id_resep_obat', 'resep_obat.kode', 'resep_obat.id_resep_obat', 'resep_obat.id_resep_obat', 'resep_obat.id_signa'];
				$this->db->where('id_signa !=', NULL);
			} else {
				$columnOrder = ['resep_obat.id_resep_obat', 'resep_obat.id_obatalkes', 'resep_obat.qty'];
				$this->db->where('id_obatalkes !=', NULL);
				$this->db->where('id_signa', NULL);
				$this->db->where('id_racikan', $this->input->post('id_racikan'));
			}

		}

		
		$this->db->from($this->table);
		$this->include->setDataTables($columnOrder, $columnSearch, $orderBy);
	}

	private function _setJoin()
	{
		$this->db->select('resep_obat.*');
		$this->db->select('obatalkes_m.obatalkes_kode, obatalkes_m.obatalkes_nama');
		$this->db->select('signa_m.signa_kode, signa_m.signa_nama');
		$this->db->join('obatalkes_m', 'obatalkes_m.obatalkes_id = resep_obat.id_obatalkes', 'left');
		$this->db->join('signa_m', 'signa_m.signa_id = resep_obat.id_signa', 'left');
	}

	public function getDataTable()
	{
		return array(
			'dataTable' 		=> $this->include->getResult($this->_setBuilder()),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered'	=> $this->db->get($this->_setBuilder())->num_rows(),
		);
	}

	public function getRow($id)
	{
		$this->_setJoin();
		$this->db->where($this->primaryKey, $id);
		$query = $this->db->get($this->table);
		return $query->row();
	}

	public function findRacikan($id_racikan)
	{
		$this->_setJoin();
		$this->db->where('id_racikan', $id_racikan);
		$query = $this->db->get($this->table);
		return $query->result();
	}

}

/* End of file ResepObatModel.php */
/* Location: ./application/models/ResepObatModel.php */