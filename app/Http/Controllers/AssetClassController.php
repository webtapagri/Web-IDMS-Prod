<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
use App\Workflow;
use App\TR_WORKFLOW_DETAIL;
use App\TR_WORKFLOW_JOB;
use App\TM_JENIS_ASSET;
use App\TM_ASSET_CONTROLLER_MAP;
use App\TM_GROUP_ASSET;
use App\TM_SUBGROUP_ASSET;

class AssetClassController extends Controller
{

    public function index()
    {
        //echo "Module Asset Class"; die();
        if (empty(Session::get('authenticated')))
            return redirect('/login');

        if (AccessRight::granted() === false) {
            $data['page_title'] = 'Oops! Unauthorized.';
            return response(view('errors.403')->with(compact('data')), 403);
        }
        
        $access = AccessRight::access();    
        $data['page_title'] = 'Asset Class';
        $data['ctree_mod'] = 'Setting';
        $data['ctree'] = 'setting/asset-class';
        $data["access"] = (object)$access;
        return view('usersetting.asset_class')->with(compact('data'));
    }

    public function dataGrid(Request $request)
    {
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['id','jenis_asset_code', 'jenis_asset_description'];

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = '
            SELECT ' . implode(", ", $selectedColumn) . '
                FROM TM_JENIS_ASSET
                WHERE 1=1
        ';


        if ($request->jenis_asset_code)
        $sql .= " AND jenis_asset_code like'%" . $request->jenis_asset_code . "%'";


        if ($request->jenis_asset_description)
        $sql .= " AND jenis_asset_description like'%" . $request->jenis_asset_description . "%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderBy . " " . $dirColumn;
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

    public function store(Request $request)
    {
        try 
        {
            if ( $request->edit_id != "" ) 
            {
                $data = TM_JENIS_ASSET::find($request->edit_id);
                //$data->updated_by = Session::get('user_id');

                $sql = "
                    UPDATE TM_JENIS_ASSET SET jenis_asset_code = '".$request->jenis_asset_code."', jenis_asset_description = '".$request->jenis_asset_description."' WHERE id = ".$request->edit_id."
                ";
                DB::UPDATE($sql);

            } else {
                $data = new TM_JENIS_ASSET();
                //$data->created_by = Session::get('user_id');
                $data->jenis_asset_code = $request->jenis_asset_code;
                $data->jenis_asset_description = $request->jenis_asset_description;
                $data->save();
            }

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function store_group_asset(Request $request)
    {
        try 
        {
            //$asset_ctrl_description = $this->get_asset_ctrl_description($request->acm_asset_ctrl_code);
            //$new_map_code = $request->acm_jenis_asset_code.$request->acm_group_code.$request->acm_subgroup_code.$request->acm_asset_ctrl_code;
            //echo "====".$asset_ctrl_description; die();

            if ( $request->edit_ga_id != "" ) 
            {
                //$data = TM_GROUP_ASSET::find($request->edit_ga_id);
                $sql = "UPDATE TM_GROUP_ASSET SET group_description = '".$request->ga_group_description."' WHERE id = ".$request->edit_ga_id."";
                DB::UPDATE($sql);
            } 
            else 
            {

                $data = new TM_GROUP_ASSET();
                $data->group_code = $request->ga_group_code;
                $data->group_description = $request->ga_group_description;
                $data->jenis_asset_code = $request->edit_ga_jenis_asset_code;
                $data->save();
            }

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_ga_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function store_subgroup_asset(Request $request)
    {
        try 
        {
            if ( $request->edit_sgc_id != "" ) 
            {
                $sql = "UPDATE TM_SUBGROUP_ASSET SET subgroup_description = '".$request->sgc_subgroup_description."' WHERE id = ".$request->edit_sgc_id."";
                DB::UPDATE($sql);
            } 
            else 
            {

                $data = new TM_SUBGROUP_ASSET();
                $data->subgroup_code = $request->sgc_subgroup_code;
                $data->subgroup_description = $request->sgc_subgroup_description;
                $data->group_code = $request->edit_sgc_group_code;
                $data->save();
            }

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_sgc_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function store_asset_map(Request $request)
    {
        try 
        {
            $asset_ctrl_description = $this->get_asset_ctrl_description($request->acm_asset_ctrl_code);
            $new_map_code = $request->acm_jenis_asset_code.$request->acm_group_code.$request->acm_subgroup_code.$request->acm_asset_ctrl_code;
            //echo "====".$asset_ctrl_description; die();

            if ( $request->edit_map_code_id != "" ) 
            {
                $data = TM_ASSET_CONTROLLER_MAP::find($request->edit_map_code_id);
                $sql = "UPDATE TM_ASSET_CONTROLLER_MAP SET asset_ctrl_code = '".$request->acm_asset_ctrl_code."', asset_ctrl_description = '".$asset_ctrl_description."' WHERE id = ".$request->edit_map_code_id."";
                DB::UPDATE($sql);
            } 
            else 
            {

                $data = new TM_ASSET_CONTROLLER_MAP();
                $data->map_code = $new_map_code;
                $data->jenis_asset_code = $request->acm_jenis_asset_code;
                $data->group_code = $request->acm_group_code;
                $data->subgroup_code = $request->acm_subgroup_code;
                $data->asset_ctrl_code = $request->acm_asset_ctrl_code;
                $data->asset_ctrl_description = $asset_ctrl_description;
                $data->save();
            }

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_map_code_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function get_asset_ctrl_description($id)
    {
        $dt = '';
        $sql = " SELECT description FROM TM_GENERAL_DATA WHERE general_code = 'asset_controller' AND description_code = '".$id."' ";
        $data = DB::SELECT($sql); 
        //echo "<pre>"; print_r($data); die();

        if(!empty($data))
        {
            $dt .= $data[0]->description;
        }

        /*
        $data = DB::table('TM_GENERAL_DATA')
        ->select('description')
        ->where(array('general_code'=>'asset_controller','description_code'=>'$id'))
        ->orderby('description_code', 'asc')
        ->get();
        */

        return $dt;
    }

    public function show()
    {
        $param = $_REQUEST;
        //echo "<pre>"; print_r($param);
        $data = TM_JENIS_ASSET::find($param["id"]);
        return response()->json(array('data' => $data));
    }

    public function show_group_asset()
    {
        $param = $_REQUEST;
        $data = TM_GROUP_ASSET::find($param["id"]);
        return response()->json(array('data' => $data));
    }

    public function show_subgroup_asset()
    {
        $param = $_REQUEST;
        $data = TM_SUBGROUP_ASSET::find($param["id"]);
        return response()->json(array('data' => $data));
    }

    public function show_asset_map()
    {
        $param = $_REQUEST;
        //echo "<pre>"; print_r($param);
        $data = TM_ASSET_CONTROLLER_MAP::find($param["id"]);
        return response()->json(array('data' => $data));
    }

    public function show_detail()
    {
        $param = $_REQUEST;
        //echo "<pre>"; print_r($param);
        $data = TR_WORKFLOW_DETAIL::find($param["workflow_detail_code"]);
        return response()->json(array('data' => $data));
    }

    public function show_detail_job()
    {
        $param = $_REQUEST;
        //echo "<pre>"; print_r($param);
        $data = TR_WORKFLOW_JOB::find($param["workflow_job_code"]);
        return response()->json(array('data' => $data));
    }

    public function inactive(Request $request)
    {
        try {

            $data = Module::find($request->id);
            $data->updated_by = Session::get('user_id');
            $data->deleted = 1;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully inactived']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function active(Request $request)
    {
        try {
            $data = Module::find($request->id);
            $data->updated_by = Session::get('user_id');
            $data->deleted = 0;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully activated']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function select2() {
        $data = DB::table('TBM_MODULE')
        ->select('id', 'name as text')
        ->where('deleted', 0)
        ->get();

        return response()->json(array("data"=>$data));
    }

    public function dataGridGroupAsset(Request $request)
    {
        //echo "<pre>"; print_r($request->id); die();
        $req_id = $request->id;
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['a.id','a.group_code','a.group_description'];

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = "
            SELECT ".implode(", ", $selectedColumn)."
                FROM TM_GROUP_ASSET a 
                WHERE a.jenis_asset_code = '".$req_id."'
        ";


        if ($request->group_code)
        $sql .= " AND a.group_code like'%" . $request->group_code . "%'";


        if ($request->group_description)
        $sql .= " AND a.group_description like'%" . $request->group_description . "%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderBy . " " . $dirColumn;
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
        return response()->json($records); # = 1
    }

    public function dataGridSubGroupAsset(Request $request)
    {
        //echo "<pre>"; print_r($request->id); die();
        $req_id = $request->id;
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['a.id','a.subgroup_code', 'a.subgroup_description', 'a.group_code'];

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = '
            SELECT ' . implode(", ", $selectedColumn) . '
                FROM TM_SUBGROUP_ASSET a 
                WHERE a.group_code = "'.$req_id.'"
        ';

        if ($request->subgroup_code)
        $sql .= " AND a.subgroup_code like'%" . $request->subgroup_code . "%'";


        if ($request->subgroup_description)
        $sql .= " AND a.subgroup_description like'%" . $request->subgroup_description . "%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderBy . " " . $dirColumn;
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
        return response()->json($records); # = 1
    }

    public function dataGridAssetMap(Request $request)
    {
        //echo "<pre>"; print_r($request->id); die();
        $req_id = explode("__", $request->id);
        //echo "<pre>"; print_r($req_id); die();

        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";

        $selectedColumn = ['a.id','a.map_code', 'a.jenis_asset_code', 'a.group_code', 'a.subgroup_code', 'a.asset_ctrl_code', 'a.asset_ctrl_description'];

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = '
            SELECT ' . implode(", ", $selectedColumn) . '
                FROM TM_ASSET_CONTROLLER_MAP a 
                WHERE a.jenis_asset_code = "'.$req_id[0].'" AND a.group_code = "'.$req_id[1].'" AND a.subgroup_code = "'.$req_id[2].'"
        ';

        if ($request->jenis_asset_code)
        $sql .= " AND a.jenis_asset_code like'%" . $request->jenis_asset_code . "%'";


        if ($request->group_code)
        $sql .= " AND a.group_code like'%" . $request->group_code . "%'";

        if ($request->subgroup_code)
        $sql .= " AND a.subgroup_code like'%" . $request->subgroup_code . "%'";

        if ($request->asset_ctrl_code)
        $sql .= " AND a.asset_ctrl_code like'%" . $request->asset_ctrl_code . "%'";

        if ($request->asset_ctrl_description)
        $sql .= " AND a.asset_ctrl_description like'%" . $request->asset_ctrl_description . "%'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderBy . " " . $dirColumn;
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
        return response()->json($records); # = 1
    }

    public function workflowcode(){
        $data = DB::table('TR_WORKFLOW')
        ->select('workflow_code as id', 'workflow_name as text')
        //->where('deleted', 0)
        ->get();

        return response()->json(array("data"=>$data));
    }

    public function workflowcodedetail()
    {
        $data = DB::table('TR_WORKFLOW_DETAIL')
        ->select('workflow_detail_code as id', 'workflow_group_name as text')
        //->where('deleted', 0)
        ->get();
        return response()->json(array("data"=>$data));
    }

    public function workflowcoderole()
    {
        $data = DB::table('TBM_ROLE')
        ->select('id', 'name as text')
        //->where('deleted', 0)
        ->get();
        return response()->json(array("data"=>$data));
    }

    public function store_detail(Request $request)
    {
        try 
        {
            if ($request->edit_workflow_code_detail) {
                $data = TR_WORKFLOW_DETAIL::find($request->edit_workflow_code_detail);
                //$data->updated_by = Session::get('user_id');
            } else {
                $data = new TR_WORKFLOW_DETAIL();
                //$data->created_by = Session::get('user_id');
            }

            $data->workflow_code = $request->workflow_code;
            $data->workflow_group_name = $request->workflow_group_name;
            $data->seq = $request->seq;
            $data->description = $request->description;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_workflow_code_detail ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function store_detail_job(Request $request)
    {
        try 
        {
            if ($request->edit_workflow_code_detail_job) {
                $data = TR_WORKFLOW_JOB::find($request->edit_workflow_code_detail_job);
                //$data->updated_by = Session::get('user_id');
            } else {
                $data = new TR_WORKFLOW_JOB();
                //$data->created_by = Session::get('user_id');
            }

            $data->workflow_detail_code = $request->workflow_detail_code;
            $data->id_role = $request->id_role;
            $data->seq = $request->seq_job;
            $data->operation = $request->operation;
            $data->lintas = $request->lintas;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_workflow_code_detail_job ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function select_jenis_asset_code()
    {
        $data = DB::table('TM_JENIS_ASSET')
        ->select('jenis_asset_code as id', 'jenis_asset_description as text')
        //->where('deleted', 0)
        ->get();
        return response()->json(array("data"=>$data));
    }

    public function select_group_code()
    {
        $data = DB::table('TM_GROUP_ASSET')
        ->select('group_code as id', 'group_description as text')
        //->where('deleted', 0)
        ->get();
        return response()->json(array("data"=>$data));
    }

    public function select_subgroup_code()
    {
        $data = DB::table('TM_SUBGROUP_ASSET')
        ->select('subgroup_code as id', 'subgroup_description as text')
        //->where('deleted', 0)
        ->get();
        return response()->json(array("data"=>$data));
    }

    public function select_asset_controller()
    {
        $data = DB::table('TM_GENERAL_DATA')
        ->select('description_code as id', 'description as text')
        ->where('general_code', 'asset_controller')
        ->orderby('description_code', 'asc')
        ->get();
        return response()->json(array("data"=>$data));
    }
}
