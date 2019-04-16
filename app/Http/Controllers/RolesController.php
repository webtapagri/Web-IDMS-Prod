<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
use App\Role;

class RolesController extends Controller
{

    public function index()
    {
        if (empty(Session::get('authenticated')))
            return redirect('/login');
/* 
        if (AccessRight::granted() == false)
            return response(view('errors.403'), 403);; */

        $access = AccessRight::access();    
        $data["page_title"] = "Role";
        $data["access"] = $access;
        return view('usersetting.roles')->with(compact('data'));
    }

    public function dataGrid()
    {
        $data = Db::table('tbm_role')->get();

        return response()->json(array('data' => $data));
    }

    public function store(Request $request)
    {
        try {
            if ($request->edit_id) {
                $data = Role::find($request->edit_id);
                $data->updated_by = Session::get('user_id');
            } else {
                $data = new Role();
                $data->created_by = Session::get('user_id');
            }

            $data->name = $request->name;
            $data->description = $request->description;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function show()
    {
        $param = $_REQUEST;
        $data = Role::find($param["id"]);
        return response()->json(array('data' => $data));
        
    }

    public function inactive(Request $request)
    {
        try {

            $data = Role::find($request->id);
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
            $data = Role::find($request->id);
            $data->updated_by = Session::get('user_id');
            $data->deleted = 0;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully activated']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function select2()
    {
        $data = DB::table('tbm_role')
            ->select('id', 'name as text')
            ->where('deleted', 0)
            ->get();

        return response()->json(array("data" => $data));
    }
}
