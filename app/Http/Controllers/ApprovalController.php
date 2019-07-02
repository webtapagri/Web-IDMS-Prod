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
        if (empty(Session::get('authenticated')))
            return redirect('/login');

        if (AccessRight::granted() == false)
            return response(view('errors.403'), 403);

        $access = AccessRight::access();
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
                    'item_detail' => $this->get_item_detail($noreg),
                    'sync_sap' => $this->get_sinkronisasi_sap($noreg)
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
        $sql = " SELECT a.*, b.jenis_asset_description AS JENIS_ASSET_NAME, c.group_description AS GROUP_NAME, d.subgroup_description AS SUB_GROUP_NAME, e.KODE_VENDOR, e.NAMA_VENDOR, e.BUSINESS_AREA AS BUSINESS_AREA
                    FROM TR_REG_ASSET_DETAIL a 
                        LEFT JOIN TM_JENIS_ASSET b ON a.jenis_asset = b.jenis_asset_code 
                        LEFT JOIN TM_GROUP_ASSET c ON a.group = c.group_code AND a.jenis_asset = c.jenis_asset_code
                        LEFT JOIN TM_SUBGROUP_ASSET d ON a.sub_group = d.subgroup_code AND a.group = d.group_code
                        LEFT JOIN TR_REG_ASSET e ON a.NO_REG = e.NO_REG
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
                    'nama_asset_1' => $v->NAMA_ASSET_1,
                    'nama_asset_2' => $v->NAMA_ASSET_2,
                    'nama_asset_3' => $v->NAMA_ASSET_3,
                    'quantity_asset_sap' => $v->QUANTITY_ASSET_SAP,
                    'uom_asset_sap' => $v->UOM_ASSET_SAP,
                    'capitalized_on' => $v->CAPITALIZED_ON,
                    'deactivation_on' => $v->DEACTIVATION_ON,
                    'cost_center' => $v->COST_CENTER,
                    'book_deprec_01' => $v->BOOK_DEPREC_01,
                    'fiscal_deprec_15' => $v->FISCAL_DEPREC_15,
                    'group_deprec_30' => $v->GROUP_DEPREC_30,
                    'no_reg_item' => $v->NO_REG_ITEM,
                    'vendor' => $v->KODE_VENDOR.'-'.$v->NAMA_VENDOR,
                    'business_area' => $v->BUSINESS_AREA
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

    function save_asset_sap(Request $request, $id)
    {
        $user_id = Session::get('user_id');

        DB::beginTransaction();

        try 
        {
            $sql = " UPDATE TR_REG_ASSET_DETAIL 
                        SET 
                            nama_asset_1 = '{$request->nama_asset_1}',
                            nama_asset_2 = '{$request->nama_asset_2}',
                            nama_asset_3 = '{$request->nama_asset_3}',
                            quantity_asset_sap = '{$request->quantity}',
                            uom_asset_sap = '{$request->uom}',
                            capitalized_on = '{$request->capitalized_on}',
                            deactivation_on = '{$request->deactivation_on}',
                            cost_center = '{$request->cost_center}',
                            book_deprec_01 = '{$request->book_deprec_01}',
                            fiscal_deprec_15 = '{$request->fiscal_deprec_15}',
                            group_deprec_30 = '{$request->group_deprec_30}',
                            updated_by = '{$user_id}',
                            updated_at = current_timestamp()
                    WHERE ID = $id AND NO_REG = '{$request->getnoreg}' AND NO_REG_ITEM = {$request->no_reg_item} ";
            DB::UPDATE($sql);    

            DB::commit();
            return response()->json(['status' => true, "message" => 'Data Detail Asset SAP is successfully ' . ($id ? 'updated' : 'update')]);
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
            //array("index" => "5", "field" => "notes", "alias" => "po_notes"),
            array("index" => "5", "field" => "DATE_FORMAT(date, '%d %b %Y')", "alias" => "po_date"),
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
                FROM v_history
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
        $role_id = Session::get('role_id'); //get role id user
        $asset_controller = ''; //get asset controller 
        //echo $note;die();

        DB::beginTransaction();

        try 
        {
            DB::SELECT('call update_approval("'.$no_registrasi.'", "'.$user_id.'","'.$status.'", "'.$note.'", "'.$role_id.'", "'.$asset_controller.'")');

            DB::commit();
            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($no_registrasi ? 'updated' : 'update')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, "message" => $e->getMessage()]);
        }
    }

    function log_history($id)
    {
        $noreg = str_replace("-", "/", $id);

        $records = array();

        /*$sql = "SELECT a.*, date_format(a.date,'%d-%m-%Y %h:%i:%s') AS date2 FROM v_history a WHERE a.document_code = '{$noreg}' ORDER BY a.date";*/
        $sql = "SELECT document_code,user_id,name,area_code,status_approval,notes,date FROM v_history_approval WHERE document_code = '{$noreg}'";

        $data = DB::SELECT($sql);
        
        if($data)
        {

            foreach ($data as $k => $v) 
            {
                //echo "<pre>"; print_r($v->NO_REG);
                # code...

                $notes = $v->notes == '' ? '' : $v->notes;

                $records[] = array(
                    'document_code' => $v->document_code,
                    'area_code' => $v->area_code,
                    'user_id' => $v->user_id,
                    'name' => $v->name,
                    //'status_dokumen' => $v->status_dokumen,
                    'status_approval' => $v->status_approval,
                    'notes' => $notes,
                    'date' => $v->date,
                    //'item_detail' => $this->get_item_detail($noreg)
                );

            }
        }

        echo json_encode($records);
    }

    function get_sinkronisasi_sap($noreg)
    {
        $request = array();
        $datax = '';
        $sql = " SELECT a.kode_material FROM v_kode_asset_sap a WHERE a.no_reg = '{$noreg}' ";
        $data = DB::SELECT($sql);

        if($data)
        {
            $datax .= $data[0]->KODE_MATERIAL;
            foreach( $data as $k => $v )
            {
                $request[] = array
                (
                    'kode_material' => $v->KODE_MATERIAL,
                );
            }
        }

        return $datax;
    }

    function synchronize_sap(Request $request)
    {
        //echo "<pre>"; print_r($request->noreg); die();
        $no_reg = @$request->noreg;

        $sql = " SELECT * FROM TR_REG_ASSET_DETAIL WHERE NO_REG = '{$no_reg}' AND (KODE_ASSET_SAP = '' OR KODE_ASSET_SAP is null) ";

        $data = DB::SELECT($sql); 
        //echo "<pre>"; print_r($data); die();

        $params = array();

        if($data)
        {
            foreach( $data as $k => $v )
            {
                //$this->synchronize_sap_process($v);

                $proses = $this->synchronize_sap_process($v);
                //echo "<pre>"; print_r($proses['status']);die();
                
                if($proses['status']=='error')
                {
                    return response()->json(['status' => false, "message" => $proses['message']]);
                    die();
                }
                
            }

            return response()->json(['status' => true, "message" => "Synchronize SAP berhasil"]);
            //echo "<pre>"; print_r($params);

            //die();
        }
        else
        {
            return response()->json(['status' => false, "message" => "Synchronize SAP Gagal, tidak ada data"]);
        }
    }

    public function synchronize_sap_process($dt) 
    {
        $ANLA_BUKRS = substr($dt->LOKASI_BA_CODE,0,2);
        $ANLA_LIFNR = $this->get_kode_vendor($dt->NO_REG);
        $param = array(
            'ANLA_ANLKL'    => $dt->JENIS_ASSET,
            'ANLA_BUKRS'    => substr($dt->LOKASI_BA_CODE,0,2),
            'RA02S_NASSETS' => 1,
            'ANLA_TXT50'    => $dt->NAMA_ASSET_1,
            'ANLA_TXA50'    => $dt->NAMA_ASSET_2,
            'ANLH_ANLHTXT'  => $dt->NAMA_ASSET_3,
            'ANLA_SERNR'    => $dt->NO_RANGKA_OR_NO_SERI,
            'ANLA_INVNR'    => $dt->NO_MESIN_OR_IMEI,
            'ANLA_MENGE'    => $dt->QUANTITY_ASSET_SAP,
            'ANLA_MEINS'    => $dt->UOM_ASSET_SAP,
            'ANLA_AKTIV'    => $dt->CAPITALIZED_ON,
            'ANLA_DEAKT'    => $dt->DEACTIVATION_ON,
            'ANLZ_GSBER'    => $dt->LOKASI_BA_CODE,
            'ANLZ_KOSTL'    => $dt->COST_CENTER,
            'ANLZ_WERKS'    => $dt->LOKASI_BA_CODE,
            'ANLA_LIFNR'    => $this->get_kode_vendor($dt->NO_REG),
            'ANLB_NDJAR_01' => $dt->BOOK_DEPREC_01,
            'ANLB_NDJAR_02' => $dt->FISCAL_DEPREC_15
        );

        //echo "<pre>"; print_r($param); die();

        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "create_asset?ANLA_ANLKL={$dt->JENIS_ASSET}&ANLA_BUKRS={$ANLA_BUKRS}&RA02S_NASSETS=1&ANLA_TXT50={$dt->NAMA_ASSET_1}&ANLA_TXA50={$dt->NAMA_ASSET_2}&ANLH_ANLHTXT={$dt->NAMA_ASSET_3}&ANLA_SERNR={$dt->NO_RANGKA_OR_NO_SERI}&ANLA_INVNR={$dt->NO_MESIN_OR_IMEI}&ANLA_MENGE={$dt->QUANTITY_ASSET_SAP}&ANLA_MEINS={$dt->UOM_ASSET_SAP}&ANLA_AKTIV={$dt->CAPITALIZED_ON}&ANLA_DEAKT={$dt->DEACTIVATION_ON}&ANLZ_GSBER={$dt->LOKASI_BA_CODE}&ANLZ_KOSTL={$dt->COST_CENTER}&ANLZ_WERKS=$dt->LOKASI_BA_CODE&ANLA_LIFNR={$ANLA_LIFNR}&ANLB_NDJAR_01={$dt->BOOK_DEPREC_01}&ANLB_NDJAR_02={$dt->FISCAL_DEPREC_15}", 
            // http://tap-ldapdev.tap-agri.com/data-sap/create_asset
            //'param' => $param
        ));
        
        $data = $service;

        //echo "<pre>"; print_r($data); die();
        /*
        stdClass Object
        (
            [TYPE] => E
            [ID] => AA
            [NUMBER] => 108
            [MESSAGE] => Asset class 3010 does not exist (Check your entry)
            [LOG_NO] => 
            [LOG_MSG_NO] => 000000
            [MESSAGE_V1] => 3010
            [MESSAGE_V2] => 
            [MESSAGE_V3] => 
            [MESSAGE_V4] => 
        )
        */

        //echo $data->TYPE; die();

        if(!empty($data))
        {
            //echo "<pre>"; print_r($v); die();
                
            if( $data->TYPE == 'E' )
            {

                DB::beginTransaction();

                try 
                {    
                    $sql = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->NO_REG_ITEM}','{$data->TYPE}','{$data->ID}','{$data->NUMBER}','{$data->MESSAGE}','{$data->MESSAGE_V1}','{$data->MESSAGE_V2}','{$data->MESSAGE_V3}','{$data->MESSAGE_V4}') ";
                    //echo $sql; die();
                    DB::INSERT($sql); 
                    DB::commit();
                    
                    $result = array('status'=>'error','message'=> ''.$data->MESSAGE.' (No Reg Item: '.$dt->NO_REG_ITEM.')');
                    return $result;
                    //return false;
                }
                catch (\Exception $e) 
                {
                    DB::rollback();
                    $result = array('status'=>'error','message'=>$e->getMessage());
                    return $result;
                    //return false;
                }
            }
            else
            {
                
                DB::beginTransaction();
                try 
                {   
                    $user_id = Session::get('user_id');

                    //UPDATE TR_REG_ASSET
                    $sql_1 = " UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_SAP = '{$data->MESSAGE_V1}', UPDATED_BY = '{$user_id}', UPDATED_AT = current_timestamp() WHERE NO_REG = '{$dt->NO_REG}' AND NO_REG_ITEM = '{$dt->NO_REG_ITEM}' ";
                     //echo $sql_1; die();
                    DB::UPDATE($sql_1);

                    $sql_2 = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->NO_REG_ITEM}','{$data->TYPE}','{$data->ID}','{$data->NUMBER}','{$data->MESSAGE}','{$data->MESSAGE_V1}','{$data->MESSAGE_V2}','{$data->MESSAGE_V3}','{$data->MESSAGE_V4}') ";
                    //echo $sql_2; die();
                    DB::INSERT($sql_2);
                    
                    DB::commit();
                    return true;
                }
                catch (\Exception $e) 
                {
                    DB::rollback();
                    return false;
                    //die();
                }
                
            }
            
            //die();
            
            //return false;
        }
        else
        {
            return false;
        }

        /*
        $datax = array();
        if(isset( $data->EBELN)) 
        {
            return response()->json(array('data' => $data));
        } 
        else 
        {
            return response()->json(array('data' => array())); 
        }
        */
    }

    public function get_kode_vendor($noreg)
    {
        $sql = "SELECT KODE_VENDOR FROM TR_REG_ASSET WHERE NO_REG = '{$noreg}' ";
        $data = DB::SELECT($sql);
        if($data){ $dt = $data[0]->KODE_VENDOR; }else{ $dt = '0'; }
        return $dt;
    }
}
