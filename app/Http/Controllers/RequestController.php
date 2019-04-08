<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\TrUser;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
class RequestController extends Controller
{
    public function index()
    {
       /*  if (empty(Session::get('authenticated')))
            return redirect('/login');

        if (AccessRight::granted() == false)
            return response(view('errors.403'), 403);

        $access = AccessRight::access(); */
        return view('request.index')->with(compact('access'));
    }

    public function create(Request $request) {
        $type = ($request->type == "amp" ? 'Melalui PO AMP':'Melalui PO Sendiri');
        return view('assets.add')->with(compact('type'));
    }

    public function dataGrid() {
        $data = array(
            array(
                "id" => '1',
                "request_no" => '572120171023001',
                "request_date" => '2019-01-02',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '2',
                "request_no" => '572120171023002',
                "request_date" => '2019-01-02',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '3',
                "request_no" => '572120171023003',
                "request_date" => '2019-02-04',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '3',
                "request_no" => '572120171023004',
                "request_date" => '2019-01-03',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '4',
                "request_no" => '572120171023005',
                "request_date" => '2019-01-03',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
        );

        $iTotalRecords = count($data);
        //$iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = intval(10);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        //$iDisplayStart = intval($_REQUEST['start']);
        $iDisplayStart = intval(0);
        //$sEcho = intval($_REQUEST['draw']);
        $sEcho = intval(2);
        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        for($i = $iDisplayStart; $i < $end; $i++) {
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

    public function store(Request $request)
    {
       try {
            $param["username"] = $request->username;
            $param["nama"] = $request->name;
            $param["email"] = $request->email;
            $param["job_code"] = $request->job_code;
            $param["nik"] = $request->nik;
            $param["area_code"] = implode(',', $request->area_code);
            $param["fl_active"] = 1;

            
            if($request->edit_id) {
                $param["updated_at"] = date('Y-m-d H:i:s');
                $param["updated_by"] = Session::get('user');
                $data = API::exec(array(
                    'request' => 'PUT',
                    'method' => 'tr_user/' . $request->edit_id,
                    'data' => $param
                ));

                $res = $data;
                if ($res->code == '201') {
                    return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);;
                } else {
                    return response()->json(['status' => false, "message" => $res->message]);
                }
            } else {

                if($this->validateUsername($request->username)) {
                    $param["created_at"] = date('Y-m-d H:i:s');
                    $param["created_by"] = Session::get('user');
                    $data = API::exec(array(
                        'request' => 'POST',
                        'method' => 'tr_user',
                        'data' => $param
                    ));

                    $res = $data;
                    if ($res->code == '201') {
                        return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);;
                    } else {
                        return response()->json(['status' => false, "message" => $res->message]);
                    }
                } else{
                    return response()->json(['status' => false, "message" => 'Username <b>'. $request->username .'</b> already used by another user!']);
                }
               
            }
            
       } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
       }
    }

    public function validateUsername($username) {
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tr_user_profile/" . $username
        ));
        $profile = $service->data;    
        if($profile) {
            return false;
        } else {
            return true;
        }

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

    public function inactive(Request $request) {
        try {
            $param["updated_by"] = Session::get('user');
            $data = API::exec(array(
                'request' => 'ACTIVE',
                'method' => 'tr_user/' . $request->id . '/0',
                'data' => $param
            ));

            $res = $data;

            if ($res->code == '201') {
                return response()->json(['status' => true, "message" => 'Data is successfully inactived']);;
            } else {
                return response()->json(['status' => false, "message" => $res->message]);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }
   
    public function active(Request $request) {
        try {
            $param["updated_by"] = Session::get('user');
            $data = API::exec(array(
                'request' => 'ACTIVE',
                'method' => 'tr_user/' . $request->id . '/1',
                'data' => $param
            ));

            $res = $data;

            if ($res->code == '201') {
                return response()->json(['status' => true, "message" => 'Data is successfully inactived']);;
            } else {
                return response()->json(['status' => false, "message" => $res->message]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }
}
