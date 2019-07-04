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
        /*
        if (empty(Session::get('authenticated')))
            return redirect('/login');

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
        $sql = " SELECT a.*, b.jenis_asset_description AS JENIS_ASSET_NAME, c.group_description AS GROUP_NAME, d.subgroup_description AS SUB_GROUP_NAME, e.KODE_VENDOR, e.NAMA_VENDOR, e.BUSINESS_AREA AS BUSINESS_AREA
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
                    'kode_asset_ams' => trim($v->KODE_ASSET_AMS)
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

    function update_status(Request $request, $status, $noreg)
    {
        $rolename = Session::get('role');
        
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
            
            $no_registrasi = str_replace("-", "/", $noreg);
            $user_id = Session::get('user_id');
            $note = $request->parNote;
            $role_id = Session::get('role_id'); //get role id user
            $asset_controller = ''; //get asset controller 
            //echo $note;die();

            DB::beginTransaction();

            try 
            {
                DB::SELECT('CALL update_approval("'.$no_registrasi.'", "'.$user_id.'","'.$status.'", "'.$note.'", "'.$role_id.'", "'.$asset_controller.'")');

                 DB::commit();
                return response()->json(['status' => true, "message" => 'Data is successfully ' . ($no_registrasi ? 'updated' : 'update')]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['status' => false, "message" => $e->getMessage()]);
            }    
        }   
    }

    function get_validasi_io(Request $request)
    {
        $req = $request->all();
        $noreg = $req['no-reg'];
        //echo "<pre>"; print_r($req); die();
        /*
            <pre>Array
            (
                [no-reg] => 19.07/AMS/PDFA/00017
                [type-transaksi] => Barang
                [po-type] => SAP
                [business-area] => 2121 - ESTATE BBB
                [requestor] => PGA (Payroll & General Affair) - BBB
                [tanggal-reg] => 04-07-2019
                
                [total_tab] => 2
                [nama_asset_1-1] => 11
                [nama_asset_2-1] => 22
                [nama_asset_3-1] => 33
                [acct_determination-1] => 4030-KENDARAAN & ALAT BERAT
                [serial_number-1] => 1
                [inventory_number-1] => 2
                [quantity-1] => 44.00
                [uom-1] => 55
                [capitalized_on-1] => 2019-12-11
                [deactivation_on-1] => 2019-12-22
                [business_area-1] => 2121
                [cost_center-1] => 66
                [plant-1] => 2121
                [vendor-1] => 2300001364-PT DAYA ANUGRAH MANDIRI
                [book_deprec_01-1] => 77
                [fiscal_deprec_15-1] => 88
                [group_deprec_30-1] => 77
                [kode_aset_controller-1] => aaaadd
                [kode_aset_sap-1] => 172
                
                [nama_asset_1-2] => 1
                [nama_asset_2-2] => 2
                [nama_asset_3-2] => 3
                [acct_determination-2] => 4030-KENDARAAN & ALAT BERAT
                [serial_number-2] => 3
                [inventory_number-2] => 4
                [quantity-2] => 5.00
                [uom-2] => 6
                [capitalized_on-2] => 2019-01-02
                [deactivation_on-2] => 2019-01-05
                [business_area-2] => 2121
                [cost_center-2] => 7
                [plant-2] => 2121
                [vendor-2] => 2300001364-PT DAYA ANUGRAH MANDIRI
                [book_deprec_01-2] => 8
                [fiscal_deprec_15-2] => 9
                [group_deprec_30-2] => 8
                [kode_aset_controller-2] => aaaa
                [kode_aset_sap-2] => 171
                [specification] => 
                [parNote] => 
            )
        */

        // KAC = Kode Aset Controller
        $total_kac = @$req['total_tab'];
        //echo $total_kac; die();

        if(!empty($total_kac))
        {
            $i = 1;
            for($i; $i<=$total_kac; $i++)
            {
                if( !empty($req['kode_aset_sap-1']) )
                {
                    $proses = $this->validasi_io_proses($noreg, $req['kode_aset_sap-'.$i.''],$req['kode_aset_controller-'.$i.'']);
                
                    if($proses['status']=='error')
                    {
                        $result = array('status'=>false,'message'=> $proses['message']);
                        return $result;
                    }
                    else
                    {
                        $result = array('status'=>true,'message'=> $proses['message']);
                        return $result;
                    }
                }
                else
                {
                    $result = array('status'=>false,'message'=> 'Kode Aset Controller belum diisi (di ITEM DETAIL)');
                    return $result;
                }
                
            }
        }else
        {
            $result = array('status'=>false,'message'=> 'Kode Aset Controller belum diisi (di ITEM DETAIL)');
            return $result;
        }
    }

    function validasi_io_proses($noreg, $ka_sap, $ka_con)
    {
        //echo $ka_sap.'===='.$ka_con;
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "check_io?AUFNR=$ka_con&AUFUSER3=$ka_sap", 
        ));
        
        $data = $service;

        //echo "<pre>"; print_r($data); die();

        if( $data->TYPE == 'S' )
        {
            DB::UPDATE(" UPDATE TR_REG_ASSET_DETAIL SET KODE_ASSET_CONTROLLER = '{$ka_con}' WHERE NO_REG = '{$noreg}' AND KODE_ASSET_SAP = '{$ka_sap}' ");

            $result = array('status'=>'success','message'=> $data->MESSAGE);
        }
        else
        {
            
            $result = array('status'=>'error','message'=> $data->MESSAGE.' (Kode Aset Controller:'.$ka_con.')');
        }
        return $result;
    }

    function log_history($id)
    {
        $noreg = str_replace("-", "/", $id);

        $records = array();

        /*$sql = "SELECT a.*, date_format(a.date,'%d-%m-%Y %h:%i:%s') AS date2 FROM v_history a WHERE a.document_code = '{$noreg}' ORDER BY a.date";*/
        $sql = "SELECT document_code,user_id,name,area_code,status_approval,notes,date FROM v_history_approval WHERE document_code = '{$noreg}' ORDER BY -date ASC, date ASC ";

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
                    'kode_material' => trim($v->KODE_MATERIAL),
                );
            }
        }

        return $datax;
    }

    function synchronize_sap(Request $request)
    {
        //echo "<pre>"; print_r($request->noreg); die();
        $no_reg = @$request->noreg;

        $sql = " SELECT a.*, date_format(a.CAPITALIZED_ON,'%d.%m.%Y') AS CAPITALIZED_ON, date_format(a.DEACTIVATION_ON,'%d.%m.%Y') AS DEACTIVATION_ON FROM TR_REG_ASSET_DETAIL a WHERE a.NO_REG = '{$no_reg}' AND (a.KODE_ASSET_SAP = '' OR a.KODE_ASSET_SAP is null) ";

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
        ));
        
        $data = $service;

        if(!empty($data))
        {
            $result = array();
            $message = '';

            foreach($data->data as $k => $v)
            {

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
                
            if( $result['TYPE'] == 'S' )
            {
                $user_id = Session::get('user_id');
                $asset_controller = $this->get_asset_controller($user_id,$dt->LOKASI_BA_CODE);

                DB::beginTransaction();
                try 
                {   
                    //1. ADD KODE_ASSET_SAP & ASSET_CONTROLLER TR_REG_ASSET 
                    $sql_1 = " UPDATE TR_REG_ASSET_DETAIL SET ASSET_CONTROLLER = '{$asset_controller}', KODE_ASSET_SAP = '".$result['MESSAGE_V1']."', UPDATED_BY = '{$user_id}', UPDATED_AT = current_timestamp() WHERE NO_REG = '{$dt->NO_REG}' AND NO_REG_ITEM = '{$dt->NO_REG_ITEM}' ";
                    DB::UPDATE($sql_1);

                    //2. INSERT LOG
                    $sql_2 = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->NO_REG_ITEM}','".$result['TYPE']."','".$result['ID']."','".$result['NUMBER']."','".$result['MESSAGE']."','".$result['MESSAGE_V1']."','".$result['MESSAGE_V2']."','".$result['MESSAGE_V3']."','".$result['MESSAGE_V4']."') ";
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
                    $sql = " INSERT INTO TR_LOG_SYNC_SAP(no_reg,no_reg_item,msgtyp,msgid,msgnr,message,msgv1,msgv2,msgv3,msgv4)VALUES('{$dt->NO_REG}','{$dt->NO_REG_ITEM}','".$result['TYPE']."','".$result['ID']."','".$result['NUMBER']."','".$result['MESSAGE']."','".$result['MESSAGE_V1']."','".$result['MESSAGE_V2']."','".$result['MESSAGE_V3']."','".$result['MESSAGE_V4']."') ";
                    
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
        else
        {
            return false;
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
}
