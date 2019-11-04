<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\FamsEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\TR_REG_ASSET_DETAIL;
use API;

class FamsEmailController extends Controller
{

	public function index(Request $request)
	{
		$req = $request->all();
		$no_registrasi = $req['noreg'];
		$document_code = str_replace("-", "/", $no_registrasi); 
		$jenis_document = "";
		
		if (strpos($document_code, 'PDFA') !== false) 
		{
			$jenis_document = 'PENDAFTARAN';
		}
		else if (strpos($document_code, 'DSPA') !== false) 
		{ 
			$jenis_document = "DISPOSAL";}
		else
		{
			$jenis_document = "MUTASI";
		}
	
		// 1. DATA ASSET
		$sql = " SELECT distinct(a.document_code) as document_code, a.KODE_MATERIAL, a.NAMA_MATERIAL, a.LOKASI_BA_CODE, a.PO_TYPE, a.NO_PO, a.BA_PEMILIK_ASSET, b.DESCRIPTION as LOKASI_BA_CODE_DESC, c.DESCRIPTION as BA_PEMILIK_ASSET_DESC   
					FROM v_email_approval a 
					LEFT JOIN TM_GENERAL_DATA b ON a.LOKASI_BA_CODE = b.DESCRIPTION_CODE AND b.GENERAL_CODE = 'PLANT'
					LEFT JOIN TM_GENERAL_DATA c ON a.BA_PEMILIK_ASSET = c.DESCRIPTION_CODE AND c.GENERAL_CODE = 'PLANT'
					WHERE a.document_code = '{$document_code}'
					order by a.nama_material ";
		$dt = DB::SELECT($sql);

		// 2. HISTORY APPROVAL 
		$sql2 = " SELECT a.*, a.date AS date_create FROM v_history a WHERE a.document_code = '{$document_code}' ORDER BY date_create ";
		$dt_history_approval = DB::SELECT($sql2);

		// 3. EMAIL TO
		$data = new \stdClass();
        $data->noreg = array($document_code,1,2);
        $data->jenis_pemberitahuan = $jenis_document;
        $data->sender = 'TAP Agri';
        $data->datax = $dt;
        $data->history_approval = $dt_history_approval;

		$sql3 = " SELECT b.name, b.email FROM v_history_approval a LEFT JOIN TBM_USER 
b ON a.USER_ID = b.ID WHERE a.document_code = '{$document_code}' AND status_approval = 'menunggu' "; //echo $sql3; die();
		$dt_email_to = DB::SELECT($sql3);
		
		#1 IT@220719 
		if(!empty($dt_email_to))
		{
			foreach($dt_email_to as $k => $v)
			{
				$data->nama_lengkap = $v->name;
				Mail::to($v->email)
					->bcc('system.administrator@tap-agri.com')
					->send(new FamsEmail($data));
			}
		}
	}

	public function showToken()
	{
      echo csrf_token(); 
    }

}