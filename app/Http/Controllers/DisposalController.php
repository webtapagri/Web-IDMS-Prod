<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Cookie;
use App\TrUser;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
use App\TM_MSTR_ASSET;

class DisposalController extends Controller
{
	
	public function index()
    {	
		if(empty(Session::get('authenticated')))
        return redirect('/login');

		$access = AccessRight::access();
        $data['page_title'] = "Disposal";
        $data['ctree_mod'] = 'Disposal';
        $data['ctree'] = 'disposal-penjualan';

        $data['autocomplete'] = $this->get_autocomplete();
        $data['data'] = $this->get_data_cart(1);
		$data['totalcartnotif'] = array(); //$this->get_totalcartnotif();

        return view('disposal.index')->with(compact('data'));
    }
	
    function get_data_cart($jenis_pengajuan)
	{
		$user_id = Session::get('user_id');

		//$created_by = $u['username'];
		
		$sql = " SELECT * FROM TR_DISPOSAL_TEMP WHERE JENIS_PENGAJUAN = $jenis_pengajuan AND CREATED_BY = $user_id AND CHECKLIST = 0 "; //echo $sql; die();
		
		$dt = DB::SELECT($sql);
		//echo "<pre>"; print_r($dt); die();
		
		return $dt;
	}

	function get_autocomplete()
    {   
    	
    	$area_code = Session::get('area_code'); 
    	$where = "";
    	
    	if( $area_code != 'All' )
    	{
    		$where .= " AND BA_PEMILIK_ASSET in ($area_code) ";
    	}

    	$sql = " SELECT a.kode_asset_ams AS kode_asset_ams, a.kode_material AS kode_material, a.nama_material AS nama_material, a.nama_asset_1 AS nama_asset_1, a.kode_asset_sap AS kode_asset_sap, a.lokasi_ba_description, a.ba_pemilik_asset 
    				FROM TM_MSTR_ASSET a 
    					WHERE (a.kode_asset_ams IS NOT NULL OR a.kode_asset_ams != '') and (a.nama_material IS NOT NULL OR a.nama_material != '' ) AND (a.DISPOSAL_FLAG IS NULL OR a.DISPOSAL_FLAG = '' ) $where
    				ORDER BY a.nama_material ASC ";//echo $sql;
 		$data = DB::SELECT($sql); 

 		if($data)
 		{
 			$datax = '';
 			foreach( $data as $k => $v )
 			{
 				$kode_asset_ams = base64_encode($v->kode_asset_ams);
 				$datax .= "{id : '{$kode_asset_ams}',
 								name : '{$v->nama_material}  ',
 								asset : '{$v->nama_asset_1} ',
 								kode_asset_ams : '{$v->kode_asset_ams}',
 								lokasi_ba_description : '{$v->lokasi_ba_description}',
 								ba_pemilik_asset : '{$v->ba_pemilik_asset}'
 							},";
 			}
 		}
 		return rtrim($datax,',');
    }

    function add($id,$jenis_pengajuan)
    {
    	if(empty(Session::get('authenticated')))
        return redirect('/login');

    	//echo $jenis_pengajuan; die();

    	$user_id = Session::get('user_id');
		$kode_asset_ams = base64_decode($id);
		$row = TM_MSTR_ASSET::find($kode_asset_ams);

		$validasi_asset = $this->check_asset($kode_asset_ams,1);
		if( $validasi_asset > 0 )
		{
			Session::flash('alert', 'Data sudah di Disposal (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
			return Redirect::to('/disposal-penjualan');
			exit;
		}
		
		if( $row->count() > 0) 
		{
			//$data = array( 'NO_REG' => $row->NO_REG,'KODE_ASSET_SAP' => $row->kode_asset_sap);
			//$NO_REG = $row->NO_REG;

			DB::beginTransaction();

			try 
			{
				$sql = "INSERT INTO TR_DISPOSAL_TEMP(KODE_ASSET_AMS,KODE_ASSET_SAP,NAMA_MATERIAL,BA_PEMILIK_ASSET,LOKASI_BA_CODE,LOKASI_BA_DESCRIPTION,NAMA_ASSET_1,CREATED_BY,JENIS_PENGAJUAN,CHECKLIST)
							VALUES('{$row->KODE_ASSET_AMS}','{$row->KODE_ASSET_SAP}','{$row->NAMA_MATERIAL}','{$row->BA_PEMILIK_ASSET}','{$row->LOKASI_BA_CODE}','{$row->LOKASI_BA_DESCRIPTION}','{$row->NAMA_ASSET_1}','{$user_id}','{$jenis_pengajuan}',0)";
				//	echo $sql; die();
				DB::insert($sql);
				DB::commit();

				Session::flash('message', 'Success add data to Latest disposal! (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
				return Redirect::to('/disposal-penjualan');
			} 
			catch (\Exception $e) 
			{
				DB::rollback();
				Session::flash('message', $e->getMessage()); 
				return Redirect::to('/disposal-penjualan');
			}
		} 
		else 
		{
			Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('/disposal-penjualan');
        }
	}

	function remove($kode_asset_ams)
    {	
		
		DB::DELETE(" DELETE FROM TR_DISPOSAL_TEMP WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' ");
		
        //Cart::remove($rowid);
        return Redirect::to('/disposal-penjualan');
    }

    public function index_hilang()
    {
		if(empty(Session::get('authenticated')))
        return redirect('/login');

		$access = AccessRight::access();
        $data['page_title'] = "Disposal";
        $data['ctree_mod'] = 'Disposal';
        $data['ctree'] = 'disposal-hilang';

        $data['autocomplete'] = $this->get_autocomplete();
        $data['data'] = $this->get_data_cart(2);
		$data['totalcartnotif'] = array(); //$this->get_totalcartnotif();

        return view('disposal.index_hilang')->with(compact('data'));
    }

    function add_hilang($id,$jenis_pengajuan)
    {
    	if(empty(Session::get('authenticated')))
        return redirect('/login');

    	//echo $jenis_pengajuan; die();

    	$user_id = Session::get('user_id');
		$kode_asset_ams = base64_decode($id);

		$row = TM_MSTR_ASSET::find($kode_asset_ams);

		$validasi_asset = $this->check_asset($kode_asset_ams,2);
		if( $validasi_asset > 0 )
		{
			Session::flash('alert', 'Data sudah di Disposal (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
			return Redirect::to('/disposal-hilang');
			exit;
		}

		if( $row->count() > 0) 
		{
			//$data = array( 'NO_REG' => $row->NO_REG,'KODE_ASSET_SAP' => $row->kode_asset_sap);
			//$NO_REG = $row->NO_REG;

			DB::beginTransaction();

			try 
			{

				$HARGA_PEROLEHAN = $this->get_harga_perolehan($row);

				$sql = "INSERT INTO TR_DISPOSAL_TEMP(KODE_ASSET_AMS,KODE_ASSET_SAP,NAMA_MATERIAL,BA_PEMILIK_ASSET,LOKASI_BA_CODE,LOKASI_BA_DESCRIPTION,NAMA_ASSET_1,CREATED_BY,JENIS_PENGAJUAN,CHECKLIST,HARGA_PEROLEHAN)
							VALUES('{$row->KODE_ASSET_AMS}','{$row->KODE_ASSET_SAP}','{$row->NAMA_MATERIAL}','{$row->BA_PEMILIK_ASSET}','{$row->LOKASI_BA_CODE}','{$row->LOKASI_BA_DESCRIPTION}','{$row->NAMA_ASSET_1}','{$user_id}','{$jenis_pengajuan}',0,'{$HARGA_PEROLEHAN}')";
				//	echo $sql; die();
				DB::insert($sql);
				DB::commit();

				Session::flash('message', 'Success add data to Latest disposal! (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
				return Redirect::to('/disposal-hilang');
			} 
			catch (\Exception $e) 
			{
				DB::rollback();
				Session::flash('message', $e->getMessage()); 
				return Redirect::to('/disposal-hilang');
			}
		} 
		else 
		{
			Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('/disposal-hilang');
        }
	}

	function remove_hilang($kode_asset_ams)
    {	
		DB::DELETE(" DELETE FROM TR_DISPOSAL_TEMP WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' ");
        return Redirect::to('/disposal-hilang');
    }

    function check_asset($kode_asset_ams,$jenis_pengajuan)
    {
    	$total = 0;

    	// #1 VALIDASI DI DISPOSAL ASET DETAIL
    	$sql1 = "SELECT COUNT(*) AS TOTAL FROM TR_DISPOSAL_ASSET_DETAIL WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' AND DELETED != 'R' ";
    	$data = DB::SELECT($sql1);

    	if( $data[0]->TOTAL == 0)
    	{
    		// #2 JIKA ASET DETAIL NULL VALIDASI DI DISPOSAL ASET TEMP
    		$sql2 = "SELECT COUNT(*) AS TOTAL FROM TR_DISPOSAL_TEMP WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' ";
    		$dt = DB::SELECT($sql2);

    		$total = $dt[0]->TOTAL;
    	}
    	else
    	{
    		$total = $data[0]->TOTAL;
    	}

    	return $total;
    }

    public function index_rusak()
    {
		if(empty(Session::get('authenticated')))
        return redirect('/login');

		$access = AccessRight::access();
        $data['page_title'] = "Disposal";
        $data['ctree_mod'] = 'Disposal';
        $data['ctree'] = 'disposal-rusak';

        $data['autocomplete'] = $this->get_autocomplete();
        $data['data'] = $this->get_data_cart(3);
		$data['totalcartnotif'] = array(); //$this->get_totalcartnotif();

        return view('disposal.index_rusak')->with(compact('data'));
    }

	
	function add_rusak($id,$jenis_pengajuan)
    {
    	if(empty(Session::get('authenticated')))
        return redirect('/login');

    	//echo $jenis_pengajuan; die();

    	$user_id = Session::get('user_id');
		$kode_asset_ams = base64_decode($id);

		$row = TM_MSTR_ASSET::find($kode_asset_ams);

		$validasi_asset = $this->check_asset($kode_asset_ams,3);

		if( $validasi_asset > 0 )
		{
			Session::flash('alert', 'Data sudah di Disposal (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
			return Redirect::to('/disposal-rusak');
			exit;
		}

		if( $row->count() > 0) 
		{
			//$data = array( 'NO_REG' => $row->NO_REG,'KODE_ASSET_SAP' => $row->kode_asset_sap);
			//$NO_REG = $row->NO_REG;

			DB::beginTransaction();

			try 
			{

				$sql = "INSERT INTO TR_DISPOSAL_TEMP(KODE_ASSET_AMS,KODE_ASSET_SAP,NAMA_MATERIAL,BA_PEMILIK_ASSET,LOKASI_BA_CODE,LOKASI_BA_DESCRIPTION,NAMA_ASSET_1,CREATED_BY,JENIS_PENGAJUAN,CHECKLIST)
							VALUES('{$row->KODE_ASSET_AMS}','{$row->KODE_ASSET_SAP}','{$row->NAMA_MATERIAL}','{$row->BA_PEMILIK_ASSET}','{$row->LOKASI_BA_CODE}','{$row->LOKASI_BA_DESCRIPTION}','{$row->NAMA_ASSET_1}','{$user_id}','{$jenis_pengajuan}',0)";
				//	echo $sql; die();
				DB::insert($sql);
				DB::commit();

				Session::flash('message', 'Success add data to Latest disposal! (KODE AMS : '.$row->KODE_ASSET_AMS.') ');
				return Redirect::to('/disposal-rusak');
			} 
			catch (\Exception $e) 
			{
				DB::rollback();
				Session::flash('message', $e->getMessage()); 
				return Redirect::to('/disposal-rusak');
			}
		} 
		else 
		{
			Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('/disposal-rusak');
        }
	}

	function proses(Request $request,$jenis)
	{
		if( $jenis == 1 ){ $jp = 'penjualan'; $menu_code = 'D3'; }
		elseif($jenis==2){ $jp = 'hilang'; $menu_code = 'D1'; }
		else{ $jp = 'rusak'; $menu_code = 'D2'; }

		$req = $request->all();
		$user_id = Session::get('user_id');
		$reg_no = $this->get_reg_no();

		$sql = " SELECT * FROM TR_DISPOSAL_TEMP WHERE JENIS_PENGAJUAN = $jenis AND CREATED_BY = $user_id AND CHECKLIST = 0 ";
		$data = DB::SELECT($sql);

		if(!empty($data))
		{
			DB::beginTransaction();
			try 
       		{
       			$vhp_plus = array();

       			if($data[0]->HARGA_PEROLEHAN >= 50000000){$tipe = 'plus';}
       			else{$tipe = 'minus';}

       			$lokasi_ba_code = $data[0]->LOKASI_BA_CODE;

       			$ac_awal = $this->get_ac_awal($data[0]->KODE_ASSET_AMS);
       			if( $ac_awal == "not found" )
       			{ 	
	           	 	Session::flash('alert', 'Asset Controller not found (KODE ASSET AMS : '.$data[0]->KODE_ASSET_AMS.' )');
					return Redirect::to('/disposal-'.$jp.''); 
				}

				foreach($data as $k => $v)
				{
					// #1 VALIDASI HARGA PEROLEHAN
					$vhp = $this->validasi_harga_perolehan($v,$tipe);
					if( $vhp['result'] != 1 )
					{
						DB::rollback(); 
						DB::commit();
	           	 		Session::flash('alert',$vhp['message']);
						return Redirect::to('/disposal-'.$jp.'');
						exit;
					}

					// #2 VALIDASI SATU LOKASI BA CODE
					$vlb = $this->validasi_lokasi_bacode($v,$lokasi_ba_code);
					if( $vlb['result'] != 1 )
					{
						DB::rollback();
						DB::commit();
	           	 		
	           	 		Session::flash('alert',$vlb['message']);
						return Redirect::to('/disposal-'.$jp.'');
						exit;
					}

					// #3 VALIDASI SAMA ASSET CONTROLLER
					$vac = $this->validasi_asset_controller($v->KODE_ASSET_AMS, $ac_awal); 
					if( $vac['result'] != 1 )
					{
						DB::rollback();DB::commit();
	           	 		Session::flash('alert',$vac['message']);
						return Redirect::to('/disposal-'.$jp.'');
						exit;
					}
					
				}

				foreach($data as $k => $v)
				{
					DB::table('TR_DISPOSAL_ASSET_DETAIL')->insertGetId([
                        "NO_REG" => $reg_no,
                        "KODE_ASSET_AMS" => $v->KODE_ASSET_AMS,
                        "KODE_ASSET_SAP" => $v->KODE_ASSET_SAP,
                        "NAMA_MATERIAL" => $v->NAMA_MATERIAL,
                        "BA_PEMILIK_ASSET" => $v->BA_PEMILIK_ASSET,
                        "LOKASI_BA_CODE" => $v->LOKASI_BA_CODE,
                        "LOKASI_BA_DESCRIPTION" => $v->LOKASI_BA_DESCRIPTION,
                        "NAMA_ASSET_1" => $v->NAMA_ASSET_1,
                        "HARGA_PEROLEHAN" => $v->HARGA_PEROLEHAN,
                        "JENIS_PENGAJUAN" => $v->JENIS_PENGAJUAN,
                        "CREATED_BY" => Session::get('user_id'),
                    ]);
				}

				//echo $ac_awal; die();

				DB::STATEMENT('call create_approval("'.$menu_code.'", "'.$data[0]->LOKASI_BA_CODE.'","","'.$reg_no.'","'.$user_id.'","'.$ac_awal.'","0")');

				$asset_id = DB::table('TR_DISPOSAL_ASSET')->insertGetId([
	                "CREATED_BY" => Session::get('user_id'),
	                "NO_REG" => $reg_no,
	                "TYPE_TRANSAKSI" => $jp,
	                "BUSINESS_AREA" => $data[0]->LOKASI_BA_CODE,
	                "TANGGAL_REG" => date("Y-m-d")
	            ]);

	            DB::DELETE(" DELETE FROM TR_DISPOSAL_TEMP WHERE JENIS_PENGAJUAN = $jenis AND CREATED_BY = $user_id AND CHECKLIST = 0 ");

				DB::commit();

				Session::flash('message', 'Proses sukses (NO REG : '.$reg_no.' ) ');
				return Redirect::to('/disposal-'.$jp.'');
			}
			catch (\Exception $e) 
			{
	            DB::rollback();
	            Session::flash('alert', $e->getMessage());
	            return Redirect::to('/disposal-'.$jp.'');
	       }
		}
		else
		{
			Session::flash('alert', 'Proses failed!');
			return Redirect::to('/disposal-'.$jp.'');
		}
	}

	public function get_reg_no()
    {
        $sql = "SELECT count(*) AS total FROM TR_DISPOSAL_ASSET WHERE YEAR(tanggal_reg) = YEAR(CURDATE()) AND MONTH(tanggal_reg) = MONTH(curdate())";
        $data = DB::select($sql);
        $maxno = $data[0]->total+1;
        $year= date('y');
        $month = date('m');
        $year=$year.'.';
        $n=$maxno;
        $n = str_pad($n + 1, 5, 0, STR_PAD_LEFT);
        $number=$year.$month.'/AMS/DSPA/'.$n;
        return $number;
    }

    public function update_harga_perolehan(Request $request)
    {
		DB::beginTransaction();
		try 
   		{
			DB::UPDATE(' UPDATE TR_DISPOSAL_TEMP SET HARGA_PEROLEHAN = '.$request->harga_perolehan.' WHERE KODE_ASSET_AMS = '.$request->kode_asset_ams.' ');

			$result = 1;
		}
		catch (\Exception $e) 
		{
            DB::rollback();
            $result = 0;
       	}

		if( $result == 1 )
		{
			Session::flash('message', 'Updated success ('.$request->kode_asset_ams.' - '.$request->nama_asset.') ');
			
			if( $request->tipe == 1 )
			{
				return Redirect::to('/disposal-penjualan');
			}
			elseif( $request->tipe == 2 )
			{
				return Redirect::to('/disposal-hilang');
			}
			else
			{
				return Redirect::to('/disposal-rusak');
			}
			
		}
		else
		{
			Session::flash('alert', 'Updated failed!');
			
			if( $request->tipe == 1 )
			{
				return Redirect::to('/disposal-penjualan');
			}
			elseif( $request->tipe == 2 )
			{
				return Redirect::to('/disposal-hilang');
			}
			else
			{
				return Redirect::to('/disposal-rusak');
			}

		}
	
    }

    function validasi_harga_perolehan($nilai,$jenis)
    {
    	$hp_default = 50000000;
    	$nilai_temp_plus = array();
    	$nilai_temp_minus = array();

    	if($nilai->HARGA_PEROLEHAN == 0)
    	{
    		$result = array('result'=> 0, 'message'=> 'Gagal Proses, Harga Perolehan tidak boleh kosong (0) (Rp. '.number_format($nilai->HARGA_PEROLEHAN,0,',','.').' / KODE ASSET AMS : '.$nilai->KODE_ASSET_AMS.' - '.$nilai->NAMA_ASSET_1.' ) ');
	    		return $result;
    	}

    	if($jenis == 'plus')
    	{
    		if($nilai->HARGA_PEROLEHAN >= $hp_default)
	    	{
	    		//array_push($nilai_temp_plus, $nilai);
	    		$nilai_temp_plus[] = $nilai->HARGA_PEROLEHAN;
	    		$result = array('result'=> 1, 'message'=> $nilai_temp_plus);
	    		return $result;
	    	}
	    	else
	    	{
	    		$result = array('result'=> 0, 'message'=> 'Gagal Proses, Harga Perolehan dibawah Rp. 50 juta (Rp. '.number_format($nilai->HARGA_PEROLEHAN,0,',','.').' / KODE ASSET AMS : '.$nilai->KODE_ASSET_AMS.' - '.$nilai->NAMA_ASSET_1.' ) ');
	    		return $result;
	    	}
    	}
    	else
    	{
    		if($nilai->HARGA_PEROLEHAN < $hp_default)
	    	{
	    		//array_push($nilai_temp_plus, $nilai);
	    		$nilai_temp_minus[] = $nilai->HARGA_PEROLEHAN;
	    		$result = array('result'=> 1, 'message'=> $nilai_temp_minus);
	    		return $result;
	    	}
	    	else
	    	{
	    		$result = array('result'=> 0, 'message'=> 'Gagal Proses, Harga Perolehan diatas Rp. 50 juta (Rp. '.number_format($nilai->HARGA_PEROLEHAN,0,',','.').' / KODE ASSET AMS : '.$nilai->KODE_ASSET_AMS.' - '.$nilai->NAMA_ASSET_1.' ) ');
	    		return $result;
	    	}
    	}
    }

    function validasi_lokasi_bacode($nilai,$lokasi_awal)
    {
    	//echo $lokasi_awal; die();

    	if( $lokasi_awal == $nilai->LOKASI_BA_CODE )
    	{
    		$result = array('result'=> 1, 'message'=> "success validasi_lokasi_bacode");
	    	return $result;
    	}
    	else
    	{
    		$result = array('result'=> 0, 'message'=> 'Gagal Proses, Lokasi BA Code tidak sama (KODE ASSET AMS : '.$nilai->KODE_ASSET_AMS.' - '.$nilai->NAMA_ASSET_1.' ) ');
	    		return $result;
    	}
    }

    function remove_rusak($kode_asset_ams)
    {	
		DB::DELETE(" DELETE FROM TR_DISPOSAL_TEMP WHERE KODE_ASSET_AMS = '{$kode_asset_ams}' ");
        return Redirect::to('/disposal-rusak');
    }

    function get_ac_awal($kode_asset_ams)
    {
    	$sql = " SELECT a.ASSET_CONTROLLER FROM TM_MSTR_ASSET a WHERE a.KODE_ASSET_AMS = '".$kode_asset_ams."' ";
    	$data = DB::SELECT($sql);
    	//echo "<pre>"; print_r($data); die();
    	if(!empty($data))
    	{
    		$ac = $data[0]->ASSET_CONTROLLER;
    	}
    	else
    	{
    		$ac = "not found";
    	}

    	//echo "2<br/>".$ac; die();

    	return $ac;
    }

    function validasi_asset_controller($kode_asset_ams,$ac_awal)
    {
    	$ac = $this->get_ac_awal($kode_asset_ams);

    	if( $ac == 'not found' )
    	{
    		$result = array('result'=> 0, 'message'=> 'Asset Controller not found (KODE ASSET AMS : '.$kode_asset_ams.' )');
	    	return $result;
    	}
    	else if( $ac == $ac_awal )
    	{
    		$result = array('result'=> 1, 'message'=> "success validasi_asset_controller");
	    	return $result;
    	}
    	else
    	{
    		$result = array('result'=> 0, 'message'=> 'Gagal Proses, AC Controller tidak sama (KODE ASSET AMS : '.$kode_asset_ams.' ) ');
	    	return $result;
    	}
    }

    function get_harga_perolehan($row)
    {
    	
    	$BUKRS = substr($row->BA_PEMILIK_ASSET,0,2);
    	$YEAR = date('Y');

    	$ANLN1 = $this->get_anln1($row->KODE_ASSET_SAP);
    	
    	if( $row->KODE_ASSET_SUBNO_SAP == '') 
    	{
    		$ANLN2 = '0000';
    	}
    	else
    	{
    		$ANLN2 = $row->KODE_ASSET_SUBNO_SAP;
    	}

    	$service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "assets_price?BUKRS={$BUKRS}&ANLN1={$ANLN1}&ANLN2=$ANLN2&AFABE=15&GJAHR={$YEAR}", 
            //'method' => "assets_price?BUKRS=41&ANLN1=000060100612&ANLN2=0000&AFABE=1&GJAHR=2019", 
            //http://tap-ldapdev.tap-agri.com/data-sap/assets_price?BUKRS=41&ANLN1=000060100612&ANLN2=0000&AFABE=1&GJAHR=2019
        ));
        
        $data = $service;

        if(!empty($data))
        {
        	$nilai = $data;
        }
        else
        {
        	$nilai = 0;
        }

        return $nilai*100;
    }

    function get_anln1($kode)
    {
    	$total = strlen($kode); //12 DIGIT

    	if( $total == 8 )
    	{
    		$ksap = '0000'.$kode.'';
    	}
    	elseif( $total == 7 )
    	{
    		$ksap = '00000'.$kode.'';
    	}
    	else
    	{
    		$ksap = '0000'.$kode.'';
    	}
    	return $ksap;
    }
}

?>