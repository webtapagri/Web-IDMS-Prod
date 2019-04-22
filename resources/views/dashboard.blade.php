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
                        <button class="btn btn-sm btn-flat btn-default btn-refresh-data-table" title="refresh"><i class="glyphicon glyphicon-refresh"></i></button>
                    </div>
                    <table id="data-table" class="table table-condensed" width="100%">
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
                            </tr>
                            <tr role="row" class="filter">
                                <th><input type="text" class="form-control input-sm form-filter" name="transaction_type"></th>
                                <th><input type="text" class="form-control input-sm form-filter" name="no_po"></th>
                                <th><input type="text" class="form-control input-sm form-filter datepicker" name="request_date" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-sm form-filter" name="requestor"></th>
                                <th><input type="text" class="form-control input-sm form-filter datepicker" name="po_date" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-sm form-filter" name="vendor_code"></th>
                                <th><input type="text" class="form-control input-sm form-filter" name="vendor_name"></th>
                                <th></th>
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
                            if(row.type == 1) {
                                var content = 'Barang'
                            } else  if(row.type == 2) {
                                var content = 'Jasa'
                            }else  if(row.type == 3) {
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
                    }
                ],
                columnDefs: [
                    {
                        targets: [1,5],
                        width: '12%'
                    },
                    {
                        targets: [7],
                        width: '15%'
                    },
                    {
                        targets: [2,4,0,3],
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

</script>
@stop 