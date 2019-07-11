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

        /*
        if (AccessRight::granted() == false)
            return response(view('errors.403'), 403);
        */

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
                    'no_reg' => trim($v->NO_REG),
                    'type_transaksi' => trim($type_transaksi[$v->TYPE_TRANSAKSI]),
                    'po_type' => trim($po_type[$v->PO_TYPE]),
                    'business_area' => trim($v->CODE_AREA).' - '.trim($v->NAME_AREA),
                    'requestor' => trim($v->REQUESTOR),
                    'tanggal_reg' => trim($v->TANGGAL_REG),
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
                    'id' => trim($v->ID),
                    'no_po' => trim($v->NO_PO),
                    'item' => trim($v->ITEM_PO),
                    'qty' => trim($v->QUANTITY_SUBMIT),
                    //'qty' => $this->get_total_qty($noreg),
                    'kode' => trim($v->KODE_MATERIAL),
                    'nama' => trim($v->NAMA_MATERIAL)
                );
            }
        }

        return $request;
    }

    function get_total_qty($noreg)
    {
        $sql = " SELECT COUNT(*) AS JML FROM TR_REG_ASSET_DETAIL WHERE NO_REG = '{$noreg}' AND DELETED != 'X' ";
        $data = DB::SELECT($sql);
        //echo "<pre>"; print_r($data);die();
        return trim($data[0]->JML);
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
        $sql = " SELECT a.*, b.jenis_asset_description AS JENIS_ASSET_NAME, c.group_description AS GROUP_NAME, d.subgroup_description AS SUB_GROUP_NAME, e.KODE_VENDOR, e.NAMA_VENDOR, e.BUSINESS_AREA AS BUSINESS_AREA, e.PO_TYPE AS PO_TYPE
                    FROM TR_REG_ASSET_DETAIL a 
                        LEFT JOIN TM_JENIS_ASSET b ON a.jenis_asset = b.jenis_asset_code 
                        LEFT JOIN TM_GROUP_ASSET c ON a.group = c.group_code AND a.jenis_asset = c.jenis_asset_code
                        LEFT JOIN TM_SUBGROUP_ASSET d ON a.sub_group = d.subgroup_code AND a.group = d.group_code
                        LEFT JOIN TR_REG_ASSET e ON a.NO_REG = e.NO_REG
                    WHERE a.no_reg = '{$noreg}' AND a.asset_po_id = '{$id}' AND (a.DELETED is null OR a.DELETED = '')
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
                    'id' => trim($v->ID),
                    'no_po' => trim($v->NO_PO),
                    'asset_po_id' => trim($v->ASSET_PO_ID),
                    'tgl_po' => trim($v->CREATED_AT),
                    'kondisi_asset' => trim(@$kondisi[$v->KONDISI_ASSET]),
                    'jenis_asset' => trim($v->JENIS_ASSET).'-'.trim($v->JENIS_ASSET_NAME),
                    'group' => trim($v->GROUP).'-'.trim($v->GROUP_NAME),
                    'sub_group' => trim($v->SUB_GROUP).'-'.trim($v->SUB_GROUP_NAME),
                    'nama_asset' => trim($v->NAMA_ASSET),
                    'merk' => trim($v->MERK),
                    'spesifikasi_or_warna' => trim($v->SPESIFIKASI_OR_WARNA),
                    'no_rangka_or_no_seri' => trim($v->NO_RANGKA_OR_NO_SERI),
                    'no_mesin_or_imei' => trim($v->NO_MESIN_OR_IMEI),
                    'lokasi' => trim($v->LOKASI_BA_DESCRIPTION),
                    'tahun' => trim($v->TAHUN_ASSET),
                    'info' => trim($v->INFORMASI),
                    'file' => $this->get_asset_file($v->ID,$noreg),
                    'nama_asset_1' => trim($v->NAMA_ASSET_1),
                    'nama_asset_2' => trim($v->NAMA_ASSET_2),
                    'nama_asset_3' => trim($v->NAMA_ASSET_3),
                    'quantity_asset_sap' => trim($v->QUANTITY_ASSET_SAP),
                    'uom_asset_sap' => trim($v->UOM_ASSET_SAP),
                    'capitalized_on' => trim($v->CAPITALIZED_ON),
                    'deactivation_on' => trim($v->DEACTIVATION_ON),
                    'cost_center' => trim($v->COST_CENTER),
                    'book_deprec_01' => trim($v->BOOK_DEPREC_01),
                    'fiscal_deprec_15' => trim($v->FISCAL_DEPREC_15),
                    'group_deprec_30' => trim($v->GROUP_DEPREC_30),
                    'no_reg_item' => trim($v->NO_REG_ITEM),
                    'vendor' => trim($v->KODE_VENDOR).'-'.trim($v->NAMA_VENDOR),
                    'business_area' => trim($v->BUSINESS_AREA),
                    'kode_asset_sap' => trim($v->KODE_ASSET_SAP),
                    'kode_asset_controller' => trim($v->KODE_ASSET_CONTROLLER),
                    'kode_asset_ams' => trim($v->KODE_ASSET_AMS),
                    'po_type' => trim($v->PO_TYPE),
                    'gi_number' => trim($v->GI_NUMBER),
                    'gi_year' => trim($v->GI_YEAR)
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

        //$total_asset_now = 100; //$this->getInfo;

        DB::beginTransaction();

        try 
        {
            $user_id = Session::get('user_id');

            /*
            if( $total_asset_now == 1 )
            {
                //JALANKAN PROSEDURE REJECT
                DB::SELECT('call update_approval("'.$no_registrasi.'", "'.$user_id.'","'.$status.'", "'.$note.'", "'.$role_id.'", "'.$asset_controller.'")');
            }
            */

            $sql = " UPDATE TR_REG_ASSET_DETAIL SET DELETED = 'X', UPDATED_AT = current_timestamp(), UPDATED_BY = '{$user_id}' WHERE ID = $id ";
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
            $deactivation_on = $request->deactivation_on;
            if($deactivation_on == '')
            { $do = "deactivation_on = NULL,"; }else
            { $do = "deactivation_on = '{$request->deactivation_on}',"; }

            $sql = " UPDATE TR_REG_ASSET_DETAIL 
                        SET 
                            nama_asset_1 = '{$request->nama_asset_1}',
                            nama_asset_2 = '{$request->nama_asset_2}',
                            nama_asset_3 = '{$request->nama_asset_3}',
                            quantity_asset_sap = '{$request->quantity}',
                            uom_asset_sap = '{$request->uom}',
                            capitalized_on = '{$request->capitalized_on}',
                            ".$do."
                            cost_center = '{$request->cost_center}',
                            book_deprec_01 = '{$request->book_deprec_01}',
                            fiscal_deprec_15 = '{$request->fiscal_deprec_15}',
                            group_deprec_30 = '{$request->book_deprec_01}',
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

    function validasi_input_all_io(Request $request, $status, $noreg)
    {
        $req = $request->all();
        //echo "<pre>"; print_r($req); die();

        $request_ka = json_decode($req['request_ka']);
        $no_registrasi = str_replace("-", "/", $noreg);
        $list_kode_asset = "";

        $sql = " SELECT * FROM TR_REG_ASSET_DETAIL WHERE NO_REG = '{$no_registrasi}' AND (KODE_ASSET_CONTROLLER is null OR KODE_ASSET_CONTROLLER = '' ) AND (DELETED is null OR DELETED = '') AND JENIS_ASSET IN ('E4030','4030', '4010') ";
        $dt = DB::SELECT($sql); 
        //echo "<pre>"; print_r($dt);
        //die();

        if(!empty($dt))
        {
            foreach($dt as $k => $v)
            {
                $list_kode_asset .= $v->KODE_ASSET_SAP.",";
            }
            $result = array('status'=>false,'message'=> 'Kode Aset Controller (KODE ASET : '.rtrim($list_kode_asset,',').') belum diisi');
        }
        else
        {
            $result = array('status'=>true,'message'=> '');
        }
        
        return $result; 
    }

    function update_status(Request $request, $status, $noreg)
    {
        $req = $request->all();
        $jenis_dokumen = $req['po-type'];
        $rolename = Session::get('role');
        
        if($jenis_dokumen == 'AMP')
        {
            if($status != 'R')
            {
                if($rolename == 'AC')
                {
                    $validasi_io = $this->get_validasi_io_amp($request, $status, $noreg);
                } 
                else
                {
                    $validasi_io['status'] = true;
                }
            }
            else
            {
                $validasi_io['status'] = true;            
            }

            if( $validasi_io['status'] == false )
            {
                return response()->json(['status' => false, "message" => $validasi_io['message']]);
            }
            else
            {
                /* AMP PROCESS */

                if( $status != 'R' )
                {
                    if($rolename == 'AC' )
                    {
                        $validasi_input_all_io = $this->validasi_input_all_io($request, $status, $noreg);
                
                        if(!$validasi_input_all_io['status'])
                        {
                            return response()->json(['status' => false, "message" => $validasi_input_all_io['message']] );
                            die();
                        }
                    }
                }

                //echo "masuk ke validasi last approve"; die();            

                $no_registrasi = str_replace("-", "/", $noreg);
                $user_id = Session::get('user_id');
                $note = $request->parNote;
                $role_id = Session::get('role_id'); //get role id user
                $asset_controller = ''; //get asset controller 
                //echo $note;die();

                $validasi_last_approve = $this->get_validasi_last_approve($no_registrasi);

                if( $validasi_last_approve == 0 )
                {
                    DB::beginTransaction();
                    
                    try 
                    {
                        DB::SELECT('CALL update_approval("'.$no_registrasi.'", "'.$user_id.'","'.$status.'", "'.$note.'", "'.$role_id.'", "'.$asset_controller.'")');
                        DB::commit();
                        return response()->json(['status' => true, "message" => 'Data is successfully ' . ($no_registrasi ? 'updated' : 'update')]);
                    } 
                    catch (\Exception $e) 
                    {
                        DB::rollback();
                        return response()->json(['status' => false, "message" => $e->getMessage()]);
                    }
                }    
                else
                {
                    $validasi_check_gi = true;

                    if($validasi_check_gi)
                    {
                        DB::beginTransaction();
                        try 
                        {
                            DB::SELECT('CALL complete_document("'.$no_registrasi.'", "'.$user_id.'")');
                            DB::commit();
                            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($no_registrasi ? 'updated' : 'update')]);
                        } 
                        catch (\Exception $e) 
                        {
                            DB::rollback();
                            return response()->json(['status' => false, "message" => $e->getMessage()]);
                        }
                    }
                    else
                    {
                        return response()->json(['status' => false, "message" => "Error Validasi GI"]);
                    }
                   
                }
            }
        }
        else
        {
            /* SAP PROCESS */ 

            if($status != 'R')
            {
                if($rolename == 'AC')
                {
                    $validasi_io = $this->get_validasi_io($request);
                } 
                else
                {
                    $validasi_io['status'] = true;
                }
            }
            else
            {
                $validasi_io['status'] = true;            
            }
            

            if( $validasi_io['status'] == false )
            {
                return response()->json(['status' => false, "message" => $validasi_io['message']]);
            }
            else
            {
                if( $status != 'R' )
                {
                    if($rolename == 'AC' )
                    {
                        $validasi_input_all_io = $this->validasi_input_all_io($request, $status, $noreg);
                
                        if(!$validasi_input_all_io['status'])
                        {
                            return response()->json(['status' => false, "message" => $validasi_input_all_io['message']] );
                            die();
                        }
                    }
                }

                //echo "masuk ke validasi last approve"; die();            

                $no_registrasi = str_replace("-", "/", $noreg);
                $user_id = Session::get('user_id');
                $note = $request->parNote;
                $role_id = Session::get('role_id'); //get role id user
                $asset_controller = ''; //get asset controller 
                //echo $note;die();

                $validasi_last_approve = $this->get_validasi_last_approve($no_registrasi);

                if( $validasi_last_approve == 0 )
                {
                    DB::beginTransaction();
                    
                    try 
                    {
                        DB::SELECT('CALL update_approval("'.$no_registrasi.'", "'.$user_id.'","'.$status.'", "'.$note.'", "'.$role_id.'", "'.$asset_controller.'")');
                        DB::commit();
                        return response()->json(['status' => true, "message" => 'Data is successfully ' . ($no_registrasi ? 'updated' : 'update')]);
                    } 
                    catch (\Exception $e) 
                    {
                        DB::rollback();
                        return response()->json(['status' => false, "message" => $e->getMessage()]);
                    }
                }    
                else
                {
                    //echo "3<pre>"; print_r($request->all()); die();

                    $validasi_check_gi = $this->get_validasi_check_gi($request,$no_registrasi);
                    //echo "1<pre>"; print_r($validasi_check_gi); die();

                    if($validasi_check_gi['status']=='success')
                    {
                        DB::beginTransaction();
                        try 
                        {
                            DB::SELECT('CALL complete_document("'.$no_registrasi.'", "'.$user_id.'")');
                            DB::commit();
                            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($no_registrasi ? 'updated' : 'completed')]);
                        } 
                        catch (\Exception $e) 
                        {
                            DB::rollback();
                            return response()->json(['status' => false, "message" => $e->getMessage()]);
                        }
                    }
                    else
                    {
                        return response()->json(['status' => false, "message" => $validasi_check_gi['message'] ]);
                    }
                   
                }
            }
        }           
    }

    function get_validasi_check_gi(Request $request, $noreg)
    {
        $req = $request->all();
        
        $request_gi = json_decode($req['request_gi']);
        //echo "3<pre>"; print_r($request_gi); die();

        if(!empty($request_gi))
        {
            foreach( $request_gi as $k => $v )
            {
                //echo "1<pre>"; print_r($v);
                
                $proses = $this->proses_validasi_check_gi($noreg,$v);

                if($proses['status']=='error')
                {
                    $result = array('status'=>'error','message'=> $proses['message']);
                    return $result;
                    die();
                }
                
            }
            //die();

            $result = array('status'=>'success','message'=> 'SUCCESS');
            return $result;
        }
        else
        {

            //Cek sekali lagi utk penginputan GI Number dan GI Year
            $sql = " SELECT * FROM TR_REG_ASSET_DETAIL WHERE NO_REG = '".$noreg."' AND ((GI_NUMBER is null OR GI_NUMBER = '') OR (GI_YEAR is null OR GI_YEAR = '')) ";
            $data = DB::SELECT($sql);
            //echo "4<pre>"; print_r($data); die();
            if(!empty($data))
            {
                $message = '';
                foreach($data as $a => $b)
                {
                    //echo "2<pre>"; print_r($b);
                    $message .= "".$b->KODE_ASSET_SAP.",";
                }
                //die();

                $result = array('status'=>'error','message'=> 'Kode GI Number & Year belum diisi (Kode Asset SAP : '.rtrim($message,',').' ) ' );
                return $result;
            }
            else
            {
                $result = array('status'=>'success','message'=> 'Check GI Success');
                return $result;
            }
            //$result = array('status'=>'error','message'=> 'Kode GI Number & Year belum diisi');
            //return $result;
        }
             
    }

    function proses_validasi_check_gi($noreg, $data)
    {
        //echo "1<pre>"; print_r($data); die();
        /*
        stdClass Object
        (
            [gi_number] => 1
            [gi_year] => 2
            [no_registrasi] => 19.07/AMS/PDFA/00042
        )
        */

        $gi_number = $data->gi_number;
        $gi_year = $data->gi_year;
        $ka_sap = $data->kode_sap;

        $user_id = Session::get('user_id');
        //echo "1".$nore.'====='.$ka_sap.'===='.$ka_con;
        
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "check_gi?MBLNR=".$gi_number."&MJAHR=".$gi_year."&ANLN1=1&ANLN2=2", 
        ));
        
        //$data = $service;
        $data = 1;
        
        //echo "2<pre>"; print_r($data); die();
        /*
        stdClass Object
        (
            [TYPE] => E
            [ID] => 
            [NUMBER] => 002
            [MESSAGE] => Number GI Not Found !!
            [LOG_NO] => 
            [LOG_MSG_NO] => 000000
            [MESSAGE_V1] => 
            [MESSAGE_V2] => 
            [MESSAGE_V3] => 
            [MESSAGE_V4] => 
        )
        */

        //if( $data->TYPE == 'S' )
        if($data==1)
        {
            
            DB::beginTransaction();
            try 
            {   
                $sql = " UPDATE TR_REG_ASSET_DETAIL SET GI_NUMBER = '{$gi_number}', GI_YEAR = '{$gi_year}', UPDATED_AT = current_timestamp(), UPDATED_BY = '{$user_id}' WHERE NO_REG = '{$noreg}' AND KODE_ASSET_SAP = '{$ka_sap}' ";
                //echo $sql; die();
                DB::UPDATE($sql);
                DB::commit();

                $result = array('status'=>'success','message'=> "Validation Success");
            }
            catch (\Exception $e) 
            {
                DB::rollback();
                $result = array('status'=>'error','message'=>$e->getMessage());
            }
            
            //$result = array('status'=>'success','message'=> "Validation Success");
        }
        else
        {
            $result = array('status'=>'error','message'=> $data->MESSAGE.' (GI Number:'.$gi_number.' & Year : '.$gi_year.' )');
        }
        return $result;
    }

    public function get_validasi_last_approve($noreg)
    {
        $sql = "SELECT COUNT(*) AS jml FROM v_history WHERE status_dokumen = 'Disetujui' AND document_code = '{$noreg}' ";
        $data = DB::SELECT($sql);
        
        if($data)
        { 
            $dt = $data[0]->jml; 
        }
        else
        { 
            $dt = '0'; 
        }
        
        return $dt;
    }

    function get_validasi_io(Request $request)
    {
        $req = $request->all();
        $request_ka = json_decode($req['request_ka']);

        $noreg = $req['no-reg'];
        $jenis_dokumen = $req['po-type'];

        if(!empty($request_ka))
        {
            foreach( $request_ka as $k => $v )
            {

                $proses = $this->validasi_io_proses_v2($noreg,$v);

                if($proses['status']=='error')
                {
                    $result = array('status'=>false,'message'=> $proses['message']);
                    return $result;
                    die();
                }
            }
            //die();

            $result = array('status'=>true,'message'=> 'SUCCESS');
            return $result;
        }
        else
        {
            //Cek Data Jenis Asset harus kendaraan
            $sql = " SELECT * FROM TR_REG_ASSET_DETAIL WHERE NO_REG = '".$noreg."' AND JENIS_ASSET IN ('E4030','4030', '4010') AND (KODE_ASSET_SAP != '' OR KODE_ASSET_SAP IS NOT NULL) ";
            $dt = DB::SELECT($sql); 
            //echo "4<pre>"; print_r($dt);die();

            if(!empty($dt))
            {
                $message = '';
                foreach($dt as $k => $v)
                {
                    //echo "5<pre>"; print_r($v);
                    $message .= $v->KODE_ASSET_SAP.",";
                }
                //die();
                $result = array('status'=>false,'message'=> 'Kode IO Asset Controller belum diisi! ( Kode Asset SAP : '.rtrim($message,',').' )');
                return $result;
            }
            else
            {
                $result = array('status'=>true,'message'=> 'Success');
                return $result;   
            }
        }
    }

    function get_validasi_io_amp(Request $request , $status, $no_reg)
    {
        $req = $request->all();
        //echo "2<pre>"; print_r($req); die();

        $request_ka = json_decode($req['request_ka']);

        $noreg = $req['no-reg'];

        if(!empty($request_ka))
        {
            foreach( $request_ka as $k => $v )
            {
                $proses = $this->validasi_io_proses_amp($noreg, $v);
            
                if($proses['status']=='error')
                {
                    $result = array('status'=>false,'message'=> $proses['message']);
                    return $result;
                    die();
                }
            }
            $result = array('status'=>true,'message'=> 'Validasi IO AMP Success');
            return $result;
        }
        else
        {
            $result = array('status'=>false,'message'=> 'Kode Aset Controller belum diisi (di Item Detail)');
            return $result;
        }
    }

    function validasi_io_proses_amp($noreg, $data)
    {
        $ka_con = $data->kode_aset_controller;
        $ka_sap = $data->kode_aset_sap;
        $user_id = Session::get('user_id');
        //echo "2<br/>".$noreg.'====='.$ka_sap.'===='.$ka_con;
        
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "check_io?AUFNR=$ka_con&AUFUSER3=$ka_sap", 
        ));
        
        //$data = $service;
        $data = 1;
        //echo "<pre>"; print_r($data); die();

        //if( $data->TYPE == 'S' )
        if($data==1)
        {
            DB::beginTransaction();
            try 
            {   
                $sql = " UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_CONTROLLER = '{$ka_con}', UPDATED_AT = current_timestamp(), UPDATED_BY = '{$user_id}' WHERE NO_REG = '{$noreg}' AND ID = '{$ka_sap}' ";
                DB::UPDATE($sql);
                DB::commit();

                $result = array('status'=>'success','message'=> "SUKSES UPDATE KODE ASET");
            }
            catch (\Exception $e) 
            {
                DB::rollback();
                $result = array('status'=>'error','message'=>$e->getMessage());
            }
        }
        else
        {    
            $result = array('status'=>'error','message'=> $data->MESSAGE.' (Kode Aset Controller:'.$ka_con.')');
        }

        return $result;
    }

    function validasi_io_proses_v2($noreg, $data)
    {
        //echo "<pre>"; print_r($data); die();
        /*
        stdClass Object
            (
                [kode_aset_controller] => 1
                [kode_aset_sap] => 40100246
                [no_registrasi] => 19.07/AMS/PDFA/00038
            )
        */

        $ka_con = $data->kode_aset_controller;
        $ka_sap = $data->kode_aset_sap;

        $user_id = Session::get('user_id');
        //echo "1".$nore.'====='.$ka_sap.'===='.$ka_con;
        
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "check_io?AUFNR=$ka_con&AUFUSER3=$ka_sap", 
        ));
        
        $data = $service;
        //$data = 1;
        
        //echo "<pre>"; print_r($data); die();

        if( $data->TYPE == 'S' )
        //if($data==1)
        {
            DB::beginTransaction();
            try 
            {   
                $sql = " UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_CONTROLLER = '{$ka_con}', UPDATED_AT = current_timestamp(), UPDATED_BY = '{$user_id}' WHERE NO_REG = '{$noreg}' AND KODE_ASSET_SAP = '{$ka_sap}' ";
                //echo $sql; die();
                DB::UPDATE($sql);
                DB::commit();

                $result = array('status'=>'success','message'=> "SUKSES UPDATE KODE ASET");
            }
            catch (\Exception $e) 
            {
                DB::rollback();
                $result = array('status'=>'error','message'=>$e->getMessage());
            }

        }
        else
        {
            
            $result = array('status'=>'error','message'=> $data->MESSAGE.' (Kode Aset Controller:'.$ka_con.')');
        }
        

        return $result;
    }

    /*
    function validasi_io_proses($noreg, $req, $i)
    {
        //echo "<pre>"; dd($req); die();
        //echo $ka_sap.'===='.$ka_con; die();

        $ka_sap = $req['kode_aset_sap-'.$i.''];
        $ka_con = $req['kode_aset_controller-'.$i.''];
        //echo "1".$ka_con; die();

        if( $ka_con == '' )
        {
            $result = array('status'=>'error','message'=> 'Kode Asset Controller Kosong ('.$ka_sap.')' );
            return $result;
        }

        $user_id = Session::get('user_id');
        //echo "1".$nore.'====='.$ka_sap.'===='.$ka_con;
        
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "check_io?AUFNR=$ka_con&AUFUSER3=$ka_sap", 
        ));
        
        //$data = $service;
        $data = 1;
        
        //echo "<pre>"; print_r($data); die();

        //if( $data->TYPE == 'S' )
        if($data==1)
        {
            DB::beginTransaction();
            try 
            {   
                $sql = " UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_CONTROLLER = '{$ka_con}', UPDATED_AT = current_timestamp(), UPDATED_BY = '{$user_id}' WHERE NO_REG = '{$noreg}' AND KODE_ASSET_SAP = '{$ka_sap}' ";
                //echo $sql; die();
                DB::UPDATE($sql);
                
                DB::commit();
                $result = array('status'=>'success','message'=> "SUKSES UPDATE KODE ASET");
            }
            catch (\Exception $e) 
            {
                DB::rollback();
                $result = array('status'=>'error','message'=>$e->getMessage());
            }
        }
        else
        {
            
            $result = array('status'=>'error','message'=> $data->MESSAGE.' (Kode Aset Controller:'.$ka_con.')');
        }
        

        return $result;
    }
    */

    /*
    function validasi_io_proses_v1($noreg, $ka_sap, $ka_con)
    {
        //echo $ka_sap.'===='.$ka_con; die();
        if( $ka_con == '' )
        {
            $result = array('status'=>'error','message'=> 'Kode Asset Controller Kosong ('.$ka_sap.')' );
            return $result;
        }

        $user_id = Session::get('user_id');
        //echo "1".$nore.'====='.$ka_sap.'===='.$ka_con;
        
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "check_io?AUFNR=$ka_con&AUFUSER3=$ka_sap", 
        ));
        
        //$data = $service;
        //$data = 1;
        
        //echo "<pre>"; print_r($data); die();

        if( $data->TYPE == 'S' )
        //if($data==1)
        {
            DB::beginTransaction();
            try 
            {   
                $sql = " UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_CONTROLLER = '{$ka_con}', UPDATED_AT = current_timestamp(), UPDATED_BY = '{$user_id}' WHERE NO_REG = '{$noreg}' AND KODE_ASSET_SAP = '{$ka_sap}' ";
                //echo $sql; die();
                DB::UPDATE($sql);
                
                DB::commit();
                $result = array('status'=>'success','message'=> "SUKSES UPDATE KODE ASET");
            }
            catch (\Exception $e) 
            {
                DB::rollback();
                $result = array('status'=>'error','message'=>$e->getMessage());
            }
        }
        else
        {
            
            $result = array('status'=>'error','message'=> $data->MESSAGE.' (Kode Aset Controller:'.$ka_con.')');
        }
        

        return $result;
    }
    */

    function log_history($id)
    {
        $noreg = str_replace("-", "/", $id);

        $records = array();

        /*$sql = "SELECT a.*, date_format(a.date,'%d-%m-%Y %h:%i:%s') AS date2 FROM v_history a WHERE a.document_code = '{$noreg}' ORDER BY a.date";*/
        $sql = "SELECT document_code,user_id,name,area_code,status_approval,notes,date FROM v_history_approval WHERE document_code = '{$noreg}' ORDER BY -date ASC, date ASC ";

        $data = DB::SELECT($sql);
        //echo "3<pre>"; print_r($data); die();
        
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
                    'kode_material' => trim($v->KODE_MATERIAL),
                );
            }
        }

        return $datax;
    }

    function synchronize_sap(Request $request)
    {
        //$req = $request->all();
        //echo "<pre>"; print_r($req); die();
        //echo "<pre>"; print_r($request->noreg); die();
        $no_reg = @$request->noreg;

        $sql = " SELECT a.*, date_format(a.CAPITALIZED_ON,'%d.%m.%Y') AS CAPITALIZED_ON, date_format(a.DEACTIVATION_ON,'%d.%m.%Y') AS DEACTIVATION_ON FROM TR_REG_ASSET_DETAIL a WHERE a.NO_REG = '{$no_reg}' AND (a.KODE_ASSET_SAP = '' OR a.KODE_ASSET_SAP is null) AND (a.DELETED is null OR a.DELETED = '') ";
        //echo $sql; die();

        $data = DB::SELECT($sql); 
        //echo "<pre>"; print_r($data); die();

        $params = array();

        if($data)
        {
            foreach( $data as $k => $v )
            {
                $proses = $this->synchronize_sap_process($v);
                //echo "<pre>"; print_r($proses); die();
                
                if($proses['status']=='error')
                {
                    return response()->json(['status' => false, "message" => $proses['message']]);
                    die();
                }
                /*
                else
                {
                    return response()->json(['status' => false, "message" => "Client Error"]);
                    die();
                }
                */

                //return response()->json(['status' => true, "message" => "Synchronize SAP Success "]);
            }

            #### PROSES CREATE KODE ASSET AMS 
            $execute_create_kode_asset_ams = true; 
            //$execute_create_kode_asset_ams = $this->execute_create_kode_asset_ams($v);
            if( $execute_create_kode_asset_ams )
            {
                return response()->json(['status' => true, "message" => "Synchronize SAP success"]);
            }
            else
            {
                $sql = " UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_SAP = '' WHERE NO_REG = '{$no_reg}' "; 
                DB::UPDATE($sql);

                return response()->json(['status' => false, "message" => "Create Kode Asset AMS failed"]);
            }
            
            //echo "<pre>"; print_r($params);

            //die();
        }
        else
        {
            $sql = " UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_SAP = '' WHERE NO_REG = '{$no_reg}' "; 
                DB::UPDATE($sql);
            return response()->json(['status' => false, "message" => "Synchronize SAP failed, data not found"]);
        }
    }

    function synchronize_amp(Request $request)
    {
        //echo "<pre>"; print_r($request->noreg); die();
        $no_reg = @$request->noreg;
        //echo $no_reg; 

        #CREATE KODE ASSET FAMS (tunggu mas Dega)

        return response()->json(['status' => true, "message" => "Synchronize AMP berhasil"]);
    }

    public function synchronize_sap_process($dt) 
    {
        /*
        ##### PROSES 1. VALIDASI ALL INPUT KODE ASSET SAP (JIKA SERVICE ERROR PAKAI VALIDASI INI)
        $list_kode_asset = '';
        $sql_validasi_kas = " SELECT * FROM TR_REG_ASSET_DETAIL WHERE NO_REG = '{$dt->NO_REG}' AND (COST_CENTER is null OR COST_CENTER = '') AND (DELETED is null OR DELETED = '') ";
        $datax = DB::SELECT($sql_validasi_kas); 
        //echo "1<pre>"; print_r($datax);die();

        if(!empty($datax))
        {
            foreach($datax as $kk => $vv)
            {
                $list_kode_asset .= $vv->KODE_MATERIAL." - NO. REG ITEM : ".$vv->NO_REG_ITEM.",";
            }

            $result = array('status'=>'error','message'=> 'Kode Aset Controller (KODE MATERIAL : '.rtrim($list_kode_asset,',').') belum diisi');
            return $result;     
        }
        */

        ##### PROSES 2
        $ANLA_BUKRS = substr($dt->BA_PEMILIK_ASSET,0,2);
        $ANLA_LIFNR = $this->get_kode_vendor($dt->NO_REG);

        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "create_asset?ANLA_ANLKL={$dt->JENIS_ASSET}&ANLA_BUKRS={$ANLA_BUKRS}&RA02S_NASSETS=1&ANLA_TXT50={$dt->NAMA_ASSET_1}&ANLA_TXA50={$dt->NAMA_ASSET_2}&ANLH_ANLHTXT={$dt->NAMA_ASSET_3}&ANLA_SERNR={$dt->NO_RANGKA_OR_NO_SERI}&ANLA_INVNR={$dt->NO_MESIN_OR_IMEI}&ANLA_MENGE={$dt->QUANTITY_ASSET_SAP}&ANLA_MEINS={$dt->UOM_ASSET_SAP}&ANLA_AKTIV={$dt->CAPITALIZED_ON}&ANLA_DEAKT={$dt->DEACTIVATION_ON}&ANLZ_GSBER={$dt->BA_PEMILIK_ASSET}&ANLZ_KOSTL={$dt->COST_CENTER}&ANLZ_WERKS=$dt->BA_PEMILIK_ASSET&ANLA_LIFNR={$ANLA_LIFNR}&ANLB_NDJAR_01={$dt->BOOK_DEPREC_01}&ANLB_NDJAR_02={$dt->FISCAL_DEPREC_15}", 
        ));
        
        $data = $service;
        
        if( !empty($data->item->TYPE) )
        {
            #2
            if( $data->item->TYPE == 'S' )
            {
                $user_id = Session::get('user_id');
                $asset_controller = $this->get_asset_controller($user_id,$dt->LOKASI_BA_CODE);

                DB::beginTransaction();
                try 
                {   
                    //1. ADD KODE_ASSET_SAP & ASSET_CONTROLLER TR_REG_ASSET 
                    $sql_1 = " UPDATE TR_REG_ASSET_DETAIL SET ASSET_CONTROLLER = '{$asset_controller}', KODE_ASSET_SAP = '".$data->item->MESSAGE_V1."', UPDATED_BY = '{$user_id}', UPDATED_AT = current_timestamp() WHERE NO_REG = '{$dt->NO_REG}' AND ASSET_PO_ID = '{$dt->ASSET_PO_ID}' AND NO_REG_ITEM = '{$dt->NO_REG_ITEM}' ";
                    DB::UPDATE($sql_1);

                    //2. INSERT LOG
                    $sql_2 = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,asset_po_id,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->ASSET_PO_ID}','{$dt->NO_REG_ITEM}','".$data->item->TYPE."','".$data->item->ID."','".$data->item->NUMBER."','".$data->item->MESSAGE."','".$data->item->MESSAGE_V1."','".$data->item->MESSAGE_V2."','".$data->item->MESSAGE_V3."','".$data->item->MESSAGE_V4."') ";
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
            else 
            {
                DB::beginTransaction();

                try 
                {    
                    $sql = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,asset_po_id,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->ASSET_PO_ID}','{$dt->NO_REG_ITEM}','".$data->item->TYPE."','".$data->item->ID."','".$data->item->NUMBER."','".$data->item->MESSAGE."','".$data->item->MESSAGE_V1."','".$data->item->MESSAGE_V2."','".$data->item->MESSAGE_V3."','".$data->item->MESSAGE_V4."') ";
                    
                    DB::INSERT($sql); 
                    DB::commit();
                    
                    $result = array('status'=>'error','message'=> ''.$data->item->MESSAGE.' (No Reg Item: '.$dt->NO_REG_ITEM.')');
                    return $result;                 
                }
                catch (\Exception $e) 
                {
                    DB::rollback();
                    $result = array('status'=>'error','message'=>$e->getMessage());
                    return $result;
                }
            }         
        }
        
        if( !empty($data->item[0]->TYPE) ) 
        {
            //RETURN ARRAY LEBIH DARI 1 ROW
            $result = array();
            $message = '';

            //echo "20<pre>"; count($data); die();

            foreach($data->item as $k => $v)
            {
                //echo "20<pre>"; print_r($v);
                
                if( $v->TYPE == 'S' && $v->ID == 'AA' && $v->NUMBER == 228 )
                {
                    $message .= $v->MESSAGE.',';
                    $result = array(
                        'TYPE' => 'S',
                        'ID' => $v->ID,
                        'NUMBER' => $v->NUMBER,
                        'MESSAGE' => $message,
                        'LOG_NO' => $v->LOG_NO,
                        'LOG_MSG_NO' => $v->LOG_MSG_NO,
                        'MESSAGE_V1' => $v->MESSAGE_V1,
                        'MESSAGE_V2' => $v->MESSAGE_V2,
                        'MESSAGE_V3' => $v->MESSAGE_V3,
                        'MESSAGE_V4' => $v->MESSAGE_V4
                    );
                }
                else
                {
                    $message .= $v->MESSAGE.',';
                    $result = array(
                        'TYPE' => 'E',
                        'ID' => $v->ID,
                        'NUMBER' => $v->NUMBER,
                        'MESSAGE' => $message,
                        'LOG_NO' => $v->LOG_NO,
                        'LOG_MSG_NO' => $v->LOG_MSG_NO,
                        'MESSAGE_V1' => $v->MESSAGE_V1,
                        'MESSAGE_V2' => $v->MESSAGE_V2,
                        'MESSAGE_V3' => $v->MESSAGE_V3,
                        'MESSAGE_V4' => $v->MESSAGE_V4
                    );
                }
                
            }
            //die();
            

            if( $result['TYPE'] == 'S' )
            {
                $user_id = Session::get('user_id');
                $asset_controller = $this->get_asset_controller($user_id,$dt->LOKASI_BA_CODE);

                DB::beginTransaction();
                try 
                {   
                    //1. ADD KODE_ASSET_SAP & ASSET_CONTROLLER TR_REG_ASSET 
                    $sql_1 = " UPDATE TR_REG_ASSET_DETAIL SET ASSET_CONTROLLER = '{$asset_controller}', KODE_ASSET_SAP = '".$result['MESSAGE_V1']."', UPDATED_BY = '{$user_id}', UPDATED_AT = current_timestamp() WHERE NO_REG = '{$dt->NO_REG}' AND ASSET_PO_ID = '{$dt->ASSET_PO_ID}' AND NO_REG_ITEM = '{$dt->NO_REG_ITEM}' ";
                    DB::UPDATE($sql_1);

                    //2. INSERT LOG
                    $sql_2 = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,asset_po_id,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->ASSET_PO_ID}','{$dt->NO_REG_ITEM}','".$result['TYPE']."','".$result['ID']."','".$result['NUMBER']."','".$result['MESSAGE']."','".$result['MESSAGE_V1']."','".$result['MESSAGE_V2']."','".$result['MESSAGE_V3']."','".$result['MESSAGE_V4']."') ";
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
            else 
            {
                DB::beginTransaction();

                try 
                {    
                    $sql = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,asset_po_id,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->ASSET_PO_ID}','{$dt->NO_REG_ITEM}','".$result['TYPE']."','".$result['ID']."','".$result['NUMBER']."','".$result['MESSAGE']."','".$result['MESSAGE_V1']."','".$result['MESSAGE_V2']."','".$result['MESSAGE_V3']."','".$result['MESSAGE_V4']."') ";
                    
                    DB::INSERT($sql); 
                    DB::commit();
                    
                    $result = array('status'=>'error','message'=> ''.$result['MESSAGE'].' (No Reg Item: '.$dt->NO_REG_ITEM.')');
                    return $result;                 
                }
                catch (\Exception $e) 
                {
                    DB::rollback();
                    $result = array('status'=>'error','message'=>$e->getMessage());
                    return $result;
                }
            }              
        }
        
    }

    public function get_asset_controller($user_id, $area_code)
    {
        $sql = "SELECT description FROM v_user WHERE id = '{$user_id}' AND area_code = '{$area_code}' ";
        $data = DB::SELECT($sql);
        if(!empty($data)){ $dt = $data[0]->description; }else{ $dt = ''; }
        return $dt;
    }

    public function get_kode_vendor($noreg)
    {
        $sql = "SELECT KODE_VENDOR FROM TR_REG_ASSET WHERE NO_REG = '{$noreg}' ";
        $data = DB::SELECT($sql);
        if($data){ $dt = $data[0]->KODE_VENDOR; }else{ $dt = '0'; }
        return $dt;
    }

    function update_ka_con_temp(Request $request)
    {
        $req = $request->all();
        echo "<pre>"; print_r($req); die();
    }

    public function execute_create_kode_asset_ams($dt) 
    {
        //echo "<pre>"; print_r($dt); die();
        /*
        <pre>stdClass Object
        (
            [ID] => 108
            [ASSET_PO_ID] => 187
            [NO_REG_ITEM] => 1
            [NO_REG] => 19.07/AMS/PDFA/00042
            [ITEM_PO] => 3
            [KODE_MATERIAL] => 000000000210020012
            [NAMA_MATERIAL] => TEE PVC 8" WAVIN
            [NO_PO] => 2013010585
            [BA_PEMILIK_ASSET] => 2121
            [JENIS_ASSET] => E4030
            [GROUP] => G20
            [SUB_GROUP] => SG164
            [ASSET_CLASS] => 
            [NAMA_ASSET] => TEE PVC 8" WAVIN
            [MERK] => 
            [SPESIFIKASI_OR_WARNA] => 
            [NO_RANGKA_OR_NO_SERI] => 21
            [NO_MESIN_OR_IMEI] => 21
            [NO_POLISI] => 
            [LOKASI_BA_CODE] => 2124
            [LOKASI_BA_DESCRIPTION] => 2124-SAWIT BRAHMA
            [TAHUN_ASSET] => 2001
            [KONDISI_ASSET] => B
            [INFORMASI] => 
            [NAMA_PENANGGUNG_JAWAB_ASSET] => 
            [JABATAN_PENANGGUNG_JAWAB_ASSET] => 
            [ASSET_CONTROLLER] => IT
            [KODE_ASSET_CONTROLLER] => 
            [NAMA_ASSET_1] => versa1
            [NAMA_ASSET_2] => versa2
            [NAMA_ASSET_3] => versa3
            [QUANTITY_ASSET_SAP] => 1.00
            [UOM_ASSET_SAP] => UN
            [CAPITALIZED_ON] => 09.07.2019
            [DEACTIVATION_ON] => 
            [COST_CENTER] => 21zd210999
            [BOOK_DEPREC_01] => 4
            [FISCAL_DEPREC_15] => 4
            [GROUP_DEPREC_30] => 4
            [DELETED] => 
            [CREATED_BY] => 22
            [CREATED_AT] => 2019-07-09 18:29:28
            [UPDATED_BY] => 24
            [UPDATED_AT] => 2019-07-09 19:32:01
            [KODE_ASSET_SAP] => 
            [KODE_ASSET_SUBNO_SAP] => 
            [GI_NUMBER] => 
            [GI_YEAR] => 
            [KODE_ASSET_AMS] => 
        )

        */
        
        $ANLA_BUKRS = substr($dt->BA_PEMILIK_ASSET,0,2);
        $user_id = Session::get('user_id');

        DB::beginTransaction();
        try 
        {   
            //3. CREATE KODE ASSET AMS PROCEDURE
            $sql_3 = 'CALL create_kode_asset_ams("'.$noreg.'", "'.$ANLA_BUKRS.'", "'.$dt->JENIS_ASSET.'", "'.$dt->KODE_ASSET_SAP.'")';
            //echo $sql_3; die();
            DB::SELECT($sql_3);

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
}
