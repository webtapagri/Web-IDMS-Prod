<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use API;
use Session;

class Select2Controller extends Controller
{

    public function get(Request $request){
         $table = $request->get('table');
         $id = $request->get('id');
         $text = $request->get('text');
        $where = '';
        if( $request->get('wheres')) {
            $no = 1;
            foreach( $request->get('wheres') as $row) {
                $param = explode(',',$row);
                if($no>1) {
                    $where .= ' AND';
                }
                if($param[1] == 'equal') {
                    $where .= $param[0]."= '".$param[2]."'";
                }
                $no++;
            }
        }
        
         $data = DB::table("$table")
         ->select($id . ' as id', $text . ' as text')
         ->whereRaw($where)
         ->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id" => $row->id,
                "text" => $row->id .'-' . $row->text
            );
        }

        return response()->json(array('data' => $arr));
    }

    public function generaldataplant(Request $request) {
        $data = DB::table('TM_GENERAL_DATA')
        ->select('DESCRIPTION_CODE as id', 'DESCRIPTION as text')
        ->where([
            [ 'GENERAL_CODE',"=" ,'plant'],
        ])
        ->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id" => $row->id,
                "text" => $row->id .'-' . $row->text
            );
        }

        return response()->json(array('data' => $arr));
    }
    
    public function jenisasset(Request $request) {
        $data = DB::table( 'TM_JENIS_ASSET')
        ->select( 'JENIS_ASSET_CODE as id', 'JENIS_ASSET_DESCRIPTION as text')
        ->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id" => $row->id,
                "text" => $row->id .'-' . $row->text
            );
        }

        return response()->json(array('data' => $arr));
    }
    
    public function assetgroup(Request $request) {
        $data = DB::table( 'TM_GROUP_ASSET')
        ->select('GROUP_CODE as id', 'GROUP_DESCRIPTION as text')
        ->where( "JENIS_ASSET_CODE", $request->type)
        ->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id" => $row->id,
                "text" => $row->id .'-' . $row->text
            );
        }

        return response()->json(array('data' => $arr));
    }

    public function assetsubgroup(Request $request) {
        $data = DB::table( 'TM_SUBGROUP_ASSET')
        ->select('SUBGROUP_CODE as id', 'SUBGROUP_DESCRIPTION as text')
        ->where( "GROUP_CODE", $request->group)
        ->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id" => $row->id,
                "text" => $row->id .'-' . $row->text
            );
        }

        return response()->json(array('data' => $arr));
    }
}
