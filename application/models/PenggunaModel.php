<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PenggunaModel extends CI_Model {

	private $table      = 'pengguna';
	private $primaryKey = 'id_pengguna';

	private function _setBuilder()
	{
		$listFields 	= $this->db->list_fields($this->table);
		$columnOrder	= $listFields;
		$columnSearch	= $listFields;
		$orderBy		= [$this->primaryKey => 'desc'];

		$this->db->where('id_pengguna !=', $this->session->id_pengguna);
		$this->db->from($this->table);
		$this->include->setDataTables($columnOrder, $columnSearch, $orderBy);
	}

	public function getDataTable()
	{
		return array(
			'dataTable' 		=> $this->include->getResult($this->_setBuilder()),
			'recordsTotal'		=> $this->db->count_all_results($this->table),
			'recordsFiltered'	=> $this->db->get($this->_setBuilder())->num_rows(),
		);
	}

}

/* End of file PenggunaModel.php */
/* Location: ./application/models/PenggunaModel.php */