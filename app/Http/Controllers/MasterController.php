<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Master;
use App\Models\Company;
use App\Models\Estate;
use App\Models\Afdeling;
use App\Models\Block;
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
					->setEndpoint('afdeling/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
					
		// return $RestAPI;
		if(count($RestAPI['data']) > 0 ){
			foreach($RestAPI['data'] as $data){
				$est = Estate::where('estate_code',$data['EST_CODE'])->first();
					if($est){
						try {
								$afd = Afdeling::firstOrNew(array('estate_id' => $est['id'],'afdeling_code' => $data['AFD_CODE']));
								$afd->region_code = $data['REGION_CODE'];
								$afd->company_code = $data['COMP_CODE'];
								$afd->afdeling_name = $data['AFD_NAME'];
								$afd->werks = $data['WERKS'];
								$afd->werks_afd_code = $data['WERKS_AFD_CODE'];
								$afd->save();
						}catch (\Throwable $e) {
							//
						}catch (\Exception $e) {
							//
						}
					}else{
						// masuk log  COMP_CODE  not found
					}
				
			}
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
		if(count($RestAPI['data']) > 0 ){
			foreach($RestAPI['data'] as $data){

				$afd = Afdeling::where('afdeling_code',$data['AFD_CODE'])->first();
					if($afd){
						try {
								$block = Block::firstOrNew(array('afdeling_id' => $afd['id'],'block_code' => $data['BLOCK_CODE']));
								$block->block_name = $data['BLOCK_NAME'];
								$block->region_code = $data['REGION_CODE'];
								$block->company_code = $data['COMP_CODE'];
								$block->estate_code = $data['EST_CODE'];
								$block->werks = $data['WERKS'];
								$block->werks_afd_block_code = $data['WERKS_AFD_BLOCK_CODE'];
								$block->latitude_block = $data['LATITUDE_BLOCK'];
								$block->longitude_block = $data['LONGITUDE_BLOCK'];
								$block->save();
						}catch (\Throwable $e) {
							//
						}catch (\Exception $e) {
							//
						}
					}else{
						// masuk log  COMP_CODE  not found
					}
				
			}
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
		$access = access($request, 'master/company');
		$model = Company::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, TM_COMPANY.*')->whereRaw('1=1');
		
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}
	
	public function estate()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Estate';
		$data['ctree'] = '/master/estate';
		$data["access"] = (object)$access['access'];
		return view('master.estate', compact('data','title'));
	}
	
	public function estate_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/estate');
		$model = Estate::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, TM_ESTATE.*')->whereRaw('1=1');
		
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}
	
	public function afdeling()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Afdeling';
		$data['ctree'] = '/master/afdeling';
		$data["access"] = (object)$access['access'];
		return view('master.afdeling', compact('data','title'));
	}
	
	public function afdeling_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/afdeling');
		$model = Afdeling::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, TM_AFDELING.*')->whereRaw('1=1');
		
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}

	
	public function block()
	{
		$access = AccessRight::roleaccess();
		$title = 'Master Data Block';
		$data['ctree'] = '/master/block';
		$data["access"] = (object)$access['access'];
		return view('master.block', compact('data','title'));
	}
	
	public function block_datatables(Request $request)
	{
		$req = $request->all();
		$start = $req['start'];
		$access = access($request, 'master/block');
		$model = Block::selectRaw(' @rank  := ifnull(@rank, '.$start.')  + 1  AS no, TM_BLOCK.*')->whereRaw('1=1');
		
		
		return Datatables::eloquent($model)
			->rawColumns(['action'])
			->make(true);
	}
}
