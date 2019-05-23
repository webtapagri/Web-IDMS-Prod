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
}
