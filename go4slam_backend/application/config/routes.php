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
  |	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'user';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['cms_users'] = 'user/users_overview/cms';
$route['add_cms_user'] = 'user/add_or_edit_user/cms/0';
$route['edit_cms_user/(:num)'] = 'user/add_or_edit_user/cms/$1';
$route['app_users'] = 'user/users_overview/app';
$route['add_app_user'] = 'user/add_or_edit_user/app';
$route['edit_app_user/(:num)'] = 'user/add_or_edit_user/app/$1';
$route['delete_user/(:num)'] = 'user/delete_user/$1';
$route['add_sponsor'] = 'sponsors/add_or_edit_sponsor';
$route['edit_sponsor/(:num)'] = 'sponsors/add_or_edit_sponsor/$1';
$route['delete_sponsor/(:num)'] = 'sponsors/delete_sponsor/$1';
$route['add_newsletter'] = 'content/add_or_edit_newsletter';
$route['edit_newsletter/(:num)'] = 'content/add_or_edit_newsletter/$1';
$route['delete_newsletter/(:num)'] = 'content/delete_newsletter/$1';
$route['add_gallery'] = 'content/add_or_edit_gallery';
$route['edit_gallery/(:num)'] = 'content/add_or_edit_gallery/$1';
$route['delete_gallery/(:num)'] = 'content/delete_gallery/$1';
$route['add_score'] = 'content/add_or_edit_score';
$route['edit_score/(:num)'] = 'content/add_or_edit_score/$1';
$route['delete_score/(:num)'] = 'content/delete_score/$1';