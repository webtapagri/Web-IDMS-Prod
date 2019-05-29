@extends('adminlte::page')
@section('title', 'FAMS - approval')

@section('content')
<div class="row" style="margin-top:-3%">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Outstanding
            <small>Approval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Outstanding</li>
        </ol>
    </section>

    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="table-container">
                    <div class="table-actions-wrapper">
                        <button class="btn btn-flat btn-sm btn-flat label-danger btn-refresh"><i class="glyphicon glyphicon-refresh" title="Refresh"></i></button>
                    </div>
                    <table id="data-table" class="table table-bordered table-condensed">
                        <thead>
                            <tr role="row" class="heading">
                                <th>No Reg</th>
                                <th>Tipe</th>
                                <th>PO</th>
                                <th>No PO</th>
                                <th>Tgl.Pengajuan</th>
                                <th>Requestor</th>
                                <th>Tgl. PO</th>
                                <th>Kode Vendor</th>
                                <th>Nama Vendor</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th><input type="text" class="form-control input-xs form-filter" name="no_reg"></th>
                                <th>
                                    <select type="text" class="form-control input-xs form-filter" name="transaction_type" id="flt_transaction_type">
                                        <option></option>
                                    </select>
                                </th>
                                <th>
                                    <select class="form-control input-xs form-filter" name="po_type" id="po_type">
                                        <option></option>
                                    </select>
                                </th>
                                <th><input type="text" class="form-control input-xs form-filter" name="no_po"></th>
                                <th><input type="text" class="form-control input-xs form-filter datepicker" name="request_date" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="requestor"></th>
                                <th><input type="text" class="form-control input-xs form-filter datepicker" name="po_date" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="vendor_code"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="vendor_name"></th>
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
<div id="approve-modal" class="modal fade" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <form id="request-form" class="form-horizontal" style="font-size:13px !important">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="plant" class="col-md-4">NO REGISTRASI</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control xinput-sm" value="" id="no-reg" name="no-reg" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="plant" class="col-md-4">TYPE TRANSAKSI</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-sm" value="" id="type-transaksi" name="type-transaksi" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="plant" class="col-md-4">JENIS PENGAJUAN</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-sm" value="" id="po-type" name="po-type" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="plant" class="col-md-4">BUSINESS AREA</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-sm" value="" id="business-area" name="business-area" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="plant" class="col-md-4">REQUESTOR</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-sm" value="" id="requestor" name="requestor" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="plant" class="col-md-4">TANGGAL PENGAJUAN</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-sm" value="" id="tanggal-reg" name="tanggal-reg" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        
                        <br>
                        <div class="form-group">
                            <div class="col-md-12">
                                <table class="table table-condensed" id="request-item-table">
                                    <tr>
                                        <th>No</th>
                                        <th>Item</th>
                                        <th>Qty index</th>
                                        <th>Kode material</th>
                                        <th>nama material</th>
                                        <th>MRP</th>
                                        <th>BA Pemilik Asset</th>
                                        <th>BA Lokasi Asset</th>
                                        <th>REquestor</th>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="text-align:center">No item selected</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <h4>Rincian Informasi Asset</h4>
                        <div class="form-group">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#panel-initial" data-toggle="tab" class="panel-initial">Page 1</a></li>
                                    <li><a href="#panel-basic-data" class="panel-basic-data" data-toggle="tab">Page 2</a></li>
                                </ul>
                                <div class="tab-content" style="border: 1px solid #e0dcdc;border-top:none; font-size:12px !important">
                                    <!-- Font Awesome Icons -->
                                    <div class="tab-pane active" id="panel-initial">
                                        <div class="box-body">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">No PO</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Tanggal PO</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Kode vendor</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" id="description" value="XXXXX" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Nama vendor</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="DAYA ANUGERAH MANDIRI" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Item PO</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm text-right" name="description" value="1" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Qty Index</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm text-right" name="description" value="1" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Kode material</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="XXXX-1" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Nama material</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="SEPEDA MOTOR 150 HONDA VERZA" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">Kondisi </label>
                                                    <div class="col-md-6 col-md-offset-1">
                                                        <div class="form-group">
                                                            <div class="">
                                                                <label>
                                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
                                                                    Baik
                                                                </label>
                                                            </div>
                                                            <div class="">
                                                                <label>
                                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                                                    Butuh Perbaikan
                                                                </label>
                                                            </div>
                                                            <div class="">
                                                                <label>
                                                                    <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3">
                                                                    Tidak baik
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h4>Penanggung jawab Aset:</h4>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-3 col-md-offset-1">Nama</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-3 col-md-offset-1">Jabatan</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Foto Fisik</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="SEPEDA MOTOR 150 HONDA VERZA" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Foto no seri</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="SEPEDA MOTOR 150 HONDA VERZA" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Foto no mesin</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="SEPEDA MOTOR 150 HONDA VERZA" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Jenis Asset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Group</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Sub Group</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Asset Class</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Kode asset Fams</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Kode asset SAP</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">Nama aset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="plant" class="col-md-4">merk</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">Spesifikasi / Warna</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">No Seri / No Rangka</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">No Mesin / IMEI</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">No Polisi</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">Tahun Asset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">BA Lokasi Asset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">BA Pemilik Asset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group material-group-input" id="input-specification">
                                                    <label for="part_no" class="col-md-4">Informasi</label>
                                                    <div class="col-md-8">
                                                        <textarea type="text" class="form-control input-sm attr-material-group" row="3" name="specification" id="specification" disabled></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-4">Kode asset</label>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control input-sm" name="description" value="" id="description" autocomplete="off">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /#fa-icons -->

                                    <!-- glyphicons-->
                                    <div class="tab-pane" id="panel-basic-data">
                                        page 2
                                    </div>
                                    <!-- /#ion-icons -->
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">Note</label>
                            <div class="col-md-8">
                                <textarea type="text" class="form-control input-sm attr-material-group" row="3" name="specification" id="specification"></textarea>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-flat label-danger" OnClick="saveRequest()" style="margin-right: 5px;">Submit</button>
                <button type="button" class="btn btn-flat label-danger" OnClick="saveRequest()" style="margin-right: 5px;">Reject</button>
                <button type="button" class="btn btn-flat label-danger" OnClick="saveRequest()" style="margin-right: 5px;">Revise</button>
                <button type="button" class="btn btn-flat label-danger" OnClick="saveRequest()" style="margin-right: 5px;">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    var request_item = [];
    var bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    $(document).ready(function() 
    {
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
                ajax: "{!! route('get.approval_grid') !!}",
                columns: [
                    {
                        "render": function(data, type, row) 
                        {
                            var no_registrasi= row.no_reg.replace(/\//g, '-');
                            return '<a href="javascript:;" style="font-weight:bold" OnClick="approval(\'' + no_registrasi + '\')">' + row.no_reg + '</a>';
                        }
                    }, {
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
                    }, {
                        "render": function(data, type, row) {
                            if (row.po_type == 0) {
                                var content = '<span class="label label-primary">SAP</span>';
                            } else if (row.po_type == 1) {
                                var content = '<span class="label label-danger">AMP</span>';
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
                    }
                ],
                columnDefs: []
            }
        });
    });

    function approval(id)
    {
        //alert(id);
        var kata = id;
        var noreg= kata.replace(/\//g, '-');
        //alert(noreg); return false;

        $.ajax({
            type: 'GET',
            url: "{{ url('approval/view') }}/"+noreg,
            data: "",
            //async: false,
            dataType: 'json',
            success: function(data) 
            { 
                //alert(data.no_reg);
                $("#request-form #no-reg").val(data.no_reg);
                $("#request-form #type-transaksi").val(data.type_transaksi);
                $("#request-form #po-type").val(data.po_type);
                $("#request-form #business-area").val(data.business_area);
                $("#request-form #requestor").val(data.requestor);
                $("#request-form #tanggal-reg").val(data.tanggal_reg);
                /*                              
                //alert(data.foto);
                $("#form-detil #gender").val(data.gender);
                $("#form-detil #message_view").val(data.message);
                $("#form-detil #ipaddress").val(data.ipaddress);
                $("#form-detil #browser").val(data.browser);
                $("#form-detil #created").val(data.created);
                if( data.foto != null )
                {
                    var folder = '';
                    if(data.category=="TESTIMONI"){folder="testimonial";}else{folder="contact";}
                    $(".image").html("<a href='<?php //echo site_url("userfiles"); ?>/"+folder+"/"+data.foto+"' target='_blank'><img src='<?php //echo base_url("userfiles"); ?>/"+folder+"/"+data.foto+"' alt='' width=80></a>");
                }
                */

                $("#approve-modal .modal-title").html("<i class='fa fa-edit'></i>  Approval Pendaftaran - <span style='color:#dd4b39'>" + data.no_reg + "</span>");

                $('#approve-modal').modal('show');
            },
            error: function(x) 
            {                           
                alert("Error: "+ "\r\n\r\n" + x.responseText);
            }
        }); 
    }

    function ___approval(id) 
    {
        request_item = [];
        jQuery("#edit_id").val(id);

        request_item.push({
            item_po: 1,
            code: "4040100001",
            name: "SEPEDA MOTOR 150 HONDA VERZA",
            qty_index: 1,
            business_area: '4141 - Gawi Mill',
            mrp: '4141 - Gawi Mill',
            business_area_location: '4121',
            requestor: 'dadang.kurniawan',
        });
        request_item.push({
            item_po: 1,
            code: "4040100001",
            name: "SEPEDA MOTOR 150 HONDA VERZA",
            qty_index: 1,
            business_area: '4141 - Gawi Mill',
            mrp: '4141 - Gawi Mill',
            business_area_location: '4121',
            requestor: 'dadang.kurniawan',
        });
        request_item.push({
            item_po: 1,
            code: "4040100001",
            name: "SEPEDA MOTOR 150 HONDA VERZA",
            qty_index: 1,
            business_area: '4141 - Gawi Mill',
            mrp: '4141 - Gawi Mill',
            business_area_location: '4121',
            requestor: 'dadang.kurniawan',
        });
        request_item.push({
            item_po: 1,
            code: "4040100001",
            name: "SEPEDA MOTOR 150 HONDA VERZA",
            qty_index: 1,
            business_area: '4141 - Gawi Mill',
            mrp: '4141 - Gawi Mill',
            business_area_location: '4121',
            requestor: 'dadang.kurniawan',
        });

        createItemRequestTable();

        jQuery("#approve-modal .modal-title").html("<i class='fa fa-edit'></i>  Approval Request - Penambahan <span style='color:#dd4b39'>" + id + "</span>");
        jQuery("#approve-modal").modal("show");
    }

    function createItemRequestTable() {
        var item = '<table class="table table-condensed" id="request-item-table" style="font-size:13px">';
        item += '<th>No</th>';
        item += '<th>Item</th>';
        item += '<th>Qty index</th>';
        item += '<th>Kode material</th>';
        item += '<th>nama material</th>';
        item += '<th>MRP</th>';
        item += '<th>BA Pemilik Asset</th>';
        item += '<th>BA Lokasi Asset</th>';
        item += '<th>Requestor</th>';

        if (request_item.length > 0) {
            var no = 1;
            jQuery.each(request_item, function(key, val) {
                item += "<tr style='height: 30px !important;font-size:11px !important;'>";
                item += "<td>" + no + "</td>";
                item += "<td>" + val.item_po + "</td>";
                item += "<td>" + val.qty_index + "</td>";
                item += "<td>" + val.code + "-" + key + "</td>";
                item += "<td>" + val.name + " " + key + "</td>";
                item += "<td>" + val.mrp + "</td>";
                item += "<td>" + val.business_area + "</td>";
                item += "<td>" + val.business_area_location + "</td>";
                item += "<td>" + val.requestor + "</td>";
                item += "</tr>";
                no++;
            });
        } else {
            item += '<tr>';
            item += ' <td colspan="7" style="text-align:center">No item selected</td>';
            item += '</tr>';
        }
        item += "</table>";
        jQuery("#request-item-table").html(item);
    }
</script>
@stop