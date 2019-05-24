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
        $data['ctree'] = 'mutasi';

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
        //echo "<pre>"; print_r($_POST); die();
        $request_date = $request->input('request_date');
        $kode_aset = $request->input('kode_aset');
        echo "$request_date <br/> <pre>"; print_r($kode_aset); die(); 
        /*
        23 May 2019
        Array
        (
            [0] => 121140300120_1_2
            [1] => 121140300120_3_4
        )

        */

        /*
            [request_date] => 23 May 2019
            [detail_kode_aset] => 121140300120
            [detail_milik_company] => 12
            [detail_milik_area] => 1211
            [detail_lokasi_company] => 52
            [detail_lokasi_area] => 5221
            [detail_tujuan_company] => 
            [detail_tujuan_area] => 
            [kode_aset] => Array
                (
                    [0] => 121140300120_2_3
                    [1] => 121140300120_1_2
                )
        */

        try {
            foreach($request as $k => $row) 
            {
                //echo "<pre>"; print_r($k);
                /*
                if($row["access_id"]) {
                    $data = RoleAccess::find( $row["access_id"]);
                    $data->updated_by = Session::get('user_id');
                } else {
                    $data = new RoleAccess();
                    $data->created_by = Session::get('user_id');
                }

                $data->role_id = $row["role_id"];
                $data->module_id = $row["module_id"];
                $data->menu_id = $row["menu_id"];
                $data->create = $row["create"];
                $data->read = $row["read"];
                $data->update = $row["update"];
                $data->delete = $row["remove"];
                $data->save();
                */

            }
            die();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }
}
