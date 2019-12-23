<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Master

class MasterController extends Controller
{
    
	public function sync_afd()
	{
		$Master = new Master;
		$token = $Master->token();
		$RestAPI = $Master
					->setEndpoint('http://app.tap-agri.com/mobileinspection/ins-msa-hectarestatement/afdeling/all')
					->setHeaders([
						'Authorization' => 'Bearer '.$token
					])
					->get();
		dd($RestAPI);
		
	}
	
}
