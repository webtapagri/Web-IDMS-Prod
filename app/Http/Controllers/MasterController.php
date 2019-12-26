<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Master;
use App\Models\Company;

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
		
		foreach($RestAPI['data'] as $data){
			
			$afd = Afdeling::firstOrNew(array('region_code' => $data['REGION_CODE'],'company_code' => $data['COMP_CODE'],'afdeling_code' => $data['AFD_CODE']));
			$afd->afdeling_name = $data['AFD_NAME'];
			$afd->werks = $data['WERKS'];
			$afd->werks_afd_code = $data['WERKS_AFD_CODE'];
			$afd->save();
			
		}
					
		return 1;
		
	}

	public function sync_block()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('block/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
					
		// return $RestAPI;
		
		foreach($RestAPI['data'] as $data){
			
			$afd = Block::firstOrNew(array('region_code' => $data['REGION_CODE'],'company_code' => $data['COMP_CODE'],'afdeling_code' => $data['AFD_CODE'],'block_code' => $data['BLOCK_CODE']));
			$afd->block_name = $data['BLOCK_NAME'];
			$afd->werks = $data['WERKS'];
			$afd->werks_afd_block_code = $data['WERKS_AFD_BLOCK_CODE'];
			$afd->latitude_block = $data['LATITUDE_BLOCK'];
			$afd->longitude_block = $data['LONGITUDE_BLOCK'];
			$afd->save();
			
		}
					
		return 1;
		
	}


	public function sync_comp()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('comp/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
		
		foreach($RestAPI['data'] as $data){
		
			$comp = Company::firstOrNew(array('region_code' => $data['REGION_CODE'],'company_code' => $data['COMP_CODE']));
			$comp->company_name = $data['COMP_NAME'];
			$comp->address = $data['ADDRESS'];
			$comp->national = $data['NATIONAL'];
			$comp->save();
			
		}
					
		return 1;
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
