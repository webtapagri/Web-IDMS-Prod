<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\TrUser;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
/* use NahidulHasan\Html2pdf\Facades\Pdf; */

class MutasiController extends Controller
{
    public function index()
    {
        /*  if (empty(Session::get('authenticated')))
            return redirect('/login');

        if (AccessRight::granted() == false)
            return response(view('errors.403'), 403);

        $access = AccessRight::access(); */

        $data['page_title'] = "Mutasi";
        $data['ctree_mod'] = 'Mutasi';
        $data['ctree'] = 'mutasi';

        return view('mutasi.index')->with(compact('data'));
    }

    public function dataGrid()
    {
        $data = array(
            array(
                "id" => '1',
                "mutasi_no" => '572120171023001',
                "mutasi_date" => '2019-01-02'
            ),
            array(
                "id" => '1',
                "mutasi_no" => '572120171023002',
                "mutasi_date" => '2019-01-02'
            )
           
        );

        $iTotalRecords = count($data);
        //$iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = intval(10);
        $iDisplayLength = $iDisplayLength < 0 ?$iTotalRecords : $iDisplayLength;
        //$iDisplayStart = intval($_REQUEST['start']);
        $iDisplayStart = intval(0);
        //$sEcho = intval($_REQUEST['draw']);
        $sEcho = intval(2);
        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ?$iTotalRecords : $end;

        for ($i = $iDisplayStart; $i < $end; $i++) {
            $records["data"][] =  $data[$i];
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return response()->json($records);
    }

    public function create(Request $request) 
    {
        $data["page_title"] = "Create Mutasi";
        $data['ctree_mod'] = 'Mutasi';
        $data['ctree'] = 'mutasi/create/1';

        $data['data'] = $this->get_data_temp("amp");

        $data['type'] = ($request->type == "amp" ? 'Melalui PO AMP':'Melalui PO Sendiri');
        return view('mutasi.add')->with(compact('data'));
    }

    public function convertToPdf() {
        $html2pdf = new Html2Pdf('L', 'A4', 'en');
        $html2pdf->writeHTML(view('assets.pdf'));
        $html2pdf->output("test.pdf", "D");    
        
    }

    public function show()
    {
        $param = $_REQUEST;
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tr_user/" . $param["id"]
        ));
        $data = $service;
        return response()->json(array('data' => $data->data));
    }

    public function store(Request $request)
    {
        $req = $request->all();
        $user_id = Session::get('user_id');

        //echo "1<pre>"; print_r($req); die();
        //echo "$request_date <br/> <pre>"; print_r($kode_aset); die(); 
        /*
        23 May 2019
        Array
        (
            [0] => 121140300120_1_2
            [1] => 121140300120_3_4
        )

        */

        /*
            1<pre>Array
            (
                [request_date] => 21 Oct 2019
                [detail_kode_aset] => 
                [detail_nama_asset] => 
                [detail_ac] => 
                [detail_milik_company] => 
                [detail_milik_area] => 
                [detail_lokasi_company] => 
                [detail_lokasi_area] => 
                [detail_tujuan_company] => 
                [detail_tujuan_area] => 
                [kode_aset] => Array
                (
                    [0] => 2160101060_21_2112_NC
                    [1] => 2110100017_21_2113_NC
                )

            )
        */

        DB::beginTransaction();

        try 
        {
            // INSERT HEADER
            $NO_REG = $this->get_reg_no();
            DB::INSERT(" INSERT INTO TR_MUTASI_ASSET (NO_REG,TYPE_TRANSAKSI,CREATED_BY) VALUES ('".$NO_REG."',1,'".$user_id."') ");

            // INSERT DETAIL
            foreach($req['kode_aset'] as $k => $v) 
            {
                $data = explode("_", $v);

                $KODE_ASSET_AMS = $data[0];
                $TUJUAN_COMPANY_CODE = $data[1];
                $TUJUAN_CODE = $data[2];
                $ASSET_CONTROLLER = $data[3];

                $validasi_store = $this->validasi_store($KODE_ASSET_AMS);

                if($validasi_store > 0)
                {
                    return array('status'=>false,'message'=> 'Proses Gagal, Data sudah pernah diinput (KODE ASSET AMS : '.$KODE_ASSET_AMS.')');
                }

                //echo $KODE_ASSET_AMS.'<br/>'.$TUJUAN_CODE.'<br/>'.$ASSET_CONTROLLER; 

                DB::INSERT(" INSERT INTO TR_MUTASI_ASSET_DETAIL (NO_REG,KODE_ASSET_AMS,TUJUAN,ASSET_CONTROLLER,CREATED_BY) VALUES ('".$NO_REG."','".$KODE_ASSET_AMS."','".$TUJUAN_CODE."','".$ASSET_CONTROLLER."','".$user_id."') ");

            }
            //die();

            DB::commit();

            return response()->json(['status' => true, "message" => 'Data is successfully created ('.$NO_REG.')']);
        } 
        catch (\Exception $e) 
        {
            DB::rollback();
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function dataGridAssetMutasi(Request $request)
    {
        //echo "<pre>"; print_r($request->all()); die();
        $role_id = Session::get('role_id');
        $user_id = Session::get('user_id');

        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";
        $addwhere = "";
        
        $field = array
        (
            array("index" => "1", "field" => "ASSET.KODE_ASSET_SAP", "alias" => "KODE_ASSET_SAP"),
            array("index" => "2", "field" => "ASSET.NAMA_MATERIAL ", "alias" => "NAMA_MATERIAL"),
            array("index" => "3", "field" => "ASSET.NAMA_ASSET", "alias" => "NAMA_ASSET"),
            array("index" => "4", "field" => "ASSET.BA_PEMILIK_ASSET", "alias" => "BA_PEMILIK_ASSET"),
            array("index" => "5", "field" => "ASSET.LOKASI_BA_DESCRIPTION", "alias" => "LOKASI_BA_DESCRIPTION"),
            array("index" => "6", "field" => "ASSET.ASSET_CONTROLLER", "alias" => "ASSET_CONTROLLER"),
            array("index" => "7", "field" => "ASSET.LOKASI_BA_CODE", "alias" => "LOKASI_BA_CODE")
        );

        foreach ($field as $row) 
        {
            if ($row["alias"]) {
                $selectedColumn[] = $row["field"] . " as " . $row["alias"];
            } else {
                $selectedColumn[] = $row["field"];
            }

            if ($row["index"] == $orderColumn) {
                $orderColumnName = $row["field"];
            }
        }

        // it@140619 JOIN W v_outstanding
        $sql = ' SELECT DISTINCT(ASSET.KODE_ASSET_AMS) AS KODE_ASSET_AMS '.implode(", ", $selectedColumn).'
            FROM TM_MSTR_ASSET AS ASSET 
            WHERE (DISPOSAL_FLAG IS NULL OR DISPOSAL_FLAG = "") AND (ASSET_CONTROLLER IS NOT NULL OR ASSET_CONTROLLER != "" ) ';

        /*if($role_id != 4)
            $sql .= " AND ASSET.CREATED_BY = '{$user_id}' ";*/ 

        if ($request->KODE_ASSET_AMS)
            $sql .= " AND ASSET.KODE_ASSET_AMS like '%" . $request->KODE_ASSET_AMS . "%'";
       
        if ($request->KODE_ASSET_SAP)
            $sql .= " AND ASSET.KODE_ASSET_SAP  like '%" . $request->KODE_ASSET_SAP . "%'";

        if ($request->NAMA_MATERIAL)
            $sql .= " AND ASSET.NAMA_MATERIAL  like '%" . $request->NAMA_MATERIAL . "%'";

        if ($request->NAMA_ASSET)
            $sql .= " AND ASSET.NAMA_ASSET  like '%".$request->NAMA_ASSET."%'";

        if ($request->BA_PEMILIK_ASSET)
            $sql .= " AND ASSET.BA_PEMILIK_ASSET  like '%".$request->BA_PEMILIK_ASSET."%'";

        if ($request->LOKASI_BA_DESCRIPTION)
            $sql .= " AND ASSET.LOKASI_BA_DESCRIPTION like '%".$request->LOKASI_BA_DESCRIPTION."%'" ;

        if ($request->ASSET_CONTROLLER)
            $sql .= " AND ASSET.ASSET_CONTROLLER like '%".$request->ASSET_CONTROLLER."%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $field[$orderColumn]['field'] . " " . $dirColumn;
        }
        else
        {
            $sql .= " ORDER BY ASSET.NAMA_ASSET ASC ";
        }

        $data = DB::select(DB::raw($sql));

        $iTotalRecords = count($data);
        $iDisplayLength = intval($request->length);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request->start);
        $sEcho = intval($request->draw);
        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        for ($i = $iDisplayStart; $i < $end; $i++) {
            $records["data"][] =  $data[$i];
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        return response()->json($records);
    }

    public function get_reg_no()
    {
        $sql = "SELECT count(*) AS total FROM TR_MUTASI_ASSET WHERE YEAR(CREATED_AT) = YEAR(CURDATE()) AND MONTH(CREATED_AT) = MONTH(curdate())";
        $data = DB::select($sql);
        $maxno = $data[0]->total+1;
        $year= date('y');
        $month = date('m');
        $year=$year.'.';
        $n=$maxno;
        $n = str_pad($n + 1, 5, 0, STR_PAD_LEFT);
        $number=$year.$month.'/AMS/MTSA/'.$n;
        return $number;
    }

    function validasi_store($KODE_ASSET_AMS)
    {
        $data = DB::SELECT(" SELECT COUNT(*) AS TOTAL FROM TR_MUTASI_ASSET_DETAIL WHERE KODE_ASSET_AMS = '".$KODE_ASSET_AMS."' ");
        return $data[0]->TOTAL;  
    }

    function list_file_category($kode_asset_ams,$jenis_pengajuan)
    {
        $result = array();
        $l = "";

        // IF MUTASI AMP
        if( $jenis_pengajuan == 'amp' )
        {
            $sql = " SELECT ID, DESCRIPTION_CODE, DESCRIPTION FROM TM_GENERAL_DATA WHERE GENERAL_CODE = 'ba_mutasi_upload' AND DESCRIPTION_CODE like 'amp' AND STATUS = 't' ";
        }
        else
        {
            //IF MUTASI NON AMP
            $sql = " SELECT ID, DESCRIPTION_CODE, DESCRIPTION FROM TM_GENERAL_DATA WHERE GENERAL_CODE = 'ba_mutasi_upload' AND DESCRIPTION_CODE = 'non amp' AND STATUS = 't' ";
        }

        $data = DB::SELECT($sql);
        
        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                $DESCRIPTION_CODE = str_replace(" ", "_", $v->DESCRIPTION);

                $l .= '<div class="form-group">
                            <label class="control-label col-xs-4" >'.strtoupper($v->DESCRIPTION).'</label>
                            <div class="col-xs-8">
                                <input type="file" class="form-control" id="'.$DESCRIPTION_CODE.'" name="'.$DESCRIPTION_CODE.'" value="" placeholder="Upload '.$v->DESCRIPTION.'"/>';
                            
                        $detail = DB::SELECT("SELECT * FROM TR_DISPOSAL_TEMP_FILE WHERE FILE_CATEGORY = '".$DESCRIPTION_CODE."' AND KODE_ASSET_AMS = '".$kode_asset_ams."' ");

                        if( !empty($detail) )
                        {
                            foreach( $detail as $kk => $vv )
                            {
                                $l .= '<span id="file-berkas-'.$vv->KODE_ASSET_AMS.'"><a href="'.url('disposal/view-berkas-detail/'.$vv->KODE_ASSET_AMS.'/'.$DESCRIPTION_CODE.'').'" target="_blank">'.$vv->FILE_NAME.'</a> <a href="#"><i class="fa fa-trash del-berkas" onClick="delete_berkas('.$vv->KODE_ASSET_AMS.',\''.$vv->FILE_CATEGORY.'\')"></i></a></span> ';    
                            }
                        }

                $l .= '</div>
                        </div>';
            }
        }

        echo $l; 
    }

    function upload_berkas_amp(Request $request)
    {
        $req = $request->all();
        echo "1<pre>"; print_r($req); die();

        //MULTIPLE UPLOAD
        $sql = " SELECT DESCRIPTION FROM TM_GENERAL_DATA WHERE GENERAL_CODE = 'ba_mutasi_upload' AND DESCRIPTION_CODE = 'amp' ";
        $data = DB::SELECT($sql);

        if( !empty($data) )
        {
            foreach($data as $k => $v)
            {
                $desc_code = str_replace(" ", "_", $v->DESCRIPTION);
                //echo "1<pre>"; print_r($v);
                $this->upload_multiple_berkas($req, $desc_code);
            }
            //die();
        }
        //END MULTIPLE UPLOAD
    }

    function upload_multiple_berkas($req, $desc_code)
    {
        //echo "1<pre>"; print_r($req); die();
        if( @$req['tipe'] == 2 )
        {
            $tipe = 'hilang';
        }
        else if( @$req['tipe'] == 3 )
        {
            $tipe = 'rusak';
        }else
        {
            $tipe = 'penjualan';
        }
        
        if( @$_FILES[''.$desc_code.'']['name'] != '')
        {
            $file_upload = base64_encode(file_get_contents(addslashes($_FILES[''.$desc_code.'']['tmp_name'])));
            $file_name = str_replace(" ", "_", $_FILES[''.$desc_code.'']['name']);
            $user_id = Session::get('user_id');
            $file_category = $desc_code;
            $file_category_label = strtoupper(str_replace("_", " ", $desc_code));

            // #1 VALIDASI SIZE DOC MAX 1 MB
            $max_docsize = 1000000;
            if( $_FILES[''.$desc_code.'']['size'] != 0 )
            {
                if( $_FILES[''.$desc_code.'']['size'] > $max_docsize )
                {
                    Session::flash('alert', 'Gagal upload '.$file_name.' ('.$file_category_label.'), ukuran file maksimal 1MB'); 
                    return Redirect::to('/disposal-'.$tipe.'');
                }
            }
            else
            {
                Session::flash('alert', 'Gagal upload '.$file_name.' ('.$file_category_label.'), ukuran file 0 MB'); 
                    return Redirect::to('/disposal-'.$tipe.'');
            }

            // #2 VALIDASI FILE UPLOAD EXIST
            $validasi_file_exist = $this->validasi_file_exist($req['kode_asset_ams'],$file_category);
            if( $validasi_file_exist == 0 )
            {
                $sql = "INSERT INTO TR_DISPOSAL_TEMP_FILE(
                            KODE_ASSET_AMS,
                            FILE_CATEGORY,
                            JENIS_FILE,
                            FILE_NAME,
                            DOC_SIZE,
                            JENIS_PENGAJUAN,
                            FILE_UPLOAD,
                            NOTES,
                            CREATED_BY)
                                VALUES('".$req['kode_asset_ams']."',
                            '{$desc_code}',
                            '".$_FILES[''.$desc_code.'']['type']."',
                            '{$file_name}',
                            '".$_FILES[''.$desc_code.'']['size']."',
                            '".$req['tipe']."',
                            '{$file_upload}',
                            '".$req['notes_asset']."',
                            '{$user_id}')";
            }
            else
            {
                $sql = "UPDATE TR_DISPOSAL_TEMP_FILE SET JENIS_FILE = '".$_FILES[''.$desc_code.'']['type']."', FILE_NAME = '{$file_name}', DOC_SIZE = '".$_FILES[''.$desc_code.'']['size']."', FILE_UPLOAD = '{$file_upload}', NOTES = '".$req['notes_asset']."', UPDATED_BY = '{$user_id}', UPDATED_AT = current_timestamp() WHERE KODE_ASSET_AMS = '".$req['kode_asset_ams']."' AND FILE_CATEGORY = '{$file_category}' ";
            }

            //echo $sql; die();

            DB::beginTransaction();

            try 
            {
                DB::insert($sql);
                DB::commit();
            } 
            catch (\Exception $e) 
            {
                DB::rollback();
            }
        }

        return true;
    }

    function add_temp(Request $request)
    {
        $req = $request->all();
        //echo "1<pre>"; print_r($req); die();
        /*
        1<pre>Array
            (
                [jenis] => amp
                [kode_asset_ams] => 5240100049
                [nama_aset] => BIN CONTAINER KAP. 5 TON
                [asset_controller] => HT
                [milik_company] => 52
                [milik_area] => 5221
                [lokasi_company] => 52
                [lokasi_area] => 5221-SLE ESTATE
                [tujuan_company] => 52
                [tujuan_area] => 5211
            )
        */

        $kode_asset_ams = $request->kode_asset_ams;
        $user_id = Session::get('user_id');
        $role_id = Session::get('role_id');
        $role_name = Session::get('role'); //get role id user
        $asset_controller = $request->asset_controller; //get asset controller
        $milik_area = $request->milik_area; 
    
        #1 VALIDASI ASSET CONTROLLER HARUS SAMA
        $validasi_asset_controller = $this->validasi_asset_controller($user_id,$asset_controller);
        if( $validasi_asset_controller == 0 )
        {
            return response()->json(['status' => false, "message" => "Asset Controller tidak sama"]);
        }    

        #2 VALIDASI KODE ASSET AMS TIDAK BOLEH SAMA (DOUBLE INPUT)
        $validasi_kode_asset_ams = $this->validasi_kode_asset_ams($user_id,$kode_asset_ams);
        if( $validasi_kode_asset_ams != 0 )
        {
            return response()->json(['status' => false, "message" => "Kode Asset AMS sudah diinput"]);
        }    

        #3 VALIDASI LOKASI BA CODE HARUS SAMA
        $validasi_milik_area = $this->validasi_milik_area($user_id,$milik_area);
        if( $validasi_milik_area == 0 )
        {
            return response()->json(['status' => false, "message" => "Kepemilikan Area tidak sama"]);
        }

        DB::beginTransaction();
            
        try 
        {
            DB::UPDATE(" INSERT INTO TR_MUTASI_TEMP (KODE_ASSET_AMS,NAMA_ASSET,ASSET_CONTROLLER,BA_PEMILIK_ASSET_COMPANY,BA_PEMILIK_ASSET,LOKASI_BA_CODE_COMPANY,LOKASI_BA_DESCRIPTION,TUJUAN_COMPANY,TUJUAN,JENIS_PENGAJUAN,CREATED_BY) VALUES ('{$kode_asset_ams}','{$request->nama_aset}','{$asset_controller}','{$request->milik_company}','{$request->milik_area}','{$request->lokasi_company}','{$request->lokasi_area}','{$request->tujuan_company}','{$request->tujuan_area}','{$request->jenis}','{$user_id}') "); 
            DB::commit();

            return response()->json(['status' => true, "message" => 'Data is successfully add ('.$kode_asset_ams.') ']);
        } 
        catch (\Exception $e) 
        {
            DB::rollback();
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function validasi_asset_controller($user_id,$asset_controller)
    {
        $data_temp = $this->get_data_temp("amp");
        //echo count($data_temp); die();

        if( count($data_temp) == 0 )
        {
            $dt = 1;
        }
        else
        {
            $sql = " SELECT COUNT(*) AS JML FROM TR_MUTASI_TEMP WHERE ASSET_CONTROLLER = '$asset_controller' AND CREATED_BY = '{$user_id}' ";
            $data = DB::SELECT($sql);
            
            if($data)
            { 
                $dt = $data[0]->JML; 
            }
            else
            { 
                $dt = 0; 
            }
        }

        
        return $dt;
    }

    public function get_data_temp($jenis)
    {
        $user_id = Session::get('user_id');
        $data = DB::SELECT(" SELECT * FROM TR_MUTASI_TEMP WHERE JENIS_PENGAJUAN = '{$jenis}' AND CREATED_BY = '{$user_id}' ");
        return $data;
    }

    function validasi_kode_asset_ams($user_id,$kode_asset_ams)
    {
        $data_temp = $this->get_data_temp("amp");

        if( count($data_temp) == 0 )
        {
            $dt = 0;
        }
        else
        {
            $sql = " SELECT COUNT(*) AS JML FROM TR_MUTASI_TEMP WHERE KODE_ASSET_AMS = '$kode_asset_ams' AND CREATED_BY = '{$user_id}' ";
            $data = DB::SELECT($sql);
            
            if($data)
            { 
                $dt = $data[0]->JML; 
            }
            else
            { 
                $dt = 0; 
            }
        }

        return $dt;
    }

    public function validasi_milik_area($user_id,$milik_area)
    {
        $data_temp = $this->get_data_temp("amp");
        //echo count($data_temp); die();

        if( count($data_temp) == 0 )
        {
            $dt = 1;
        }
        else
        {
            $sql = " SELECT COUNT(*) AS JML FROM TR_MUTASI_TEMP WHERE BA_PEMILIK_ASSET = '$milik_area' AND CREATED_BY = '{$user_id}' ";
            $data = DB::SELECT($sql);
            
            if($data)
            { 
                $dt = $data[0]->JML; 
            }
            else
            { 
                $dt = 0; 
            }
        }

        
        return $dt;
    }

}
