<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Master;

class MasterController extends Controller
{
    
	/*
		Endpoint:
		
			afdeling/all
			block/all
			comp/all
			est/all
			region/all
	
	*/
	
	public function sync_afd()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('afdeling/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
					
		// return $RestAPI;
		
		try {
				$RS = RoadStatus::find($request->id);
				$RS->status_name = strtoupper($request->status_name);
				$RS->status_code = $request->status_code;
				$RS->updated_by = \Session::get('user_id');
				$RS->save();
		}catch (\Throwable $e) {
			\Session::flash('error', throwable_msg($e));
			return redirect()->back()->withInput($request->input());
		}catch (\Exception $e) {
			\Session::flash('error', exception_msg($e));
			return redirect()->back()->withInput($request->input());
		}
		
		\Session::flash('success', 'Berhasil mengupdate data');
		return redirect()->route('master.road_status');
		
	}

	public function afdeling(Request $request)
	{
            // $data = $request->session()->all();
			// dd($data);
		$access = AccessRight::roleaccess();
		$title = 'Afdeling list';
		$data['ctree'] = '/master/afdeling';
		$data["access"] = (object)$access['access'];
		return view('master.afdeling', compact('data','title'));
	}

	public function afdeling_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/road-status');
		$model = RoadStatus::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1 AS no, TM_ROAD_STATUS.*')->whereRaw('1=1');
		$update_action ="";
		$delete_action ="";

		if($access['update']==1){
			$update_action ='<button class="btn btn-link text-primary-600" onclick="edit({{ $id }}, \'{{ $status_name }}\', \'{{ $status_code }}\'); return false;">
								<i class="icon-pencil7"></i> Edit
							</button>';
		}
		if($access['delete']==1){
			$delete_action = '<a class="btn btn-link text-danger-600" href="" onclick="del(\''.URL::to('master/road-status-delete/{{ $id }}').'\'); return false;">
								<i class="icon-trash"></i> Hapus
							</a>';
		}

		return Datatables::eloquent($model)
			->addColumn('action', '<div class="text-center">'.
					$update_action.
					$delete_action.
					'<div>')
			->rawColumns(['action'])
			->make(true);

	}
	
	
}
