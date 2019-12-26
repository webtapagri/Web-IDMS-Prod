<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Master;
use App\Models\Company;
use App\Models\Estate;
use AccessRight;
use Yajra\DataTables\Facades\DataTables;

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
					->setEndpoint('comp/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
					
		return $RestAPI;
		
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
		$jml = count($RestAPI['data']);
		if($jml > 0 ){
			foreach($RestAPI['data'] as $data){
				try {
					$comp = Company::firstOrNew(array('region_code' => $data['REGION_CODE'],'company_code' => $data['COMP_CODE']));
					$comp->company_name = $data['COMP_NAME'];
					$comp->address = $data['ADDRESS'];
					$comp->national = $data['NATIONAL'];
					$comp->save();
				}catch (\Throwable $e) {
					//
				}catch (\Exception $e) {
					//
				}
			}
				
		}
					
		return response()->success('Success', $jml);
	}
	
	public function sync_est()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('est/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
		$jml = count($RestAPI['data']);
		if($jml > 0){
			foreach($RestAPI['data'] as $data){
				
				$comp = Company::where('company_code',$data['COMP_CODE'])->first();
				
				if($comp){
					try {
						$est = Estate::firstOrNew(array('company_id' => $comp['id'],'estate_code' => $data['EST_CODE']));
						$est->estate_name 	= $data['EST_NAME'];
						$est->werks 		= $data['WERKS'];
						$est->city 			= $data['CITY'];
						$est->save();
					}catch (\Throwable $e) {
						//
					}catch (\Exception $e) {
						//
					}
				}else{
					// masuk log  COMP_CODE  not found
				}
				
			}
				
		}else{
			//
		}		
		
		return response()->success('Success', $jml);
		
	}
	
	public function company()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Company';
		$data['ctree'] = '/master/company';
		$data["access"] = (object)$access['access'];
		return view('master.company', compact('data','title'));
	}
	
	public function company_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/road-category');
		$model = Company::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, TM_COMPANY.*')->whereRaw('1=1');
		
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}
	
}
