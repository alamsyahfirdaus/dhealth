<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SignaModel extends CI_Model {

	private $table      = 'signa_m';
	private $primaryKey = 'signa_id';

	private function _setBuilder()
	{
		$listFields  	= $this->db->list_fields($this->table);
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->db->from($this->table);
		$this->include->setDataTables($listFields, $listFields, $orderBy);
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
		$this->db->select('s.*');
		$this->db->select('pc.nama_pengguna as created_name');
		$this->db->select('pm.nama_pengguna as modified_name');
		$this->db->join('pengguna pc', 'pc.id_pengguna = s.created_by', 'left');
		$this->db->join('pengguna pm', 'pm.id_pengguna = s.last_modified_by', 'left');
		$this->db->where('s.signa_id', $id);
		$this->db->group_by('s.signa_id');
		$query = $this->db->get('signa_m s');
		return $query->row();
	}

}

/* End of file SignaModel.php */
/* Location: ./application/models/SignaModel.php */