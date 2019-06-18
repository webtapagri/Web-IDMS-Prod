<?php 
    //echo Session::get('role_id').'<br/>';
    //echo "<pre>"; print_r(session()->all()); 
    //die();
?>

@extends('adminlte::page')
@section('title', 'FAMS - approval')

@section('content')

<div class="row" style="margin-top:-3%">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            TASK
            <small>Approval</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Task Approval</li>
        </ol>
    </section><br/>

<div class="col-md-12">
  <!-- Custom Tabs -->
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">OUTSTANDING</a></li>
      <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">HISTORY</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab_1">
            <div class="small table-container">
                    <div class="table-actions-wrapper">
                        <button class="btn btn-flat btn-sm btn-flat label-danger btn-refresh"><i class="glyphicon glyphicon-refresh" title="Refresh"></i></button>
                    </div>
                    <table id="data-table" class="table table-bordered table-condensed">
                        <thead>
                            <tr role="row" class="heading">
                                <th>NO REG</th>
                                <th>TIPE</th>
                                <th>PO</th>
                                <th>NO PO</th>
                                <th>TGL PENGAJUAN</th>
                                <th>REQUESTOR</th>
                                <th>TGL PO</th>
                                <th>KODE VENDOR</th>
                                <th>NAMA VENDOR</th>
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
      <!-- /.tab-pane -->
      <div class="tab-pane" id="tab_2">
        <div class="small table-container">
                    <div class="table-actions-wrapper">
                        <button class="btn btn-flat btn-sm btn-flat label-danger btn-refresh"><i class="glyphicon glyphicon-refresh" title="Refresh"></i></button>
                    </div>
                    <table id="data-table-history" class="table table-bordered table-condensed">
                        <thead>
                            <tr role="row" class="heading">
                                <th>DOC. CODE</th>
                                <th>AREA CODE</th>
                                <th>DOC. NAME</th>
                                <th>STATUS</th>
                                <th>STATUS APPROVAL</th>
                                <th>NOTES</th>
                                <th>DATE</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th><input type="text" class="form-control input-xs form-filter" name="document_code"></th>
                                <th>
                                    <select type="text" class="form-control input-xs form-filter" name="area_code" id="area_code">
                                        <option></option>
                                    </select>
                                </th>
                                <!--th>
                                    <select class="form-control input-xs form-filter" name="user_id" id="user_id">
                                        <option></option>
                                    </select>
                                </th-->
                                <th><input type="text" class="form-control input-xs form-filter" name="name"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="status_dokumen"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="status_approval"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="notes"></th>
                                <th><input type="text" class="form-control input-xs form-filter datepicker" name="date" autocomplete="off"></th>
                                
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            </div>
      </div>
      <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
  </div>
  <!-- nav-tabs-custom -->
</div>
<!-- /.col -->
</div>

<?php /*
<div class="row" style="margin-top:-3%">

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Outstanding
            <small>Task</small>
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
                                <th>NO REG</th>
                                <th>TIPE</th>
                                <th>PO</th>
                                <th>NO PO</th>
                                <th>TGL PENGAJUAN</th>
                                <th>REQUESTOR</th>
                                <th>TGL PO</th>
                                <th>KODE VENDOR</th>
                                <th>NAMA VENDOR</th>
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
*/ ?>

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
                        <span class="label bg-blue"><i class="fa fa-bars"></i> ITEM DETAIL</span> <br/><br/>
                        <div class="form-group">
                            <div class="col-md-12" id="box-item-detail">
                                <!--table class="table table-condensed table-responsive" id="request-item-table">
                                    <tr>
                                        <th>NO</th>
                                        <th>ITEM</th>
                                        <th>QTY</th>
                                        <th>KODE MATERIAL</th>
                                        <th>NAMA MATERIAL</th>
                                        <th>MRP</th>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="text-align:center">No item selected</td>
                                    </tr>
                                </table-->
                            </div>
                        </div>
                        
                        <div id="box-detail-item"></div>

                        <div class="form-group">
                            <label class="col-md-2">Note</label>
                            <div class="col-md-8">
                                <textarea type="text" class="form-control input-sm attr-material-group" row="3" name="specification" id="specification"></textarea>
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php /* <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button> */ ?>
                <button type="button" class="btn btn-flat label-danger" OnClick="changeStatus('A')" style="margin-right: 5px;">Approve</button>
                <button type="button" class="btn btn-flat label-danger" OnClick="changeStatus('R')" style="margin-right: 5px;">Reject</button>
                <?php /*
                <button type="button" class="btn btn-flat label-danger" OnClick="saveRequest()" style="margin-right: 5px;">Revise</button>
                <button type="button" class="btn btn-flat label-danger" OnClick="saveRequest()" style="margin-right: 5px;">Simpan</button>
                */ ?>
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
        $("#box-detail-item").fadeIn();

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

        var grid_history = new Datatable();
        grid_history.init({
            src: jQuery("#data-table-history"),
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
                ajax: "{!! route('get.approval_grid_history') !!}",
                columns: [
                    {
                        "render": function(data, type, row) 
                        {
                            var no_registrasi= row.document_code.replace(/\//g, '-');
                            return '<a href="javascript:;" style="font-weight:bold" OnClick="approval(\'' + no_registrasi + '\')">' + row.document_code + '</a>';
                        }
                    }, 
                    /*{
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
                    */
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
                        data: 'vendor_code',
                        name: 'vendor_code'
                    },
                    {
                        data: 'vendor_name',
                        name: 'vendor_name'
                    },
                    {
                        data: 'po_date',
                        name: 'po_date'
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

         $("#box-detail-item").hide();

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

                var item = '<table class="table xtable-condensed table-responsive table-striped" id="request-item-table" style="font-size:13px">';
                item += '<th>NO.</th>';
                item += '<th>NO PO</th>';
                item += '<th>ITEM PO</th>';
                item += '<th>QTY</th>';
                item += '<th>KODE MATERIAL</th>';
                item += '<th>NAMA MATERIAL</th>';
                item += '<th>VIEW DETAIL</th>';
                if (data.item_detail.length > 0) 
                {
                    var no = 1;
                    $.each(data.item_detail, function(key, val) 
                    {
                        item += "<tr style='height: 30px !important;font-size:11px !important;'>";
                        item += "<td>" + no + "</td>";
                        item += "<td>" + val.no_po + "</td>";
                        item += "<td>" + val.item + "</td>";
                        item += "<td><label class='label bg-red'>" + val.qty + "</label></td>";
                        item += "<td>" + val.kode + "</td>";
                        item += "<td>" + val.nama + "</td>";
                        item += "<td><i class='fa fa-eye' OnClick='getDetailItem(\"" + noreg + "\","+val.id+")'></i></td>";
                        item += "</tr>";
                        no++;
                    });
                }
                else
                {
                    item += '<tr>';
                    item += ' <td colspan="7" style="text-align:center">No item selected</td>';
                    item += '</tr>';
                }
                item += '</table>';

                $("#box-item-detail").html(item);

                $("#approve-modal .modal-title").html("<i class='fa fa-edit'></i>  Approval Pendaftaran - <span style='color:#dd4b39'>" + data.no_reg + "</span><input type='hidden' id='getnoreg' name='getnoreg' value='"+data.no_reg+"' >");

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

    function getDetailItem(noreg,id)
    {
        //alert(JSON.stringify(id));
        //alert(kode);
        
        $.ajax({
            type: 'GET',
            url: "{{ url('approval/view_detail') }}/"+noreg+"/"+id,
            data: "",
            //async: false,
            dataType: 'json',
            success: function(data) 
            {
                //alert(data.length);
                var total_tab = data.length;
                var i;
                var j;
                var no=1;
                var num=1;

                var item = "<span class='label bg-blue'><i class='fa fa-bars'></i> RINCIAN INFORMASI ASSET</span><br/><br/>";
                item += "<div class='form-group'>";
                item += "<div class='col-md-12 xnav-tabs-custom'>";

                if(total_tab == 0)
                {
                    item += "<div class='callout callout-danger'><h4>Warning!</h4><p>Belum ada Informasi asset</p></div>";
                }

                item += "<ul class='nav nav-tabs'>";
                for(i=0;i<total_tab;i++)
                {
                    var active = '';
                    if(i==0){ active = 'active'; }

                    item += "<li class='"+active+"'><a href='#panel-"+no+"' data-toggle='tab' class='panel-"+no+"'>Asset "+no+"</a></li> ";
                    no++;
                }
                //item += "<li class='"+active+"'><a href='#panel-file' data-toggle='tab' class='panel-"+no+"'>Asset "+no+"</a></li> ";
                item += "</ul>";

                item += "<div class='tab-content' style='border: 1px solid #e0dcdc;border-top:none; font-size:12px !important'>";
                /*for(j=0;j<total_tab;j++)
                {
                    //var aktif = '';
                    //if(j==0){ aktif = 'active'; }
                    //item += "<div class='tab-pane "+aktif+" ' id='panel-"+num+"'>Content "+num+"</div> ";
                    //num++;
                }*/

                $.each(data, function(key, val) 
                {
                    var aktif = '';
                    if(key==0){ aktif = 'active'; }
                    item += "<div class='tab-pane "+aktif+" ' id='panel-"+num+"'>";
                    
                    item += "<div class='box-body'>";
                    
                    item += "<div class='col-md-6'>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>NO PO</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.no_po+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>TGL PO</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.tgl_po+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>KONDISI ASSET</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.kondisi_asset+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>NAMA ASSET</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.nama_asset+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>NO RANGKA/SERI</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.no_rangka_or_no_seri+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>NO MESIN / IMEI</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.no_mesin_or_imei+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>LOKASI</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.lokasi+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group'><label for='plant' class='col-md-4'>INFORMASI</label><div class='col-md-8'><textarea class='form-control' readonly>"+val.info+"</textarea></div></div>";
                    item += "</div>";

                    item += "<div class='col-md-6'>";
                    item += " <div class='form-group'><label for='plant' class='col-md-4'>JENIS ASSET</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.jenis_asset+"' id='' autocomplete='off' readonly></div></div>";
                    item += " <div class='form-group'><label for='plant' class='col-md-4'>GROUP</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.group+"' id='' autocomplete='off' readonly></div></div>";
                    item += " <div class='form-group'><label for='plant' class='col-md-4'>SUB GROUP</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.sub_group+"' id='' autocomplete='off' readonly></div></div>";
                    item += " <div class='form-group'><label for='plant' class='col-md-4'>MERK</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.merk+"' id='' autocomplete='off' readonly></div></div>";
                    item += " <div class='form-group'><label for='plant' class='col-md-4'>SPESIFIKASI/WARNA</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.spesifikasi_or_warna+"' id='' autocomplete='off' readonly></div></div>";
                    item += " <div class='form-group'><label for='plant' class='col-md-4'>TAHUN</label><div class='col-md-8'><input type='text' class='form-control input-sm' name='' value='"+val.tahun+"' id='' autocomplete='off' readonly></div></div>";
                    item += "<div class='form-group' align='right'><div class='btn btn-warning btn-sm' value='Delete' OnClick='delAsset("+val.id+")' style='margin-right:15px'><i class='fa fa-trash'></i> DELETE</div></div>";
                    //item += "<div class='form-group' align='right'><button type='button' class='btn btn-flat label-danger' OnClick='delAsset("+val.id+")' style='margin-right: 5px'>Delete</button></div>";
                    item += "</div>";

                    /* FILE UPLOAD */
                    item += "<div class='col-md-12'><div class='row'>";
                    item += "<span class='label bg-blue'><i class='fa fa-bars'></i> RINCIAN FILE ASSET</span><br/><br/>";
                    
                    var file_foto_asset = [];
                    var file_foto_seri = [];
                    var file_foto_mesin = [];

                    if (val.file === undefined || val.file.length == 0) 
                    {
                        item += "<div class='callout callout-danger'><h4>Warning!</h4><p>Belum ada file asset</p></div>";
                    }
                    else
                    {
                        $.each(val.file, function(k, v) 
                        {

                            if (v.category === "asset") {
                                file_foto_asset = {
                                    name: v.file_name,
                                    size: v.size,
                                    type: v.type,
                                    file: v.file
                                };
                            } 
                            else if (v.category === "no seri") 
                            {
                                file_foto_seri = {
                                    name: v.file_name,
                                    size: v.size,
                                    type: v.type,
                                    file: v.file
                                };
                            } 
                            else if (v.category === "imei") 
                            {
                                file_foto_mesin = {
                                    name: v.file_name,
                                    size: v.size,
                                    type: v.type,
                                    file: v.file
                                };
                            }

                            //alert(v.file_thumb); 
                            //$("#foto_thumb").prop('src', v.file_thumb);

                            //item += "<div class='col-md-4'><b>"+v.file_category+"</b><br/>"+v.filename+"</div>";
                            
                            item += "<div class='col-md-4'><b>"+v.file_category+"</b><br/> <img id='foto_thumb' name='foto_thumb' data-status='0' title='' class='img-responsive' src='"+v.file_thumb+"'></div>";
                        
                        });
                    }
                    
                    item += "</div></div>";
                    /* END FILE UPLOAD */
                    
                    item += "</div>";

                    item += "</div>";
                    num++;
                });

                item += "<div>";

                item += "</div>";
                item += "</div>";

                $("#box-detail-item").fadeIn();
                $("#box-detail-item").html(item);
                //alert(noreg);
            },
            error: function(x) 
            {                           
                alert("Error: "+ "\r\n\r\n" + x.responseText);
            }
        });  
        
    }

    function delAsset(id)
    {
        if(confirm('are you sure?'))
        {
            
            //e.preventDefault();
            var param = $(this).serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ url('approval/delete_asset') }}/"+id,
                method: "POST",
                data: param,
                beforeSend: function() {
                    jQuery('.loading-event').fadeIn();
                },
                success: function(result) 
                {
                    //alert(result.status);
                    if (result.status) 
                    {
                        $("#approve-modal").modal("hide");
                        $("#data-table").DataTable().ajax.reload();
                        notify({
                            type: 'success',
                            message: result.message
                        });
                    } else {
                        notify({
                            type: 'warning',
                            message: result.message
                        });
                    }
                    
                },
                complete: function() {
                    jQuery('.loading-event').fadeOut();
                }
            });
        }
    }

    function changeStatus(status)
    {
        var getnoreg = $("#getnoreg").val();
        var no_registrasi= getnoreg.replace(/\//g, '-');
        var specification = $("#specification").val();
        if( status == 'A' ){ status_desc = 'approve'; }else
        if( status == 'R' ){ status_desc = 'reject' }else{ status_desc = 'cancel'; }

        if(confirm('confirm '+status_desc+' data ?'))
        {
            
            //e.preventDefault();
            var param = $(this).serialize();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: "{{ url('approval/update_status') }}/"+status+"/"+no_registrasi,
                method: "POST",
                data: param+"&parNote="+specification+"",
                beforeSend: function() {
                    jQuery('.loading-event').fadeIn();
                },
                success: function(result) 
                {
                    //alert(result.status);
                    if (result.status) 
                    {
                        $("#approve-modal").modal("hide");
                        $("#data-table").DataTable().ajax.reload();
                        notify({
                            type: 'success',
                            message: result.message
                        });
                    } else {
                        notify({
                            type: 'warning',
                            message: result.message
                        });
                    }
                    
                },
                complete: function() {
                    jQuery('.loading-event').fadeOut();
                }
            });
        }
    }

</script>
@stop