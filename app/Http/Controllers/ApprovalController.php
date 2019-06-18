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

class ApprovalController extends Controller
{
    public function index()
    {
        /*  if (empty(Session::get('authenticated')))
            return redirect('/login');

        if (AccessRight::granted() == false)
            return response(view('errors.403'), 403);

        $access = AccessRight::access(); */
        $data['page_title'] = "Approval";
        $data['ctree_mod'] = 'Approval';
        $data['ctree'] = 'approval';

        return view('approval.index')->with(compact('data'));
    }

    public function dataGrid(Request $request)
    {
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";
        $addwhere = "";
        $role_id = Session::get('role_id');
        $user_id = Session::get('user_id');
        
        $field = array
        (
            array("index" => "0", "field" => "asset.NO_REG", "alias" => "no_reg"),
            array("index" => "1", "field" => "asset.TYPE_TRANSAKSI ", "alias" => "type"),
            array("index" => "2", "field" => "asset.PO_TYPE", "alias" => "po_type"),
            array("index" => "3", "field" => "asset.NO_PO", "alias" => "no_po"),
            array("index" => "4", "field" => "DATE_FORMAT(asset.TANGGAL_REG, '%d %b %Y')", "alias" => "request_date"),
            array("index" => "5", "field" => "requestor.name", "alias" => "requestor"),
            array("index" => "6", "field" => "DATE_FORMAT(asset.TANGGAL_PO, '%d %b %Y')", "alias" => "po_date"),
            array("index" => "7", "field" => "asset.KODE_VENDOR", "alias" => "vendor_code"),
            array("index" => "8", "field" => "asset.NAMA_VENDOR", "alias" => "vendor_name"),
        );

        foreach ($field as $row) 
        {
            if ($row["alias"]) {
                $selectedColumn[] = $row["field"] . " as " . $row["alias"];
            } else {
                $selectedColumn[] = $row["field"];
            }

            if ($row["index"] == $orderColumn) {
                $orderColumnName = $row["field"];
            }
        }

        // it@140619 JOIN W v_outstanding
        if($role_id!=4){ $addwhere .= " AND approval.user_id = '{$user_id}' "; }
        $sql = '
            SELECT distinct(asset.id) as id '.implode(", ", $selectedColumn).'
            FROM v_outstanding as approval 
                LEFT JOIN TR_REG_ASSET as asset ON ( approval.document_code = asset.no_reg)
                INNER JOIN TBM_USER as requestor ON (requestor.id=asset.CREATED_BY)
            WHERE asset.NO_REG > 0 '.$addwhere.'
        ';

        /*
        $sql = '
            SELECT asset.ID as id ' . implode(", ", $selectedColumn) . '
            FROM TR_REG_ASSET as asset
            INNER JOIN TBM_USER as requestor ON (requestor.id=asset.CREATED_BY)
            WHERE asset.NO_REG > 0
        ';
        */

        $total_data = DB::select(DB::raw($sql));

        if ($request->no_po)
            $sql .= " AND asset.NO_PO  like '%" . $request->no_po . "%'";
       
            if ($request->no_reg)
            $sql .= " AND asset.NO_REG  like '%" . $request->no_reg . "%'";

        if ($request->requestor)
            $sql .= " AND requestor.name  like '%" . $request->requestor . "%'";

        if ($request->vendor_code)
            $sql .= " AND asset.KODE_VENDOR  like '%" . $request->vendor_code . "%'";

        if ($request->vendor_name)
            $sql .= " AND asset.NAMA_VENDOR  like '%" . $request->vendor_name . "%'";

        if ($request->transaction_type)
            $sql .= " AND asset.TYPE_TRANSAKSI  = " . $request->transaction_type;
     
        if($request->po_type !='')
            $sql .= " AND asset.PO_TYPE  = " . $request->po_type;

        if ($request->request_date)
            $sql .= " AND DATE_FORMAT(asset.TANGGAL_REG, '%Y-%m-%d') = '" . DATE_FORMAT(date_create($request->request_date), 'Y-m-d'). "'";


        if ($request->po_date)
            $sql .= " AND DATE_FORMAT(asset.TANGGAL_PO, '%Y-%m-%d') = '" . DATE_FORMAT(date_create($request->po_date), 'Y-m-d') ."'";

        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $field[$orderColumn]['field'] . " " . $dirColumn;
        }
        else
        {
            $sql .= " ORDER BY asset.ID DESC ";
        }

        //echo $sql; die();

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

    public function create(Request $request) {
        $type = ($request->type == "amp" ? 'Melalui PO AMP':'Melalui PO Sendiri');
        return view('assets.add')->with(compact('type'));
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

    function view($id)
    {
        $noreg = str_replace("-", "/", $id);

        $records = array();

        $sql = " SELECT a.*, date_format(a.tanggal_reg,'%d-%m-%Y') AS TANGGAL_REG, b.description_code AS CODE_AREA, b.description AS NAME_AREA, c.name AS REQUESTOR 
                    FROM TR_REG_ASSET a 
                        LEFT JOIN TM_GENERAL_DATA b ON a.business_area = b.description_code AND b.general_code = 'plant'
                        LEFT JOIN TBM_USER c ON a.created_by = c.id 
                    WHERE a.no_reg = '$noreg' ";
        $data = DB::SELECT($sql);

        //echo "<pre>"; print_r($data); die();
        
        if($data)
        {
            $type_transaksi = array(
                1 => 'Barang',
                2 => 'Jasa',
                3 => 'Lain-lain',
            );

            $po_type = array(
                0 => 'SAP',
                1 => 'AMP'
            );

            foreach ($data as $k => $v) 
            {
                //echo "<pre>"; print_r($v->NO_REG);
                # code...

                $records[] = array(
                    'no_reg' => $v->NO_REG,
                    'type_transaksi' => $type_transaksi[$v->TYPE_TRANSAKSI],
                    'po_type' => $po_type[$v->PO_TYPE],
                    'business_area' => $v->CODE_AREA.' - '.$v->NAME_AREA,
                    'requestor' => $v->REQUESTOR,
                    'tanggal_reg' => $v->TANGGAL_REG,
                    'item_detail' => $this->get_item_detail($noreg)
                );

            }
        }
        
        //echo "<pre>"; print_r($records[0]); die();

        //echo $id; die();
        //$records = array('id'=>$id);
        //echo response()->json($records);
        echo json_encode($records[0]);
    }

    function get_item_detail($noreg)
    {
        $request = array();
        $sql = " SELECT a.* FROM TR_REG_ASSET_DETAIL_PO a WHERE a.no_reg = '{$noreg}' ";
        $data = DB::SELECT($sql);
        //echo "<pre>"; print_r($data); die();

        if($data)
        {
            foreach( $data as $k => $v )
            {
                $request[] = array
                (
                    'id' => $v->ID,
                    'no_po' => $v->NO_PO,
                    'item' => $v->ITEM_PO,
                    'qty' => $v->QUANTITY_SUBMIT,
                    'kode' => $v->KODE_MATERIAL,
                    'nama' => $v->NAMA_MATERIAL,
                    //'asset' => $this->get_asset_detail($noreg,$v->KODE_MATERIAL) 
                );
            }
        }

        return $request;
    }

    function get_asset_detail($noreg,$id)
    {
        //echo $noreg.'/'.$id; die();

        $kondisi = array(
            'B' => 'Baik',
            'BP' => 'Butuh Perbaikan',
            'TB' => 'Tidak Baik'
        );

        $noreg = str_replace("-", "/", $noreg);

        $records = array();
        $sql = " SELECT a.*, b.jenis_asset_description AS JENIS_ASSET_NAME, c.group_description AS GROUP_NAME, d.subgroup_description AS SUB_GROUP_NAME
                    FROM TR_REG_ASSET_DETAIL a 
                        LEFT JOIN TM_JENIS_ASSET b ON a.jenis_asset = b.jenis_asset_code 
                        LEFT JOIN TM_GROUP_ASSET c ON a.group = c.group_code AND a.jenis_asset = c.jenis_asset_code
                        LEFT JOIN TM_SUBGROUP_ASSET d ON a.sub_group = d.subgroup_code AND a.group = d.group_code
                    WHERE a.no_reg = '{$noreg}' AND a.asset_po_id = '{$id}' AND a.DELETED is null
                        ORDER BY a.kode_material ";
        //echo $sql; die();
        $data = DB::SELECT($sql);
        //echo "<pre>"; print_r($data); die();

        if($data)
        {
            foreach( $data as $k => $v )
            {
                $records[] = array
                (
                    'id' => $v->ID,
                    'no_po' => $v->NO_PO,
                    'asset_po_id' => $v->ASSET_PO_ID,
                    'tgl_po' => $v->CREATED_AT,
                    'kondisi_asset' => @$kondisi[$v->KONDISI_ASSET],
                    'jenis_asset' => $v->JENIS_ASSET.'-'.$v->JENIS_ASSET_NAME,
                    'group' => $v->GROUP.'-'.$v->GROUP_NAME,
                    'sub_group' => $v->SUB_GROUP.'-'.$v->SUB_GROUP_NAME,
                    'nama_asset' => $v->NAMA_ASSET,
                    'merk' => $v->MERK,
                    'spesifikasi_or_warna' => $v->SPESIFIKASI_OR_WARNA,
                    'no_rangka_or_no_seri' => $v->NO_RANGKA_OR_NO_SERI,
                    'no_mesin_or_imei' => $v->NO_MESIN_OR_IMEI,
                    'lokasi' => $v->LOKASI_BA_DESCRIPTION,
                    'tahun' => $v->TAHUN_ASSET,
                    'info' => $v->INFORMASI,
                    'file' => $this->get_asset_file($v->ID,$noreg),

                );
            }
        }

        echo json_encode($records);
    }

    function get_asset_file($id,$noreg)
    {
        $records = array();
        $sql = " SELECT a.* FROM TR_REG_ASSET_DETAIL_FILE a WHERE a.no_reg = '{$noreg}' AND a.asset_po_detail_id = '{$id}' ";
        $data = DB::SELECT($sql);//echo $sql; die();
        //echo "<pre>"; print_r($data); die();

        if($data)
        {
            foreach( $data as $k => $v )
            {
                $records[] = array
                (
                    'id' => $v->ID,
                    'file_category' => $v->FILE_CATEGORY,
                    'filename' => $v->FILENAME,
                    'jenis_foto' => $v->JENIS_FOTO,
                    'file_thumb' => $v->FILE_UPLOAD
                );
            }
        }

        return $records;
    }

    function delete_asset(Request $request, $id)
    {
        //echo $id; die();
        DB::beginTransaction();

        try 
        {
            $sql = " UPDATE TR_REG_ASSET_DETAIL SET DELETED = 'X' WHERE ID = $id ";
            DB::UPDATE($sql);    

            DB::commit();
            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($id ? 'updated' : 'update')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    public function dataGridHistory(Request $request)
    {
        $orderColumn = $request->order[0]["column"];
        $dirColumn = $request->order[0]["dir"];
        $sortColumn = "";
        $selectedColumn[] = "";
        $addwhere = "";
        $role_id = Session::get('role_id');
        $user_id = Session::get('user_id');
        
        $field = array
        (
            array("index" => "0", "field" => "document_code ", "alias" => "document_code"),
            array("index" => "1", "field" => "area_code ", "alias" => "area_code"),
            array("index" => "2", "field" => "name", "alias" => "name"),
            array("index" => "3", "field" => "status_dokumen", "alias" => "status_dokumen"),
            array("index" => "4", "field" => "status_approval", "alias" => "status_approval"),
            array("index" => "5", "field" => "notes", "alias" => "po_notes"),
            array("index" => "6", "field" => "DATE_FORMAT(date, '%d %b %Y')", "alias" => "po_date"),
        );

        foreach ($field as $row) 
        {
            if ($row["alias"]) {
                $selectedColumn[] = $row["field"] . " as " . $row["alias"];
            } else {
                $selectedColumn[] = $row["field"];
            }

            if ($row["index"] == $orderColumn) {
                $orderColumnName = $row["field"];
            }
        }

        // it@140619 JOIN W v_outstanding
        //if($role_id!=4){ $addwhere .= " AND approval.user_id = '{$user_id}' "; }
        /*$sql = '
            SELECT distinct(asset.id) as id '.implode(", ", $selectedColumn).'
            FROM v_outstanding as approval 
                LEFT JOIN TR_REG_ASSET as asset ON ( approval.document_code = asset.no_reg)
                INNER JOIN TBM_USER as requestor ON (requestor.id=asset.CREATED_BY)
            WHERE asset.NO_REG > 0 '.$addwhere.'
        ';*/

        $sql = '
            SELECT user_id as user_id '.implode(", ", $selectedColumn).'
                FROM V_HISTORY
            WHERE user_id = '.$user_id.'
        ';

        $total_data = DB::select(DB::raw($sql));

        /*
        if ($request->no_po)
            $sql .= " AND asset.NO_PO  like '%" . $request->no_po . "%'";
       
            if ($request->no_reg)
            $sql .= " AND asset.NO_REG  like '%" . $request->no_reg . "%'";

        if ($request->requestor)
            $sql .= " AND requestor.name  like '%" . $request->requestor . "%'";

        if ($request->vendor_code)
            $sql .= " AND asset.KODE_VENDOR  like '%" . $request->vendor_code . "%'";

        if ($request->vendor_name)
            $sql .= " AND asset.NAMA_VENDOR  like '%" . $request->vendor_name . "%'";

        if ($request->transaction_type)
            $sql .= " AND asset.TYPE_TRANSAKSI  = " . $request->transaction_type;
     
        if($request->po_type !='')
            $sql .= " AND asset.PO_TYPE  = " . $request->po_type;

        if ($request->request_date)
            $sql .= " AND DATE_FORMAT(asset.TANGGAL_REG, '%Y-%m-%d') = '" . DATE_FORMAT(date_create($request->request_date), 'Y-m-d'). "'";

        if ($request->po_date)
            $sql .= " AND DATE_FORMAT(asset.TANGGAL_PO, '%Y-%m-%d') = '" . DATE_FORMAT(date_create($request->po_date), 'Y-m-d') ."'";
        */
    
        if ($orderColumn != "") {
            $sql .= " ORDER BY " . $field[$orderColumn]['field'] . " " . $dirColumn;
        }
        else
        {
            $sql .= " ORDER BY po_date DESC ";
        }

        //echo $sql; die();

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

    function update_status(Request $request, $status, $noreg)
    {
        //echo $status.'===='.$noreg; die();
        //A====19.06-AMS-PDFA-00009

        //echo "<pre>"; print_r($_REQUEST); die();

        $no_registrasi = str_replace("-", "/", $noreg);
        $user_id = Session::get('user_id');
        $note = $request->parNote;
        //echo $note;die();

        DB::beginTransaction();

        try 
        {
            DB::SELECT('call update_approval("'.$no_registrasi.'", "'.$user_id.'","'.$status.'", "'.$note.'")');

            DB::commit();
            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($no_registrasi ? 'updated' : 'update')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }
}
