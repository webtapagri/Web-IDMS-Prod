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
		
		foreach($RestAPI['data'] as $data){
		
			$comp = Company::firstOrNew(array('region_code' => $data['REGION_CODE'],'company_code' => $data['COMP_CODE']));
			$comp->company_name = $data['COMP_NAME'];
			$comp->address = $data['ADDRESS'];
			$comp->national = $data['NATIONAL'];
			$comp->save();
			
		}
					
		return 1;
	}
	
	
	
}
