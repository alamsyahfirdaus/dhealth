<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomLibrary
{
	protected $ci;

	public function __construct()
	{
        $this->ci =& get_instance();
        date_default_timezone_set('Asia/Jakarta');
	}

	public function layout($content, $data = NULL)
	{
		$section = array('content' => $this->ci->load->view('section/' . $content, $data, TRUE));
		return $this->ci->load->view('layout/index_page', $section);
	}

	# DataTables

	public function setDataTables($col_order, $col_search, $order_by)
	{
		$i = 0;
		foreach ($col_search as $row) {
			if(@$_POST['search']['value']) {

				if($i === 0) {
					$this->ci->db->group_start();
					$this->ci->db->like($row, $_POST['search']['value']);
				} else {
					$this->ci->db->or_like($row, $_POST['search']['value']);
				}

				if(count($col_search) - 1 == $i)
					$this->ci->db->group_end();
			}
			$i++;
		}
		if(@$_POST['order']) {
			$this->ci->db->order_by($col_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if(@$order_by) {
			$this->ci->db->order_by(key($order_by), $order_by[key($order_by)]);
		}
	}

	private function _getPaging()
	{
	    if($this->ci->input->post('length') != -1)
	    $this->ci->db->limit($this->ci->input->post('length'), $this->ci->input->post('start'));
	}

	private $resultSet;

	public function getResult($bulider)
	{
	    $this->_getPaging();
	    $this->resultSet = $bulider;
	    return $this->ci->db->get()->result();
	}

	#End DataTables

	public function datetime($date)
	{
	    if ($date) {
	        $datetime = $date;
	    } else {
	    	return '-';
	    }

	    $moths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	    $year 		= substr($datetime, 0, 4);
	    $month 		= substr($datetime, 5, 2);
	    $date  	 	= substr($datetime, 8, 2);
	    $hour   	= substr($datetime, 11, 2);
	    $minute   	= substr($datetime, 14, 2);
	    $second   	= substr($datetime, 17, 2);
	    $substr	= substr($date, 0, 1) == 0 ? substr($date, 1) : $date;

	    $result 	= $substr . " " . $moths[(int) $month - 1] . " " . $year . " " . $hour . ":" . $minute . ":" . $second;
	    return ($result);
	}

	public function date($datetime)
	{
		if ($datetime) {
		    $date = $datetime;
		} else {
			return '-';
		}

	    $moths = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

	    $year 	= substr($date, 0, 4);
	    $moth 	= substr($date, 5, 2);
	    $date 	= substr($date, 8, 2);

	    $substr	= substr($date, 0, 1) == 0 ? substr($date, 1) : $date;

	    $result = $substr . " " . $moths[(int) $moth - 1] . " " . $year;
	    return ($result);
	}
}

/* End of file CustomLibrary.php */
/* Location: ./application/libraries/CustomLibrary.php */
