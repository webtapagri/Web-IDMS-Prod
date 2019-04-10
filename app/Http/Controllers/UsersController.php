<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\TrUser;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
use App\User;
class UsersController extends Controller
{
    public function index()
    {
        if (empty(Session::get('authenticated')))
            return redirect('/login');

       /*  if (AccessRight::granted() == false)
            return response(view('errors.403'), 403); */

       /*  $access = AccessRight::access(); */
        return view('usersetting.users')->with(compact('access'));
    }

    public function dataGrid() {
        $data = DB::table('tbm_user as user')
        ->join('tbm_role as role', 'role.id','=', 'user.role_id')
        ->select('user.*', 'role.name as role_name')
        ->get();

        return response()->json(array('data' => $data));

    }

    public function store(Request $request)
    {
       try {

            if ($request->edit_id) {
                $data = User::find($request->edit_id);
                $data->updated_by = Session::get('user_id');
            } else {
                $data = new User();
                $data->created_by = Session::get('user_id');
            }

            $data->role_id = $request->role_id;
            $data->username = $request->username;
            $data->name = $request->name;
            $data->email = $request->email;
            $data->job_code = $request->job_code;
            $data->nik = $request->nik;
            $data->area_code = implode(',', $request->area_code);
          
            foreach ($_FILES as $row) {
                if ($row["name"]) {
                    $name = $row["name"];
                    $size = $row["size"];
                    $path = $row["tmp_name"];
                    $type = pathinfo($row["tmp_name"], PATHINFO_EXTENSION);
                    $img = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($img);
                   $data->img = $base64;
                }
            }

            $data->save();
            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
            
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
        $data = User::find($param['id']);
        return response()->json(array('data' => $data));
    }

    public function inactive(Request $request) {
        try {
            $data = User::find($request->id);
            $data->updated_by = Session::get('user_id');
            $data->deleted = 1;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully inactived']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }
   
    public function active(Request $request) {
        try {
            $data = User::find($request->id);
            $data->updated_by = Session::get('user_id');
            $data->deleted = 0;

            $data->save();

            return response()->json(['status' => true, "message" => 'Data is successfully inactived']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }
}
