<?php

use App\Http\Controllers\MaterialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Auth::routes();
Route::group(['middleware' => ['web']], function () {

});

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'ProfileController@index');
Route::post('/ldaplogin', 'LDAPController@login');
Route::post('/ldaplogout', 'LDAPController@logout');

Route::resource('/asset', 'AssetController');
Route::get('/asset/create/{type}', 'AssetController@create')->name('type');
Route::post('/asset/post', 'AssetController@store');
Route::get('/asset/edit/', 'AssetController@show');
Route::post('/asset/inactive', 'AssetController@inactive');
Route::get('grid-asset', ['as' => 'get.asset_grid', 'uses' => 'AssetController@dataGrid']);
Route::get('asset_pdf', ['as' => 'get.asset_pdf', 'uses' => 'AssetController@convertToPdf']);
Route::get('asset_report', ['as' => 'get.asset_report', 'uses' => 'AssetController@report']);

Route::resource('/request', 'RequestController');
Route::get('/request/create/{type}', 'RequestController@create')->name('type');
Route::post('/request/post', 'RequestController@store');
Route::get('/request/edit/', 'RequestController@show');
Route::post('/request/inactive', 'RequestController@inactive');
Route::post('/request/active', 'RequestController@active');
Route::get('grid-request', ['as' => 'get.request_grid', 'uses' => 'RequestController@dataGrid']);
Route::get('requestpdf', ['as' => 'get.requestpdf', 'uses' => 'RequestController@pdfDoc']);

Route::resource('/approval', 'ApprovalController');
Route::get('/approval/create/{type}', 'ApprovalController@create')->name('type');
Route::post('/approval/post', 'ApprovalController@store');
Route::get('/approval/edit/', 'ApprovalController@show');
Route::post('/approval/inactive', 'ApprovalController@inactive');
Route::post('/approval/active', 'ApprovalController@active');
Route::get('grid-approval', ['as' => 'get.approval_grid', 'uses' => 'ApprovalController@dataGrid']);


Route::resource('/mutasi', 'MutasiController');
Route::get('/mutasi/create/{type}', 'MutasiController@create')->name('type');
Route::post('/mutasi/post', 'MutasiController@store');
Route::get('/mutasi/edit/', 'MutasiController@show');
Route::post('/mutasi/inactive', 'MutasiController@inactive');
Route::post('/mutasi/active', 'MutasiController@active');
Route::get('grid-mutasi', ['as' => 'get.mutasi_grid', 'uses' => 'MutasiController@dataGrid']);

/* USER SETTINGS */
Route::resource('/users', 'UsersController');
Route::post('/users/post', 'UsersController@store');
Route::get('/users/edit/', 'UsersController@show');
Route::post('/users/inactive', 'UsersController@inactive');
Route::post('/users/active', 'UsersController@active');
Route::get('grid-tr-user', ['as' => 'get.grid_tr_user', 'uses' => 'UsersController@dataGrid']);

Route::resource('/menu', 'MenuController');
Route::post('/menu/post', 'MenuController@store');
Route::get('/menu/edit/', 'MenuController@show');
Route::post('/menu/inactive', 'MenuController@inactive');
Route::post('/menu/active', 'MenuController@active');
Route::get('grid-menu', ['as' => 'get.menu_grid', 'uses' => 'MenuController@dataGrid']);

Route::resource('/modules', 'ModuleController');
Route::post('/modules/post', 'ModuleController@store');
Route::get('/modules/edit/', 'ModuleController@show');
Route::post('/modules/inactive', 'ModuleController@inactive');
Route::post('/modules/active', 'ModuleController@active');
Route::get('grid-modules', ['as' => 'get.grid_modules', 'uses' => 'ModuleController@dataGrid']);

Route::resource('/menu', 'MenuController');
Route::post('/menu/post', 'MenuController@store');
Route::get('/menu/edit/', 'MenuController@show');
Route::post('/menu/inactive', 'MenuController@inactive');
Route::post('/menu/active', 'MenuController@active');
Route::get('grid-menu', ['as' => 'get.grid_menu', 'uses' => 'MenuController@dataGrid']);

Route::resource('/roles', 'RolesController');
Route::post('/roles/post', 'RolesController@store');
Route::get('/roles/edit/', 'RolesController@show');
Route::post('/roles/inactive', 'RolesController@inactive');
Route::post('/roles/active', 'RolesController@active');
Route::get('grid-tm-role', ['as' => 'get.grid_tm_role', 'uses' => 'RolesController@dataGrid']);


Route::resource('/roleusers', 'RoleUserController');
Route::post('/roleusers/post', 'RoleUserController@store');
Route::get('/roleusers/edit/', 'RoleUserController@show');
Route::post('/roleusers/inactive', 'RoleUserController@inactive');
Route::post('/roleusers/active', 'RoleUserController@active');
Route::get('grid-role-user', ['as' => 'get.role_user', 'uses' => 'RoleUserController@dataGrid']);
Route::get('get-select_tr_user', ['as' => 'get.select_tr_user', 'uses' => 'RoleUserController@get_tr_user']);
Route::get('get-select_role', ['as' => 'get.select_role', 'uses' => 'RoleUserController@get_role']);

Route::resource('/accessright', 'AccessRightController');
Route::post('/accessright/post', 'AccessRightController@store');
Route::get('/accessright/edit/', 'AccessRightController@show');
Route::post('/accessright/inactive', 'AccessRightController@inactive');
Route::post('/accessright/active', 'AccessRightController@active');
Route::get('grid-accessright', ['as' => 'get.accessright_grid', 'uses' => 'AccessRightController@dataGrid']);
Route::get('get-select_menu', ['as' => 'get.select_menu', 'uses' => 'AccessRightController@get_menu']);

Route::resource('/roleaccess', 'RoleAccessController');


Route::get('SapDownloadExcel', 'SAPController@downloadExcel');
