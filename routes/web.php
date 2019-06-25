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

/* DASHBOARD */
Route::match(['get', 'post'], 'grid-outstanding', [
    'as' => 'get.outstanding',
    'uses' => 'OutstandingController@dataGrid'
]);

Route::get('/asset/edit/', 'AssetController@show');
Route::resource('/help', 'HelpController');

/* PAGES */
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
Route::get('get-no_po', ['as' => 'get.no_po', 'uses' => 'RequestController@getPO']);
Route::get('requestpdf', ['as' => 'get.requestpdf', 'uses' => 'RequestController@pdfDoc']);
Route::get('get-businessarea', ['as' => 'get.businessarea', 'uses' => 'RequestController@businessarea']);
Route::get('get-qty-po', ['as' => 'get.qty_po', 'uses' => 'RequestController@qty_po']);

Route::resource('/approval', 'ApprovalController');
Route::get('/approval/create/{type}', 'ApprovalController@create')->name('type');
Route::post('/approval/post', 'ApprovalController@store');
Route::get('/approval/edit/', 'ApprovalController@show');
Route::post('/approval/inactive', 'ApprovalController@inactive');
Route::post('/approval/active', 'ApprovalController@active');
Route::get('grid-approval', ['as' => 'get.approval_grid', 'uses' => 'ApprovalController@dataGrid']);
//Route::get('view-data-approval/{no_reg}', ['as' => 'get.approval_view', 'uses' => 'ApprovalController@dataView']);
Route::get('/approval/view/{no_reg}', 'ApprovalController@view')->name('no_reg');
Route::get('/approval/view_detail/{no_reg}/{id}', 'ApprovalController@get_asset_detail');
Route::post('/approval/delete_asset/{id}','ApprovalController@delete_asset');
Route::get('grid-approval-history', ['as' => 'get.approval_grid_history', 'uses' => 'ApprovalController@dataGridHistory']);
Route::post('/approval/update_status/{status}/{no_reg}','ApprovalController@update_status');
Route::get('/approval/log_history/{no_reg}', 'ApprovalController@log_history')->name('no_reg');

Route::resource('/mutasi', 'MutasiController');
Route::get('/mutasi/create/{type}', 'MutasiController@create')->name('type');
Route::post('/mutasi/post', 'MutasiController@store');
Route::get('/mutasi/edit/', 'MutasiController@show');
Route::post('/mutasi/inactive', 'MutasiController@inactive');
Route::post('/mutasi/active', 'MutasiController@active');
Route::get('grid-mutasi', ['as' => 'get.mutasi_grid', 'uses' => 'MutasiController@dataGrid']);

/* USER SETTINGS */
Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile', 'ProfileController@index');
Route::post('/ldaplogin', 'LDAPController@login');
Route::post('/ldaplogout', 'LDAPController@logout');

Route::resource('/users', 'UsersController');
Route::post('/users/post', 'UsersController@store');
Route::get('/users/edit/', 'UsersController@show');
Route::post('/users/inactive', 'UsersController@inactive');
Route::post('/users/active', 'UsersController@active');
Route::match(['get', 'post'], 'grid-users', [
    'as' => 'get.users',
    'uses' => 'UsersController@dataGrid'
]);

Route::resource('/menu', 'MenuController');
Route::post('/menu/post', 'MenuController@store');
Route::get('/menu/edit/', 'MenuController@show');
Route::post('/menu/inactive', 'MenuController@inactive');
Route::post('/menu/active', 'MenuController@active');
Route::match(['get', 'post'], 'grid-menu', [
    'as' => 'get.menu_grid',
    'uses' => 'MenuController@dataGrid'
]);

Route::resource('/modules', 'ModuleController');
Route::post('/modules/post', 'ModuleController@store');
Route::get('/modules/edit/', 'ModuleController@show');
Route::post('/modules/inactive', 'ModuleController@inactive');
Route::post('/modules/active', 'ModuleController@active');
Route::match(['get', 'post'], 'grid-modules', [
    'as' => 'get.grid_modules',
    'uses' => 'ModuleController@dataGrid'
]);

Route::resource('/roles', 'RolesController');
Route::post('/roles/post', 'RolesController@store');
Route::get('/roles/edit/', 'RolesController@show');
Route::post('/roles/inactive', 'RolesController@inactive');
Route::post('/roles/active', 'RolesController@active');
Route::match(['get', 'post'], 'grid-role_grid', [
    'as' => 'get.role_grid',
    'uses' => 'RolesController@dataGrid'
]);

Route::resource('/accessright', 'AccessRightController');
Route::post('/accessright/post', 'AccessRightController@store');
Route::get('/accessright/edit/', 'AccessRightController@show');
Route::post('/accessright/inactive', 'AccessRightController@inactive');
Route::post('/accessright/active', 'AccessRightController@active');
Route::match(['get', 'post'], 'grid-accessright', [
    'as' => 'get.accessright_grid',
    'uses' => 'AccessRightController@dataGrid'
]);

/* DOCS */
Route::get('SapDownloadExcel', 'SAPController@downloadExcel');

/* JSON DATA SOURCE */
Route::get( 'get-outstandingdetail', ['as' => 'get.outstandingdetail', 'uses' => 'OutstandingController@requestDetail']);
Route::get( 'get-outstandingdetailfiles', ['as' => 'get.outstandingdetailfiles', 'uses' => 'OutstandingController@requestDetailFiles']);
Route::get( 'get-outstandingdetailitem', ['as' => 'get.outstandingdetailitem', 'uses' => 'OutstandingController@requestDetailItem']);
Route::get( 'get-outstandingdetailitempo', ['as' => 'get.outstandingdetailitempo', 'uses' => 'OutstandingController@requestDetailItemPO']);
Route::get( 'get-outstandingdetailitemfile', ['as' => 'get.outstandingdetailitemfile', 'uses' => 'OutstandingController@requestDetailItemFile']);


/* SELECT 2 */
Route::get('get-select_module', ['as' => 'get.select_module', 'uses' => 'ModuleController@select2']);
Route::get('get-select_menu', ['as' => 'get.select_menu', 'uses' => 'MenuController@select2']);
Route::get('get-select_role', ['as' => 'get.select_role', 'uses' => 'RolesController@select2']);
Route::get('get-generaldataplant', ['as' => 'get.generaldataplant', 'uses' => 'Select2Controller@generaldataplant']);
Route::get('get-assetgroup', ['as' => 'get.assetgroup', 'uses' => 'Select2Controller@assetgroup']);
Route::get('get-assetsubgroup', ['as' => 'get.assetsubgroup', 'uses' => 'Select2Controller@assetsubgroup']);
Route::get( 'get-jenisasset', ['as' => 'get.jenisasset', 'uses' => 'Select2Controller@jenisasset']);
Route::get('get-select_workflow_code', ['as' => 'get.select_workflow_code', 'uses' => 'WorkflowController@workflowcode']);
Route::get('get-select_workflow_detail_code', ['as' => 'get.select_workflow_detail_code', 'uses' => 'WorkflowController@workflowcodedetail']);
Route::get('get-select_workflow_detail_role', ['as' => 'get.select_workflow_detail_role', 'uses' => 'WorkflowController@workflowcoderole']);

/* WORKFLOW SETTING */
Route::resource('/setting/workflow', 'WorkflowController');
Route::post('/workflow/post', 'WorkflowController@store');
Route::post('/workflow/post-detail', 'WorkflowController@store_detail');
Route::post('/workflow/post-detail-job', 'WorkflowController@store_detail_job');
Route::get('/workflow/edit/', 'WorkflowController@show');
Route::get('/workflow/edit-detail/', 'WorkflowController@show_detail');
Route::get('/workflow/edit-detail-job/', 'WorkflowController@show_detail_job');
Route::match(['get', 'post'], 'grid-workflow', [
    'as' => 'get.grid_workflow',
    'uses' => 'WorkflowController@dataGrid'
]);
/*
Route::match(['get', 'post'], 'grid-workflow-detail', [
    'as' => 'get.grid_workflow_detail',
    'uses' => 'WorkflowController@dataGrid'
]);
*/
Route::post('grid-workflow-detail/{id}', 'WorkflowController@dataGridDetail')->name('grid-workflow-detail/{id}');
Route::post('grid-workflow-detail-job/{id}', 'WorkflowController@dataGridDetailJob')->name('grid-workflow-detail-job/{id}');

/* MASTER GENERAL DATA SETTING */
Route::resource('/setting/general-data', 'GeneralDataController');
Route::match(['get', 'post'], 'grid-general-data', [
    'as' => 'get.grid_general_data',
    'uses' => 'GeneralDataController@dataGrid'
]);
Route::post('/general-data/post', 'GeneralDataController@store');
Route::get('/general-data/edit/', 'GeneralDataController@show');

