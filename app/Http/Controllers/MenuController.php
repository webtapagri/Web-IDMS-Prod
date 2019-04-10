<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\TmRole;
use function GuzzleHttp\json_encode;
use Session;
use API;
use App\Menu;
use AccessRight;

class MenuController extends Controller
{

    public function index()
    {
        if (empty(Session::get('authenticated')))
            return redirect('/login');
            
        /* if (AccessRight::granted() == false)
            return response(view('errors.403'), 403); */

        $access = AccessRight::access();
        return view('usersetting.menu')->with(compact('access'));
    }

    public function dataGrid()
    {
        $data = Db::table('tbm_menu as menu')
        ->join('tbm_module as module', 'module.id', '=', 'menu.module_id')
        ->select('module.id as module_id', 'module.name as module_name', 'menu.id', 'menu.name', 'menu.url', 'menu.sort', 'menu.deleted')
        ->get();

        return response()->json(array('data' => $data));
    }

    public function store(Request $request)
    {
        try {
            if ($request->edit_id) {
                $data = Menu::find($request->edit_id);
                $data->updated_by = Session::get('user_id');
            } else {
                $data = new Menu();
                $data->created_by = Session::get('user_id');
            }

            $data->module_id = $request->module;
            $data->name = $request->name;
            $data->sort = $request->sorting;
            $data->url = $request->url;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    function validateName($name) {
        $data = DB::table('tbm_menu')
            ->select('id', 'name as text')
            ->where('name', $name)
            ->get();

        if (count($data > 0)) {
            return false;
        } else {
            return true;
        }
    }

    public function show()
    {
        $param = $_REQUEST;
        $data = Menu::find($param["id"]);
        return response()->json(array('data' => $data));
        
    }

    public function inactive(Request $request)
    {
        try {
            $data = Menu::find($request->id);
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
            $data = Menu::find($request->id);
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
        $data = DB::table('tbm_menu')
            ->select('id', 'name as text')
            ->where('deleted', 0)
            ->get();

        return response()->json(array("data" => $data));
    }
}
