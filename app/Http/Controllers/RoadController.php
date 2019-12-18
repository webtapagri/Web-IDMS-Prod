<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\RoadStatus;
use App\Models\RoadCategory;
use App\Models\VRoadCategory;
use App\Http\Requests\RoadStatusRequest;
use App\Http\Requests\RoadCategoryRequest;
use Yajra\DataTables\Facades\DataTables;
use URL;
use Carbon\Carbon;

class RoadController extends Controller
{
    public function index()
	{
		return view('road.index');
	}
    public function status(Request $request)
	{
            // $data = $request->session()->all();
			// dd($data);
		$title = 'Road Status list';
		$data['ctree'] = '/master/road-status';
		return view('road.status', compact('data','title'));
	}
	
	public function api_status(Request $request)
	{
		try {
			
			$get = RoadStatus::all();
			
		}catch (\Throwable $e) {
            return response()->error('Error',throwable_msg($e));
        }catch (\Exception $e) {
            return response()->error('Error',exception_msg($e));
		}
		
		return response()->success('Success', $get);
	}
	
	public function status_datatables()
	{
		$model = RoadStatus::whereRaw('1=1');
		
		return Datatables::eloquent($model)
			->addColumn('action', '<div class="text-center">
					<button class="btn btn-link text-primary-600" onclick="edit({{ $id }}, \'{{ $status_name }}\'); return false;">
						<i class="icon-pencil7"></i> Edit
					</button>
					<a class="btn btn-link text-danger-600" href="" onclick="del(\''.URL::to('master/road-status-delete/{{ $id }}').'\'); return false;">
						<i class="icon-trash"></i> Hapus
					</a>
				<div>
				')
			->rawColumns(['action'])
			->make(true);
	}
	
	public function add()
	{
		$title = 'Tambah Road Status';
		return view('road.status_add', compact('title'));
	}
	
	public function save(RoadStatusRequest $request)
	{
		try {
			RoadStatus::create($request->only('status_name'));
		}catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan pada backend ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            $msg = 'Terjadi kesalahan sistem silahkan tunggu beberapa saat dan ulangi kembali. Error messages ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil menyimpan data');
        return redirect()->route('master.road_status');
	}
	
	public function update(RoadStatusRequest $request)
	{
		try {
			$RS = RoadStatus::find($request->id);
			$RS->status_name = $request->status_name;
			$RS->save();
		}catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan pada backend ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            $msg = 'Terjadi kesalahan sistem silahkan tunggu beberapa saat dan ulangi kembali. Error messages ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
        return redirect()->route('master.road_status');
	}
	
	public function delete($id){
		
		try {
			$data = RoadStatus::find($id);
			$data->deleted_at = Carbon::now();
			$data->updated_by = \Session::get('user_id');
			$data->save();
			
		}catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan pada backend ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back();
        }catch (\Exception $e) {
            $msg = 'Terjadi kesalahan sistem silahkan tunggu beberapa saat dan ulangi kembali. Error messages ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back();
		}
		
		\Session::flash('success', 'Berhasil menghapus data');
        return redirect()->route('master.road_status');
	}
	
	//STart Master Category Module
	
	public function category(Request $request)
	{
		$access = access($request);
		// dd($access);
		$title = 'Road Category list';
		$data['ctree'] = '/master/road-category';
		return view('road.category', compact('access','data','title'));
	}
	
	public function category_datatables(Request $request)
	{
		$access = access($request, 'master/road-category');
		$model = VRoadCategory::whereRaw('1=1');
		
		$update_action = '';
		$delete_action = '';
		if($access['update']==1){
			$update_action = '
					<button class="btn btn-link text-primary-600" onclick="edit({{ $id }}, \'{{ $category_name }}\', \'{{ $category_code }}\', \'{{ $category_initial }}\', \'{{ $status_id }}\'); return false;">
						<i class="icon-pencil7"></i> Edit
					</button>
			';
		}
		if($access['delete']==1){
			$delete_action = '
					<a class="btn btn-link text-danger-600" href="" onclick="del(\''.URL::to('master/road-category-delete/{{ $id }}').'\'); return false;">
						<i class="icon-trash"></i> Hapus
					</a>
			';
		}
		
		return Datatables::eloquent($model)
			->addColumn('action', '<div class="text-center">
					'.$update_action.'
					'.$delete_action.'
				<div>
				')
			->rawColumns(['action'])
			->make(true);
	}
	
	public function category_save(Request $request)
	{
		try {
			RoadCategory::create($request->only('status_id','category_name','category_code','category_initial'));
		}catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan pada backend ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            $msg = 'Terjadi kesalahan sistem silahkan tunggu beberapa saat dan ulangi kembali. Error messages ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil menyimpan data');
        return redirect()->route('master.road_category');
	}
	
	public function category_update(RoadCategoryRequest $request)
	{
		try {
			$RS = RoadCategory::find($request->id);
			$RS->status_id = $request->status_id;
			$RS->category_name = $request->category_name;
			$RS->category_code = $request->category_code;
			$RS->category_initial = $request->category_initial;
			$RS->save();
		}catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan pada backend ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
        }catch (\Exception $e) {
            $msg = 'Terjadi kesalahan sistem silahkan tunggu beberapa saat dan ulangi kembali. Error messages ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
        return redirect()->route('master.road_category');
	}
	
	public function category_delete($id){
		
		try {
			$data = RoadCategory::find($id);			
			$data->deleted_at = Carbon::now();
			$data->updated_by = \Session::get('user_id');
			$data->save();
			
			
			
		}catch (\Throwable $e) {
            $msg = 'Terjadi kesalahan pada backend ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back();
        }catch (\Exception $e) {
            $msg = 'Terjadi kesalahan sistem silahkan tunggu beberapa saat dan ulangi kembali. Error messages ->'.$e->getMessage();
			\Session::flash('error', $msg);
            return redirect()->back();
		}
		
		\Session::flash('success', 'Berhasil menghapus data');
        return redirect()->route('master.road_category');
	}
	
	
	
	
}
