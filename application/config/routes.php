<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['auth/login']					= 'Login/proses_login';
$route['logout']						= 'Login/logout';
$route['settings']						= 'Home/settings';
$route['list/pengguna']					= 'Home/list_pengguna';
$route['save/pengguna']					= 'Home/save_pengguna';
$route['update_foto/pengguna/(:any)']	= 'Home/update_foto_profile/$1';
$route['delete/pengguna/(:any)']		= 'Home/delete_pengguna/$1';
$route['password/pengguna/(:any)']		= 'Home/update_password_pengguna/$1';
$route['delete_foto/pengguna/(:any)']	= 'Home/delete_foto_profile/$1';

$route['obat']							= 'Obat/index';
$route['list/obat']						= 'Obat/list_obat';
$route['detail/obat/(:any)']			= 'Obat/detail_obat/$1';
$route['add/obat']						= 'Obat/add_edit_obat';
$route['edit/obat/(:any)']				= 'Obat/add_edit_obat/$1';
$route['save/obat']						= 'Obat/save_obat';
$route['delete/obat/(:any)']			= 'Obat/delete_obat/$1';


$route['signa']							= 'Signa/index';
$route['list/signa']					= 'Signa/list_signa';
$route['detail/signa/(:any)']			= 'Signa/detail_signa/$1';
$route['add/signa']						= 'Signa/add_edit_signa';
$route['edit/signa/(:any)']				= 'Signa/add_edit_signa/$1';
$route['save/signa']					= 'Signa/save_signa';
$route['delete/signa/(:any)']			= 'Signa/delete_signa/$1';

$route['nonracikan']					= 'Resepobat/nonracikan';
$route['list/nonracikan']				= 'Resepobat/list_nonracikan';
$route['add/nonracikan']				= 'Resepobat/add_edit_nonracikan';
$route['edit/nonracikan/(:any)']		= 'Resepobat/add_edit_nonracikan/$1';
$route['list/qty/(:any)']				= 'Resepobat/list_qty/$1';
$route['racikan']						= 'Resepobat/racikan';
$route['list/racikan']					= 'Resepobat/list_racikan';
$route['add/racikan']					= 'Resepobat/add_edit_racikan';
$route['edit/racikan/(:any)']			= 'Resepobat/add_edit_racikan/$1';
$route['save/resepobat']				= 'Resepobat/save_resepobat';
$route['delete/resepobat/(:any)']		= 'Resepobat/delete_resepobat/$1';
$route['list/racikanobat']				= 'Resepobat/list_racikanobat';
$route['list/addobat']					= 'Resepobat/list_addobat';
$route['edit_qty/racikan']				= 'Resepobat/edit_qty';
$route['print/prescription/(:any)']		= 'Resepobat/print_resepobat/$1';