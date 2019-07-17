<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\FamsEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\TR_REG_ASSET_DETAIL;

class FamsEmailController extends Controller
{

	public function index(Request $request)
	{
		$req = $request->all();
		$no_registrasi = $req['noreg'];
		$document_code = str_replace("-", "/", $no_registrasi); 
		//echo $document_code; die();
	
		// 1. DATA ASSET 
		$sql = " SELECT * FROM V_EMAIL_APPROVAL WHERE DOCUMENT_CODE = '{$document_code}' ";
		$dt = DB::SELECT($sql);

		// 2. HISTORY APPROVAL 
		$sql2 = " SELECT * FROM v_history WHERE DOCUMENT_CODE = '{$document_code}' ";
		$dt_history_approval = DB::SELECT($sql2);

		// 3. EMAIL TO
		$sql3 = " SELECT b.email FROM v_history_approval a LEFT JOIN TBM_USER 
b ON a.USER_ID = b.ID WHERE a.document_code = '{$document_code}' AND status_approval = 'menunggu' "; //echo $sql3; die();
		$dt_email_to = DB::SELECT($sql3);
		//echo "2<pre>"; print_r($dt_email_to); die();
		if(!empty($dt_email_to))
		{
			$to = "";
			$cc = "";
			foreach($dt_email_to as $k => $v)
			{
				if($k==0)
				{
					$to = $v->email;	
				}
				else
				{
					$cc = $v->email.",";
				}
			}
		}
		else
		{
			$to = "no-reply@tap-agri.co.id";
			$cc = "no-reply@tap-agri.co.id,no-reply@tap-agri.co.id";
		}

		$data = new \stdClass();
        $data->noreg = array($document_code,1,2);
        $data->jenis_pemberitahuan = 'PENDAFTARAN';
        $data->sender = 'TAP Agri';
        //$data->content = $content;
        $data->datax = $dt;
        $data->history_approval = $dt_history_approval;
        
        //$to = "irvan27@gmail.com";
        //$to = "irvan.tazrian@tap-agri.co.id";
        //$cc = "irvan27@yahoo.co.id, me@vanrayen.com";     

        if($cc != "")
        {
        	Mail::to($to)
        		//->cc(rtrim($cc,","))
        		->send(new FamsEmail($data));
        }
        else
        {
        	Mail::to($to)->send(new FamsEmail($data));
        }
        
	}

}