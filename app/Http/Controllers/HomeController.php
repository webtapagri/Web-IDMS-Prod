<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cookie;
use Session;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(empty(Session::get('authenticated')))
            return redirect('/login');

        $data['page_title'] = "Dashboard";
        if(Session::get('role_id')) {
            return view('dashboard')->with(compact('data'));
        } else {
            return view('home')->with(compact('data'));
        }    
    }

    public function dataGrid(Request $request)
    {
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";
        $field = array(
            array("index" => "0", "field" => "asset.TYPE_TRANSAKSI ", "alias" => "type"),
            array("index" => "1", "field" => "asset.NO_PO", "alias" => "no_po"),
            array("index" => "2", "field" => "DATE_FORMAT(asset.TANGGAL_REG, '%d %M %Y')", "alias" => "request_date"),
            array("index" => "3", "field" => "requestor.name", "alias" => "requestor"),
            array("index" => "4", "field" => "DATE_FORMAT(asset.TANGGAL_PO, '%d %M %Y')", "alias" => "po_date"),
            array("index" => "5", "field" => "asset.KODE_VENDOR", "alias" => "vendor_code"),
            array("index" => "6", "field" => "asset.NAMA_VENDOR", "alias" => "vendor_name"),
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

        if ($orderColumn) {
            $order = explode("as", $selectedColumn[$orderColumn]);
            if (count($order) > 1) {
                $orderBy = $order[0];
            } else {
                $orderBy = $selectedColumn[$orderColumn];
            }
        }

        $sql = '
            SELECT asset.NO_REG as id ' . implode(", ", $selectedColumn) . '
            FROM TR_REG_ASSET as asset
            INNER JOIN TBM_USER as requestor ON (requestor.id=asset.CREATED_BY)
            WHERE asset.NO_REG > 0
        ';

        $total_data = DB::select(DB::raw($sql));

        if ($request->no_po)
            $sql .= " AND asset.NO_PO  like '%" . $request->no_po . "%'";

        if ($request->requestor)
            $sql .= " AND requestor.name  like '%" . $request->requestor . "%'";

        if ($request->vendor_code)
            $sql .= " AND asset.KODE_VENDOR  like '%" . $request->vendor_code . "%'";

        if ($request->vendor_name)
            $sql .= " AND asset.NAMA_VENDOR  like '%" . $request->vendor_name . "%'";

        if ($request->transaction_type)
            $sql .= " AND asset.TYPE_TRANSAKSI  = " . $request->transaction_type;

        if ($request->request_date)
            $sql .= " AND DATE_FORMAT(asset.TANGGAL_REG, '%Y-%m-%d') = " . DATE_FORMAT(date_create($request->request_date), 'Y-m-d');


        if ($request->po_date)
            $sql .= " AND DATE_FORMAT(asset.TANGGAL_PO, '%Y-%m-%d') = " . DATE_FORMAT(date_create($request->po_date), 'Y-m-d');

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

    public function outstanding()
    {
        $data = DB::table('TR_REG_ASSET')
            ->where("NO_REG", "=", $no_reg)
            ->get();

        return response()->json(array('data' => $data));
    }
}