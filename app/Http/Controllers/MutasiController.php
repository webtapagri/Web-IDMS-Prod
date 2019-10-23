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
}
