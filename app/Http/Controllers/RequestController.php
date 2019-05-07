<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\TrUser;
use function GuzzleHttp\json_encode;
use Session;
use API;
use AccessRight;
use Spipu\Html2Pdf\Html2Pdf;
use App\TR_REG_ASSET;
use App\TR_REG_ASSET_FILE;
use App\TR_REG_ASSET_DETAIL;
use App\TR_REG_ASSET_DETAIL_FILE;
use App\TR_REG_ASSET_DETAIL_DETAIL;
use App\TR_REG_ASSET_DETAIL_PO;

class RequestController extends Controller
{
    public function index()
    {
        if (empty(Session::get('authenticated')))
            return redirect('/login');


        if (AccessRight::granted() === false) {
            $data['page_title'] = 'Oops! Unauthorized.';
            return response(view('errors.403')->with(compact('data')), 403);
        }

        $access = AccessRight::access();
        $data["page_title"] = "Request";
        $data["access"] = (object)$access;
        return view('request.index')->with(compact('data'));
    }

    public function create(Request $request) {
        if (empty(Session::get('authenticated')))
            return redirect('/login');


        $data['page_title'] = 'Request '.($request->type == "amp" ? 'Melalui PO AMP':'Melalui PO Sendiri');
        $data['type'] = ($request->type == "amp" ? 'Melalui PO AMP':'Melalui PO Sendiri');
        $access = AccessRight::access();
        $data["access"] = (object)$access;
        
        if($request->type == "amp") {
            return view('request.amp')->with(compact('data'));
        }else {
            return view('request.sap')->with(compact('data'));
        }
        
    }
    
    public function getPO(Request $request) {
        $param = $_REQUEST;
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "select_po/" . $param["no_po"]
        ));
        $data = $service;
       if(isset( $data->EBELN)) {
            return response()->json(array('data' => $data));
       } else {
            return response()->json(array('data' => array())); 
       }
    }

    public function dataGrid() {
        $data = array(
            array(
                "id" => '1',
                "request_no" => '572120171023001',
                "request_date" => '2019-01-02',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '2',
                "request_no" => '572120171023002',
                "request_date" => '2019-01-02',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '3',
                "request_no" => '572120171023003',
                "request_date" => '2019-02-04',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '3',
                "request_no" => '572120171023004',
                "request_date" => '2019-01-03',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
            array(
                "id" => '4',
                "request_no" => '572120171023005',
                "request_date" => '2019-01-03',
                "controller_asset_code" => '',
                "fams_asset_code" => '',
                "verification" => '0',
                "satatus" => '0'
            ),
        );

        $iTotalRecords = count($data);
        //$iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = intval(10);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        //$iDisplayStart = intval($_REQUEST['start']);
        $iDisplayStart = intval(0);
        //$sEcho = intval($_REQUEST['draw']);
        $sEcho = intval(2);
        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        for($i = $iDisplayStart; $i < $end; $i++) {
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

    public function store(Request $request)
    {
        DB::beginTransaction();

       try {
           
            $reg_no = rand(0, 1000000);
            $asset_id = DB::table('TR_REG_ASSET')->insertGetId([
                "CREATED_BY" => Session::get('user_id'),
                "NO_REG" => $reg_no,
                "BUSINESS_AREA" => $request->business_area,
                "TYPE_TRANSAKSI" => $request->transaction_type,
                "PO_TYPE" => $request->po_type,
                "TANGGAL_REG" => date_format(date_create($request->request_date), "Y-m-d"),
                "NO_PO" => $request->po_no,
                "TANGGAL_PO" => date_format(date_create($request->po_date), "Y-m-d"),
                "KODE_VENDOR" => $request->vendor_code,
                "NAMA_VENDOR" => $request->vendor_name,
            ]);

            $no = 1;
            if( $request->docs) {
                foreach ($request->docs as $row) {
                    DB::table('TR_REG_ASSET_FILE')->insert([
                        "ASSET_REG_ID" => $asset_id,
                        "NO_FILE" => $no,
                        "NO_REG" => $reg_no,
                        "FILENAME" => $row['name'],
                        "DOC_SIZE" => $row["size"],
                        "FILE_CATEGORY" => $row['type'],
                        "FILE_UPLOAD" => $row['file'],
                        "CREATED_BY" => Session::get('user_id'),
                    ]);
                    $no++;
                }
            }

            $no = 0;
            if( $request->asset) {
                foreach ($request->asset as $row) {
                    if ($row["item_po"]) {
                        $reg_asset_po_id = DB::table( 'TR_REG_ASSET_DETAIL_PO')-> insertGetId([
                            "ASSET_REG_ID" =>  $asset_id,
                            "NO_REG" =>  $reg_no,
                            "NO_PO" =>  $request->po_no,
                            "ITEM_PO" =>  $row["item_po"],
                            "KODE_MATERIAL" =>  $row["code"],
                            "NAMA_MATERIAL" =>  $row["name"],
                            "QUANTITY_PO" =>  $row["qty"],
                            "QUANTITY_SUBMIT" =>  $row["request_qty"],
                            "CREATED_BY" =>  Session::get('user_id'),
                        ]);
                        $detail = $row["detail"];

                        for ($i = 0; $i < count($detail); $i++) {
                            $reg_asset_detail_id = DB::table( 'TR_REG_ASSET_DETAIL')->insertGetId([
                                "ASSET_PO_ID" =>   $reg_asset_po_id,
                                "NO_REG_ITEM" =>  $i + 1,
                                "NO_REG" =>  $reg_no,
                                "ITEM_PO" =>  $row["item_po"],
                                "KODE_MATERIAL" =>  $row["code"],
                                "NAMA_MATERIAL" =>  $row["name"],
                                "NO_PO" =>  $request->po_no,
                                "KODE_JENIS_ASSET" =>  '',
                                "JENIS_ASSET" =>  $detail[$i]["asset_type"],
                                "GROUP" =>  $detail[$i]["asset_group"],
                                "SUB_GROUP" =>  $detail[$i]["asset_sub_group"],
                                "ASSET_CLASS" =>  '',
                                "NAMA_ASSET" =>  $detail[$i]["asset_name"],
                                "MERK" =>  $detail[$i]["asset_brand"],
                                "SPESIFIKASI_OR_WARNA" =>  $detail[$i]["asset_specification"],
                                "NO_RANGKA_OR_NO_SERI" =>  $detail[$i]["asset_serie_no"],
                                "NO_MESIN_OR_IMEI" =>  $detail[$i]["asset_imei"],
                                "NO_POLISI" =>  $detail[$i]["asset_police_no"],
                                "LOKASI_BA_CODE" =>  $detail[$i]["asset_location"],
                                "LOKASI_BA_DESCRIPTION" => $detail[$i]["asset_location_desc"],
                                "KONDISI_ASSET" =>  $detail[$i]["asset_condition"],
                                "TAHUN_ASSET" =>  $detail[$i]["asset_year"],
                                "INFORMASI" =>  $detail[$i]["asset_info"],
                                "NAMA_PENANGGUNG_JAWAB_ASSET" =>  $detail[$i]["asset_pic_name"],
                                "JABATAN_PENANGGUNG_JAWAB_ASSET" =>  $detail[$i]["asset_pic_level"],
                                "CREATED_BY" =>  Session::get('user_id'),
                            ]); 
                            $item_file_id = ($no + 1) . ($i + 1);
           
                            if ($detail[$i]["foto_asset"]["name"]) {
                                DB::table( 'TR_REG_ASSET_DETAIL_FILE')->insert([
                                    "ASSET_PO_DETAIL_ID" =>  $reg_asset_detail_id,
                                    "NO_REG_ITEM_FILE" => $item_file_id,
                                    "NO_REG" => $reg_no,
                                    "JENIS_FOTO" => 'foto asset',
                                    "FILENAME" => $detail[$i]["foto_asset"]["name"],
                                    "DOC_SIZE" => $detail[$i]["foto_asset"]["size"],
                                    "FILE_CATEGORY" => 'asset',
                                    "FILE_UPLOAD" => $detail[$i]["foto_asset"]["file"],

                                ]);
                            }

                            if ($detail[$i]["foto_asset_seri"]["name"]) {
                                DB::table('TR_REG_ASSET_DETAIL_FILE')->insert([
                                    "ASSET_PO_DETAIL_ID" =>  $reg_asset_detail_id,
                                    "NO_REG_ITEM_FILE" => $item_file_id,
                                    "NO_REG" => $reg_no,
                                    "JENIS_FOTO" => 'Foto no. seri / no rangka',
                                    "FILENAME" => $detail[$i]["foto_asset_seri"]["name"],
                                    "DOC_SIZE" => $detail[$i]["foto_asset_seri"]["size"],
                                    "FILE_CATEGORY" => 'no seri',
                                    "FILE_UPLOAD" => $detail[$i]["foto_asset_seri"]["file"],
                                ]);
                            }

                            if ($detail[$i]["foto_asset_mesin"]["name"]) {
                                DB::table('TR_REG_ASSET_DETAIL_FILE')->insert([
                                    "ASSET_PO_DETAIL_ID" =>  $reg_asset_detail_id,
                                    "NO_REG_ITEM_FILE" => $item_file_id,
                                    "NO_REG" => $reg_no,
                                    "JENIS_FOTO" => 'Foto No msin / IMEI',
                                    "FILENAME" => $detail[$i]["foto_asset_mesin"]["name"],
                                    "DOC_SIZE" => $detail[$i]["foto_asset_mesin"]["size"],
                                    "FILE_CATEGORY" => 'imei',
                                    "FILE_UPLOAD" => $detail[$i]["foto_asset_mesin"]["file"],
                                ]);
                            }
                        }
                        $no++;
                    }
                }
            }
            DB::commit();
            return response()->json(['status' => true, "message" => 'Data is successfully ' . ($request->edit_id ? 'updated' : 'added')]);
       } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, "message" => $e->getMessage()]);
       }
    }

    public function pdfDoc()
    {

        $html2pdf = new Html2Pdf('P', 'A4', 'en');
        $html2pdf->writeHTML(view('request.pdf', [
            'name' => 'dadang kurniawan',
        ]));

        $pdf = $html2pdf->output("", "S");
        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Length', strlen($pdf))
            ->header('Content-Disposition', 'inline; filename="request.pdf"');
    }
}
