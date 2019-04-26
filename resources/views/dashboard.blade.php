{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<!-- <h1>Dashboard</h1> -->
@stop

@section('content')

<!-- /.row -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="table-container">
                    <div class="table-actions-wrapper">
                        <span></span>
                        <button class="btn btn-sm btn-flat btn-danger btn-refresh-data-table" title="refresh"><i class="glyphicon glyphicon-refresh"></i></button>
                    </div>
                    <table id="data-table" class="table table-bordered table-condensed" width="100%">
                        <thead>
                            <tr role="row" class="heading">
                                <th>Tipe</th>
                                <th>No PO</th>
                                <th>Tgl.Pengajuan</th>
                                <th>Requestor</th>
                                <th>Tgl. PO</th>
                                <th>Kode Vendor</th>
                                <th>Nama Vendor</th>
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th><input type="text" class="form-control input-xs form-filter" style="height:10px !important" name="transaction_type" id="transaction_type"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="no_po"></th>
                                <th><input type="text" class="form-control input-xs form-filter datepicker" name="request_date" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="requestor"></th>
                                <th><input type="text" class="form-control input-xs form-filter datepicker" name="po_date" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="vendor_code"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="vendor_name"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="status" disabled></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="detail" disabled></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<div id="detail-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i id="modalHeader"></i></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal code-asset-form" id="code-asset-form">
                    <div class="box-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Tanggal</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control input-sm " name="asset_request_date" id="asset_request_date" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Business Area</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control input-sm" name="asset_business_area" id="asset_business_area" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">No. PO</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control input-sm" name="asset_po_no" id="asset_po_no" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Tgl PO</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control input-sm" name="asset_po_date" id="asset_po_date" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Kode vendor</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control input-sm" name="asset_vendor_code" id="asset_vendor_code" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Nama vendor</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control input-sm" name="asset_vendor_name" id="asset_vendor_name" autocomplete="off" readonly>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label class="col-md-2"><b>ITEM DETAIL</b></label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control input-sm text-right" name="detail_item_selected" id="detail_item_selected" readonly style="border:1px solid red;background-color: #f4433630">
                                    <span class="help-block">Please select the item to show the detail</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Item PO</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control input-sm text-right" name="item_po" value="" id="item_po" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Qty Index</label>
                                <div class="col-md-2">
                                    <input type="text" class="form-control input-sm text-right" name="item_qty_index" id="item_qty_index" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Kode material</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control input-sm" name="item_code" id="item_code" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="plant" class="col-md-2">Nama material</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control input-sm" name="item_name" value="" id="item_name" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs">
                                        <li class="active" style="border-bottom:none !important;"><a href="#panel-initial" data-toggle="tab" class="panel-initial" style="background-color:#f3f3f3;border-bottom:none;font-weight:800">Rincian Informasi Asset | page: <span id="page"></span></a></li>
                                        <li class="pull-right"><a href="javascript:nextPage()" class="text-muted" id="btn_next">Next <i class="fa fa-arrow-right"></i></a></li>
                                        <li class="pull-right"><a href="javascript:prevPage()" class="text-muted" id="btn_prev"><i class="fa fa-arrow-left"></i> Prev</a></li>
                                    </ul>
                                    <div class="tab-content" style="border-left: 1px solid #e0dcdc;border-right: 1px solid #e0dcdc;border-bottom: 1px solid #e0dcdc;border-top:none;background-color:#f3f3f3;">
                                        <!-- Font Awesome Icons -->
                                        <div class="tab-pane active" id="panel-initial">
                                            <div class="box-body">
                                                <div class="form-group hide">
                                                    <label for="plant" class="col-md-2 text-right">Company</label>
                                                    <div class="col-md-3">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group hide">
                                                    <label for="plant" class="col-md-2 text-right">Asset</label>
                                                    <div class="col-md-3">
                                                        <div class="input-group">
                                                            <input type="email" class="form-control input-sm" placeholder="" readonly>
                                                            <span class="input-group-addon btn btn-sm btn-danger"><i class="fa fa-files-o"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-2 text-right">Jenis asset</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control input-sm" name="asset_type" value="" id="asset_type" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-2 text-right">Group</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control input-sm" name="asset_group" value="" id="asset_group" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-2 text-right">Sub Group</label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control input-sm" name="asset_sub_group" value="" id="asset_sub_group" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-3">
                                                        <h4>Asset Class</h4>
                                                    </label>
                                                    <div class="col-md-9">
                                                        <h4>E4010</h4>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-description">

                                                    <label for="part_no" class="col-md-2 col-md-offset-1">Nama Aset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_name" id="asset_name" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-part-no">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1">Merk</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_brand" id="asset_brand" autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">Spesifikasi / Warna</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_specification" id="asset_specification">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">No Seri / No Rangka</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_serie_no" id="asset_serie_no">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">No Mesin / IMEI</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_imei" id="asset_imei">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">No Polisi</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_police_no" id="asset_police_no">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">Lokasi Asset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_location" id="asset_location">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">Tahun Asset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_year" id="asset_year">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">Kondisi Asset</label>
                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="asset_condition" id="optionsRadios1" value="1" checked>
                                                                    Baik
                                                                </label>
                                                            </div>
                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="asset_condition" id="optionsRadios2" value="2">
                                                                    Butuh Perbaikan
                                                                </label>
                                                            </div>
                                                            <div class="radio-inline">
                                                                <label>
                                                                    <input type="radio" name="asset_condition" id="optionsRadios3" value="3">
                                                                    Tidak baik
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">Informasi</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control input-sm attr-material-group" row="3" name="asset_info" id="asset_info"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 col-md-offset-1 ">Foto aset</label>
                                                    <div class="col-md-9">
                                                        <div id="filesContainer">
                                                            <div class="col-md-4" id="panel-image-1">
                                                                <div class="form-group hide">
                                                                    <input type="file" id="foto_asset_1" name="foto_asset_1" accept='image/*' OnChange="showImage('asset', 1)">
                                                                    <p class="help-block">*jpg, png</p>
                                                                </div>
                                                                <div class="image-group">
                                                                    <button type="button" class="btn btn-danger btn-xs btn-flat btn-foto-asset-remove hide" OnClick="removeImage('asset',1)"><i class="fa fa-trash"></i></button>
                                                                    <img id="foto_asset_thumb_1" data-status="0" style="cursor:pointer" title="click to change image" OnClick="openFile('asset',1)" class="img-responsive select-img" src="{{URL::asset('img/add-img.png')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 col-md-offset-1 ">Foto no. seri / no rangka</label>
                                                    <div class="col-md-9">
                                                        <div id="filesContainer">
                                                            <div class="col-md-4" id="panel-image-1">
                                                                <div class="form-group hide">
                                                                    <input type="file" id="foto_no_seri_1" name="foto_no_seri_1" accept='image/*' OnChange="showImage('seri',1)">
                                                                    <p class="help-block">*jpg, png</p>
                                                                </div>
                                                                <div class="image-group">
                                                                    <button type="button" class="btn btn-danger btn-xs btn-flat btn-foto-seri-remove hide" OnClick="removeImage('seri',1)"><i class="fa fa-trash"></i></button>
                                                                    <img id="foto_no_seri_thumb_1" data-status="0" style="cursor:pointer" title="click to change image" OnClick="openFile('seri',1)" class="img-responsive select-img" src="{{URL::asset('img/add-img.png')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-2 col-md-offset-1 ">Foto No msin / IMEI</label>
                                                    <div class="col-md-9">
                                                        <div id="filesContainer">
                                                            <div class="col-md-4" id="panel-image-1">
                                                                <div class="form-group hide">
                                                                    <input type="file" id="foto_mesin_1" name="foto_mesin_1" accept='image/*' OnChange="showImage('mesin', 1)">
                                                                    <p class="help-block">*jpg, png</p>
                                                                </div>
                                                                <div class="image-group">
                                                                    <button type="button" class="btn btn-danger btn-xs btn-flat btn-foto-mesin-remove hide" OnClick="removeImage('mesin', 1)"><i class="fa fa-trash"></i></button>
                                                                    <img id="foto_mesin_thumb_1" data-status="0" style="cursor:pointer" title="click to change image" OnClick="openFile('mesin', 1)" class="img-responsive select-img" src="{{URL::asset('img/add-img.png')}}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h4>Penanggung jawab Aset:</h4>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">Nama</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_pic_name" id="asset_pic_name">
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-2 col-md-offset-1 col-form-label">Jabatan</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="asset_pic_level" id="asset_pic_level">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer clearfix">
                            <button type="button" class="btn btn-default btn-flat btn-back-request-form pull-right" data-dismiss="modal" style="margin-right: 5px;">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop


@section('js')
<script>
    var attribute = [];
    jQuery(document).ready(function() {

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var grid = new Datatable();
        grid.init({
            src: jQuery("#data-table"),
            onSuccess: function(grid) {},
            onError: function(grid) {},
            onDataLoad: function(grid) {},
            loadingMessage: 'Loading...',
            dataTable: {
                "dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",
                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100, 150],
                    [10, 20, 50, 100, 150]
                ],
                "pageLength": 10,
                "ajax": {
                    url: "{!! route('get.outstanding') !!}"
                },
                columns: [{
                        "render": function(data, type, row) {
                            if (row.type == 1) {
                                var content = 'Barang'
                            } else if (row.type == 2) {
                                var content = 'Jasa'
                            } else if (row.type == 3) {
                                var content = 'lain-lain'
                            }

                            return content;
                        }
                    },
                    {
                        data: 'no_po',
                        name: 'no_po'
                    },
                    {
                        data: 'request_date',
                        name: 'request_date'
                    },
                    {
                        data: 'requestor',
                        name: 'requestor'
                    },
                    {
                        data: 'po_date',
                        name: 'po_date'
                    },
                    {
                        data: 'vendor_code',
                        name: 'vendor_code'
                    },
                    {
                        data: 'vendor_name',
                        name: 'vendor_name'
                    },
                    {
                        "render": function(data, type, row) {
                            return 'Waiting approval';
                        }
                    },
                    {
                        "render": function(data, type, row) {
                            var content = '<button class="btn btn-flat btn-flat btn-xs btn-danger" OnClick="requestDetail(' + row.id + ')" title="detail data ' + row.no_po + '" ><i class="fa fa-search"></i></button>';
                            return content;
                        }
                    }
                ],
                columnDefs: [{
                        targets: [1, 5],
                        width: '12%'
                    },
                    {
                        targets: [7],
                        width: '15%'
                    },
                    {
                        targets: [8],
                        width: '5%',
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        targets: [2, 4, 0, 3],
                        width: '10%'
                    },

                ],
                oLanguage: {
                    sProcessing: "<div id='datatable-loader'></div>",
                    sEmptyTable: "Data tidak di temukan",
                    sLoadingRecords: ""
                },
                "order": [],
            }
        });

        jQuery(".datepicker").datepicker({
            format: "mm/dd/yyyy",
            autoclose: true
        });

        jQuery("input[name='transaction_type']").select2({
            data: [{
                    id: '1',
                    text: 'Barang'
                },
                {
                    id: '2',
                    text: 'Jasa'
                },
                {
                    id: '3',
                    text: 'Lain-lain'
                },
            ],
            width: "100%",
            allowClear: true,
            placeholder: ' '
        });

        jQuery("#business_area").select2({
            data: [{
                    id: '1',
                    text: 'LoV'
                },
                {
                    id: '2111',
                    text: '2111 - HO JAKARTA SAWIT'
                },
                {
                    id: '2112',
                    text: '2112 - RO JAMBI'
                },
                {
                    id: '2113',
                    text: '2113 - HO JAKARTA KARET'
                },
            ],
            width: "100%",
            allowClear: true,
            placeholder: ' '
        });

    });

    function requestDetail(id) {

        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('menu/edit/?id=') }}" + id)));

        jQuery("#detail-modal").modal({
            backdrop: 'static',
            keyboard: false
        });
        jQuery("#detrail-modal").modal('show');
    }
</script>
@stop