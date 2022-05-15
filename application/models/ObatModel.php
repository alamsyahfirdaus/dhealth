<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ObatModel extends CI_Model {

	private $table      = 'obatalkes_m';
	private $primaryKey = 'obatalkes_id';

	private function _setBuilder()
	{
		$listFields  	= $this->db->list_fields($this->table);
		$orderBy		= [$this->primaryKey => 'desc'];

		if ($this->input->post('obatalkes_id')) {
			if (is_array($this->input->post('obatalkes_id'))) {
				$obatalkes_id = array();
				for ($i=0; $i < count($this->input->post('obatalkes_id')); $i++) { 
					$obatalkes_id[] = $this->input->post('obatalkes_id')[$i]['id'];
				}
				if (count($obatalkes_id) > 0) {
					$this->db->where_in('obatalkes_id', array_unique($obatalkes_id));
				}
			} else {
				$this->db->where('obatalkes_id', $this->input->post('obatalkes_id'));
			}
		}

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
		$this->db->select('o.*');
		$this->db->select('pc.nama_pengguna as created_name');
		$this->db->select('pm.nama_pengguna as modified_name');
		$this->db->join('pengguna pc', 'pc.id_pengguna = o.created_by', 'left');
		$this->db->join('pengguna pm', 'pm.id_pengguna = o.last_modified_by', 'left');
		$this->db->where('o.obatalkes_id', $id);
		$this->db->group_by('o.obatalkes_id');
		$query = $this->db->get('obatalkes_m o');
		return $query->row();
	}

	public function findObatTerpakai($id_obatalkes)
	{
		$stok = 0;
		foreach ($this->db->get_where('resep_obat', ['id_obatalkes' => $id_obatalkes])->result() as $row) {
			if ($row->id_obatalkes) {
				$stok += $row->qty;
			}
		}
		return $stok;
	}

}

/* End of file ObatModel.php */
/* Location: ./application/models/ObatModel.php */