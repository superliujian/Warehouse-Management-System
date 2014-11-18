<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = 'user/index';
$route['404_override'] = '';

/*admin*/
$route['admin'] = 'user/index';
$route['admin/signup'] = 'user/signup';
$route['admin/create_member'] = 'user/create_member';
$route['admin/login'] = 'user/index';
$route['admin/logout'] = 'user/logout';
$route['admin/change_password'] = 'user/change_password';
$route['admin/login/validate_credentials'] = 'user/validate_credentials';

$route['admin/change_password_post'] = 'user/change_password_post';

$route['admin/products/repackage'] = 'admin_products/repackage'; //$1 = page number
$route['admin/products/repackage/(:any)'] = 'admin_products/repackage/$1'; //$1 = page number

$route['admin/repackage/undo/(:any)'] = 'admin_products/undo_repackage/$1'; //$1 = unique id to undo repackaging

$route['admin/products'] = 'admin_products/index';
$route['admin/products/add'] = 'admin_products/add';
$route['admin/products/update'] = 'admin_products/update';
$route['admin/products/update/(:any)'] = 'admin_products/update/$1';
$route['admin/products/delete'] = 'admin_products/delete';
$route['admin/products/(:any)'] = 'admin_products/index/$1'; //$1 = page number

$route['admin/stock'] = 'admin_stock/index';
$route['admin/stock/add'] = 'admin_stock/add';
$route['admin/stock/update'] = 'admin_stock/update';
$route['admin/stock/update/(:any)'] = 'admin_stock/update/$1';
$route['admin/stock/delete'] = 'admin_stock/delete';
$route['admin/stock/(:any)'] = 'admin_stock/index/$1'; //$1 = page number

$route['admin/release'] = 'admin_release/index';
$route['admin/release/add'] = 'admin_release/add';
$route['admin/release/update'] = 'admin_release/update';
$route['admin/release/update/(:any)'] = 'admin_release/update/$1';
$route['admin/release/delete'] = 'admin_release/delete';
$route['admin/release/(:any)'] = 'admin_release/index/$1'; //$1 = page number

$route['admin/payments/js_clients'] = 'admin_payments/js_clients';
$route['admin/payments/js_clients_pro'] = 'admin_payments/js_clients_pro';

$route['admin/payments'] = 'admin_payments/index';
$route['admin/payments/add'] = 'admin_payments/add';
$route['admin/payments/update'] = 'admin_payments/update';
$route['admin/payments/update/(:any)'] = 'admin_payments/update/$1';
$route['admin/payments/delete'] = 'admin_payments/delete';
$route['admin/payments/(:any)'] = 'admin_payments/index/$1'; //$1 = page number

$route['admin/reports/(:any)'] = 'admin_reports/display_client_report';
//$route['admin/reports/(:any)/(:any)'] = 'admin_reports/display_client_report';

$route['admin/expense'] = 'admin_expense/index';
$route['admin/expense/add'] = 'admin_expense/add';
$route['admin/expense/update'] = 'admin_expense/update';
$route['admin/expense/update/(:any)'] = 'admin_expense/update/$1';
$route['admin/expense/delete'] = 'admin_expense/delete';
$route['admin/expense/(:any)'] = 'admin_expense/index/$1'; //$1 = page number

$route['admin/clients'] = 'admin_manufacturers/index';
$route['admin/clients/add'] = 'admin_manufacturers/add';
$route['admin/clients/update'] = 'admin_manufacturers/update';
$route['admin/clients/update/(:any)'] = 'admin_manufacturers/update/$1';
$route['admin/clients/delete'] = 'admin_manufacturers/delete';
$route['admin/clients/(:any)'] = 'admin_manufacturers/index/$1'; //$1 = page number



/* End of file routes.php */
/* Location: ./application/config/routes.php */