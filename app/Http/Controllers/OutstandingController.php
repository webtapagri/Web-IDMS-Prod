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

class OutstandingController extends Controller
{
    public function index()
    {
        if (empty(Session::get('authenticated')))
            return redirect('/login');

       /*  if (AccessRight::granted() == false)
            return response(view('errors.403'), 403); */

       /*  $access = AccessRight::access(); */
       $data["page_title"] = "User";
        return view('usersetting.users')->with(compact('data'));
    }

    function requestDetail()
    {
        $data = DB::table('TR_REG_ASSET')
            ->where("NO_REG", "=", $no_reg)
            ->get();

        return response()->json(array('data' => $data));
    }

}
