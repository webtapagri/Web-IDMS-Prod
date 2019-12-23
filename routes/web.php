<?php

use App\Http\Controllers\MaterialController;

Auth::routes();

Route::group(['middleware' => ['web']], function () {

});

Route::group(['middleware' => [ 'auth' ]], function () {
	
	Route::get('/', 'RoadController@index')->name('road');
	Route::get('/icons', 'HomeController@icons')->name('icons');

	Route::group(['prefix'=>'api/master'], function () {
		Route::get('/road-status', 				['as'=>'master.api_road_status', 'uses'=>'RoadController@api_status']);
		Route::get('/sync-afd', 				['as'=>'master.api_sync_afd', 'uses'=>'MasterController@sync_afd']);
		
	});

	Route::group(['prefix'=>'master'], function () {
		Route::get('/road-status', 				['as'=>'master.road_status', 'uses'=>'RoadController@status']);
		Route::get('/road-status-datatables', 	['as'=>'master.road_status_datatables', 'uses'=>'RoadController@status_datatables']);
		Route::get('/road-status-add', 			['as'=>'master.road_status_add', 'uses'=>'RoadController@add']);
		Route::post('/road-status-save', 		['as'=>'master.road_status_save', 'uses'=>'RoadController@save']);
		Route::post('/road-status-update', 		['as'=>'master.road_status_update', 'uses'=>'RoadController@update']);
		Route::get('/road-status-delete/{id}', 	['as'=>'master.road_status_delete', 'uses'=>'RoadController@delete']);
		
		Route::get('/road-category', 			['as'=>'master.road_category', 'uses'=>'RoadController@category']);
		Route::post('/road-category-save', 		['as'=>'master.road_category_save', 'uses'=>'RoadController@category_save']);
		Route::get('/road-category-datatables', 	['as'=>'master.road_category_datatables', 'uses'=>'RoadController@category_datatables']);
		Route::post('/road-category-update', 		['as'=>'master.road_category_update', 'uses'=>'RoadController@category_update']);
		Route::get('/road-category-delete/{id}', 	['as'=>'master.road_category_delete', 'uses'=>'RoadController@category_delete']);
	});
	
	
});




###################################################################################################### 

/* USER SETTINGS */

Route::get('/home', 'ApprovalController@index')->name('home');
Route::get('/profile', 'ProfileController@index')->name('profile');
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
Route::get('get-assetgroupcondition', ['as' => 'get.assetgroupcondition', 'uses' => 'Select2Controller@assetgroupcondition']);
Route::get('get-assetsubgroup', ['as' => 'get.assetsubgroup', 'uses' => 'Select2Controller@assetsubgroup']);
Route::get( 'get-jenisasset', ['as' => 'get.jenisasset', 'uses' => 'Select2Controller@jenisasset']);
Route::get('get-select_workflow_code', ['as' => 'get.select_workflow_code', 'uses' => 'WorkflowController@workflowcode']);
Route::get('get-select_workflow_detail_code', ['as' => 'get.select_workflow_detail_code', 'uses' => 'WorkflowController@workflowcodedetail']);
Route::get('get-select_workflow_detail_role', ['as' => 'get.select_workflow_detail_role', 'uses' => 'WorkflowController@workflowcoderole']);
Route::get('get-select_workflow_detail_code', ['as' => 'get.select_workflow_detail_code', 'uses' => 'WorkflowController@workflowcodedetail']);
Route::get('get-select_jenis_asset_code', ['as' => 'get.select_jenis_asset_code', 'uses' => 'AssetClassController@select_jenis_asset_code']);
Route::get('get-select_jenis_asset_code_text_only', ['as' => 'get.select_jenis_asset_code_text_only', 'uses' => 'AssetClassController@select_jenis_asset_code_text_only']);
Route::get('get-select_group_code', ['as' => 'get.select_group_code', 'uses' => 'AssetClassController@select_group_code']);
Route::get('get-select_subgroup_code', ['as' => 'get.select_subgroup_code', 'uses' => 'AssetClassController@select_subgroup_code']);
Route::get('get-select_subgroup_code_condition', ['as' => 'get.select_subgroup_code_condition', 'uses' => 'AssetClassController@select_subgroup_code_condition']);
Route::get('get-select_asset_controller', ['as' => 'get.select_asset_controller', 'uses' => 'AssetClassController@select_asset_controller']);
Route::get('get-generaldata-assetcontroller', ['as' => 'get.generaldata_assetcontroller', 'uses' => 'Select2Controller@generaldata_assetcontroller']);
Route::get('get-select_role_idname', ['as' => 'get.select_role_idname', 'uses' => 'RolesController@select_role']);
Route::get('get-select_user', ['as' => 'get.select_user', 'uses' => 'UsersController@select2']);
Route::get('get-select_uom', ['as' => 'get.select_uom', 'uses' => 'Select2Controller@select_uom']);
Route::get('get-tujuan_business_area', ['as' => 'get.tujuan_business_area', 'uses' => 'Select2Controller@tujuan_business_area']);

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

/* ASSET CLASS - SETTING */
Route::resource('/setting/asset-class', 'AssetClassController');
Route::match(['get', 'post'], 'grid-asset-class', [
    'as' => 'get.grid_asset_class',
    'uses' => 'AssetClassController@dataGrid'
]);
Route::post('/asset-class/post', 'AssetClassController@store');
Route::post('/asset-class/post-group-asset', 'AssetClassController@store_group_asset');
Route::post('/asset-class/post-subgroup-asset', 'AssetClassController@store_subgroup_asset');
Route::post('/asset-class/post-asset-map', 'AssetClassController@store_asset_map');
Route::get('/asset-class/edit/', 'AssetClassController@show');
Route::get('/asset-class/edit-group-asset/', 'AssetClassController@show_group_asset');
Route::get('/asset-class/edit-subgroup-asset/', 'AssetClassController@show_subgroup_asset');
Route::get('/asset-class/edit-asset-map/', 'AssetClassController@show_asset_map');
Route::post('grid-ac-group-asset/{id}', 'AssetClassController@dataGridGroupAsset')->name('grid-ac-group-asset/{id}');
Route::post('grid-ac-subgroup-asset/{id}/{id_jenis_asset_code}', 'AssetClassController@dataGridSubGroupAsset')->name('grid-ac-subgroup-asset/{id}/{id_jenis_asset_code}');
Route::post('grid-ac-asset-map/{id}', 'AssetClassController@dataGridAssetMap')->name('grid-ac-asset-map/{id}');

/* SETTING - ROLE MAP */
Route::resource('/setting/role-map', 'RoleMapController');
Route::match(['get', 'post'], 'grid-role-map', [
    'as' => 'get.grid_role_map',
    'uses' => 'RoleMapController@dataGrid'
]);
Route::get('/role-map/edit/', 'RoleMapController@show');
Route::post('/role-map/post', 'RoleMapController@store');

/* SEND EMAIL */
Route::post('/request/email_create_po','FamsEmailController@index');

/* MASTER ASSET */
Route::resource('/master-asset', 'MasterAssetController');
Route::match(['get', 'post'], 'grid-master-asset', [
    'as' => 'get.grid_master_asset',
    'uses' => 'MasterAssetController@dataGrid'
]);
//Route::get('/master-asset/edit/', 'MasterAssetController@show');
Route::get('/master-asset/show-data/{id}', 'MasterAssetController@show_edit');
Route::get('/master-asset/show_qrcode/{ams}', 'MasterAssetController@show_qrcode')->name('ams');
Route::get('/test_qrcode', 'MasterAssetController@test_qrcode');
Route::get('/master-asset/print-qrcode/{noreg}', 'MasterAssetController@print_qrcode')->name('noreg');
Route::post('/master-asset/download', 'MasterAssetController@download')->name('master_asset.download');
Route::get('bulk-download', 'MasterAssetController@view_download_masterasset_qrcode')->name('view_download_masterasset_qrcode');
Route::post('download_masterasset_qrcode', 'MasterAssetController@download_masterasset_qrcode')->name('download_masterasset_qrcode');

/* REQUEST ASET LAINNYA */
Route::resource('/request', 'RequestAsetLainController');
Route::get('/create/aset-lain', 'RequestAsetLainController@create');
Route::post('/aset-lain/post', 'RequestAsetLainController@store');

/* RESUME PROCESS */
Route::get('/resume/document', 'ResumeController@index');
Route::post('/resume/document-submit','ResumeController@document_submit');
Route::get('/resume/user', 'ResumeController@user');
Route::get('get-select_role_resume', ['as' => 'get.select_role_resume', 'uses' => 'Select2Controller@select_role']);
Route::get('get-select_user_resume', ['as' => 'get.select_user_resume', 'uses' => 'Select2Controller@select_user']);
Route::post('/resume/user-submit','ResumeController@user_submit');

/* ALL REPORT */
Route::get('/report/list-asset', 'ReportController@list_asset');
Route::post('/report/list-asset/submit', 'ReportController@list_asset_submit');
Route::post('/report/list-asset/download', 'ReportController@list_asset_download');

/* DISPOSAL */
Route::resource('/disposal-penjualan', 'DisposalController');
Route::get('/disposal-penjualan/add/{id}/{pengajuan}', 'DisposalController@add');
Route::get('/disposal-penjualan/delete/{kode_asset_ams}', 'DisposalController@remove');

Route::get('/disposal-hilang', 'DisposalController@index_hilang');
Route::get('/disposal-hilang/add_hilang/{id}/{pengajuan}', 'DisposalController@add_hilang');
Route::get('/disposal-hilang/delete_hilang/{kode_asset_ams}', 'DisposalController@remove_hilang');

Route::get('/disposal-rusak', 'DisposalController@index_rusak');
Route::get('/disposal-rusak/add_rusak/{id}/{pengajuan}', 'DisposalController@add_rusak');
Route::get('/disposal-rusak/delete_rusak/{kode_asset_ams}', 'DisposalController@remove_rusak');

Route::post('/proses_disposal/{tipe}','DisposalController@proses');
Route::post('/disposal/edit_harga', 'DisposalController@update_harga_perolehan');
Route::post('/disposal/upload_berkas_hilang', 'DisposalController@upload_berkas_hilang');
Route::post('/disposal/upload_berkas_rusak', 'DisposalController@upload_berkas_rusak');
Route::post('/disposal/upload_berkas', 'DisposalController@upload_berkas');
Route::get('/approval/berkas-disposal/{no_reg}', 'ApprovalController@berkas_disposal')->name('no_reg');
Route::get('/approval/berkas-mutasi/{no_reg}', 'ApprovalController@berkas_mutasi')->name('no_reg');
Route::get('/disposal/view-berkas/{no_reg}', 'DisposalController@berkas_disposal')->name('no_reg');
Route::get('/disposal/view-berkas-serah-terima/{kode_asset_ams}', 'DisposalController@berkas_serah_terima')->name('kode_asset_ams');
Route::get('/disposal/view-berkas-detail/{kode_asset_ams}/{file_category}', 'DisposalController@berkas_disposal_detail')->name('kode_asset_ams');
Route::get('/disposal/list-kategori-upload/{kode_asset_ams}/{jenis_pengajuan}', 'DisposalController@list_file_category')->name('kode_asset_ams');
Route::get('/disposal/view-berkas-notes/{kode_asset_ams}', 'DisposalController@berkas_notes')->name('kode_asset_ams');
Route::get('/disposal/view-berkas-by-type/{kode_asset_ams}/{file_category}', 'DisposalController@file_download')->name('kode_asset_ams');
Route::get('/disposal/delete_berkas_temp','DisposalController@delete_berkas_temp');
/* END DISPOSAL */



