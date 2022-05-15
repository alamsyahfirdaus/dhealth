<?php

function logged_in()
{
	$ci =& get_instance();
	if (!$ci->session->id_pengguna) {
		redirect('login');
	}
}

function logged_out()
{
	$ci =& get_instance();
	if ($ci->session->id_pengguna) {
		redirect('home');
	}
}

/* End of file auth_helper.php */
/* Location: ./application/helpers/auth_helper.php */
