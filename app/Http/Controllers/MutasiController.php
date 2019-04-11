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
        return view('mutasi.index')->with(compact('data'));
    }

    public function dataGrid()
    {
        $data = array(
            array(
                "id" => '1',
                "request_no" => '572120171023001',
                "request_date" => '2019-01-02',
                "request_category" => 'Penambahan',
                "request_type" => 'Melalui PO',
                "business_area" => '4141 - Gawi Mill',
                "business_area_location" => '4141 - Gawi Mill',
                "requestor" => 'dadang.kurniawan'
            ),
            array(
                "id" => '1',
                "request_no" => '572120171023002',
                "request_date" => '2019-01-02',
                "request_category" => 'Disposal',
                "request_type" => 'rusak',
                "business_area" => '4141 - Gawi Mill',
                "business_area_location" => '4141 - Gawi Mill',
                "requestor" => 'dadang.kurniawan'
            ),
            array(
                "id" => '1',
                "request_no" => '572120171023003',
                "request_date" => '2019-01-02',
                "request_category" => 'Penambahan',
                "request_type" => 'Reklasifikasi',
                "business_area" => '4141 - Gawi Mill',
                "business_area_location" => '4141 - Gawi Mill',
                "requestor" => 'dadang.kurniawan'
            ),
            array(
                "id" => '1',
                "request_no" => '572120171023004',
                "request_date" => '2019-01-02',
                "request_category" => 'Disposal',
                "request_type" => 'Hilang',
                "business_area" => '4141 - Gawi Mill',
                "business_area_location" => '4141 - Gawi Mill',
                "requestor" => 'dadang.kurniawan'
            ),
            array(
                "id" => '1',
                "request_no" => '572120171023005',
                "request_date" => '2019-01-02',
                "request_category" => 'Penambahan',
                "request_type" => 'Sewa',
                "business_area" => '4141 - Gawi Mill',
                "business_area_location" => '4141 - Gawi Mill',
                "requestor" => 'dadang.kurniawan'
            ),
           
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

    public function create(Request $request) {
        $type = ($request->type == "amp" ? 'Melalui PO AMP':'Melalui PO Sendiri');
        return view('assets.add')->with(compact('type'));
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
}
