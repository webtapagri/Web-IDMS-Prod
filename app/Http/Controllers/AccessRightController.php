<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use function GuzzleHttp\json_encode;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Session;
use AccessRight;
use API;


class AccessRightController extends Controller
{
    public function index()
    {
        if (empty(Session::get('authenticated')))
            return redirect('/login');

       /*  if (AccessRight::granted() == false)
            return response(view('errors.403'), 403);; */

        $access = AccessRight::access();
        $data["page_title"] = "Access Right";
        $data["access"] = $access;    
        return view('usersetting.accessright')->with(compact('data'));
    }

    public function dataGrid(Request $request)
    {
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";
        $field = array(
            array("index" => "0", "field" => "role.name", "alias" => "role_name"),
            array("index" => "1", "field" => "module.module_name", "alias" => ""),
            array("index" => "2", "field" => "module.menu_name", "alias" => ""),
            array("index" => "3", "field" => "role_access.create", "alias" => ""),
            array("index" => "4", "field" => "role_access.read", "alias" => ""),
            array("index" => "5", "field" => "role_access.update", "alias" => ""),
            array("index" => "6", "field" => "role_access.delete", "alias" => ""),
        );

        foreach ($field as $row) {
            if ($row["alias"]) {
                $selectedColumn[] = $row["field"] . " as " . $row["alias"];
            } else {
                $selectedColumn[] = $row["field"];
            }

            if ($row["index"] == $orderColumn) {
                $orderColumnName = $row["field"];
            }
        }

        $sql = '
            SELECT
                role.id as role_id,
                module.module_id,
                module.menu_id, 
                role_access.id as access_id
                '.implode(", ",$selectedColumn).'
                FROM  
                    tbm_role as role
                JOIN(
                    SELECT 
                        module.id as module_id,
                        module.name as module_name,
                        menu.id as menu_id,
                        menu.name as menu_name
                    FROM 
                        tbm_module as module
                    INNER JOIN tbm_menu as menu ON (module.id=menu.module_id)
                    WHERE module.deleted=0
                ) as module
                LEFT OUTER JOIN(
                    select id, role_id, menu_id, module_id, `create`, `read`, `update`, `delete`
                    from tbm_role_access 
                )  as role_access ON (role_access.role_id = role.id AND role_access.module_id=module.module_id  AND role_access.menu_id=module.menu_id)
                WHERE role.deleted=0 
        ';
        //$total_data = DB::select(DB::raw($sql));
        //$sql .=  " limit " . $request->start . ', ' .$request->length;

        if($request->role)
            $sql .= ' AND role.id = ' . $request->role;
        
        if($request->module)
            $sql .= ' AND module.module_id = ' . $request->module;
       
        if($request->menu)
            $sql .= ' AND module.menu_id = ' . $request->menu;
        
        
        if($request->create)
        $sql .= ' AND role_access.create = ' . $request->create;
        
        if($request->read)
        $sql .= ' AND role_access.read = ' . $request->read;
        
        if($request->update)
        $sql .= ' AND role_access.update = ' . $request->update;
        
        if($request->delete)
        $sql .= ' AND role_access.delete = ' . $request->delete;

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $orderColumnName . " " . $dirColumn;
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

    public function show()
    {
        $param = $_REQUEST;
        $data = explode('-', $param["id"]);
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tr_role_accessright/" . $data[0] .'/'. $data[1] .'/'. $data[2]
        ));
        $data = $service->data;

        return response()->json(array('data' => $data));

    }

    public function store(Request $request)
    {
        try {
            $param["menu_code"] = \trim($request->menu) ;
            $param["id_role"] = \trim($request->role_id);
            $param["operation"] = trim($request->operation);
            $param["description"] = $request->description;

            if ($request->edit_id) {
                $data = explode('-', $request->edit_id);

                $param["updated_at"] = date('Y-m-d H:i:s');
                $param["updated_by"] = Session::get('user');

                $data = API::exec(array(
                    'request' => 'PUT',
                    'method' => "tr_role_accessright/" . $data[0] . '/' . $data[1] . '/' . $data[2],
                    'data' => $param
                ));
            } else {
                $param["created_at"] = date('Y-m-d H:i:s');
                $param["created_by"] = Session::get('user');
                $data = API::exec(array(
                    'request' => 'POST',
                    'method' => 'tr_role_accessright',
                    'data' => $param
                ));
            }
            $res = $data;
            if ($res->code == '201') {
                if($res->status == 'failed') {
                    return response()->json(['status' => true, "exist"=>true, "message" => $res->message]);
                }else {
                    return response()->json(['status' => true, "exist" => false, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
                }
            } else {
                return response()->json(['status' => false, "message" => $res->message]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function inactive(Request $request)
    {
        try {
            $param = explode('-', $request->id);
            $data = API::exec(array(
                'request' => 'DELETE',
                'method' => "tr_role_accessright/" . $param[0] . '/' . $param[1] . '/' . $param[2]
            ));

            $res = $data;

            if ($res->code == '201') {
                return response()->json(['status' => true, "message" => 'Data is successfully deleted']);;
            } else {
                return response()->json(['status' => false, "message" => $res->message]);
            }

        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function get_menu()
    {
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tm_menu"
        ));
        $data = $service->data;
        $item = array();
        foreach ($data as $row) {
            $item[] = array(
                'id' => $row->menu_code,
                'text' => $row->menu_name
            );
        }

        return response()->json(array('data' => $item));
    }
}
