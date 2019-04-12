@extends('adminlte::page')
@section('title', 'FAMS - Request')

@section('content')
<style>
    label {
        font-weight: 500;
    }

    .select-img:hover {
        opacity: 0.5
    }

    .fmdb-input-default {
        background-color: #eee !important;
    }
</style>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="box">
            <form class="form-horizontal request-form" id="request-form">
                <div class="box-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="plant" class="col-md-3">Tipe Transaksi</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" name="transaction_type" id="transaction_type">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-3">Tanggal</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm datepicker" name="request_date" id="request_date" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-3">Business Area</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="business_area" id="business_area">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-3">No. Purchare Order</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" name="po_no" id="po_no" value="5013103287">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-3">Tgl PO</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-sm" name="description" value="23/03/2019" id="description" autocomplete="off" readonly required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-3">Kode vendor</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" name="description" id="description" value="XXXXX" autocomplete="off" readonly required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-3">Nama vendor</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-sm" name="description" value="DAYA ANUGERAH MANDIRI" id="description" autocomplete="off" readonly required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-3">
                                <button type="button" class="btn btn-flat btn-danger" OnClick="addItem()">Add</button>
                            </label>
                            <div class="col-md-9">

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered table-condensed" id="request-item-table">
                                    <tr>
                                        <th>Item PO</th>
                                        <th>Kode</th>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        <th>Qty diajukan</th>
                                        <th>Qty Outstanding</th>
                                    </tr>
                                    <tr>
                                        <td colspan="6" style="text-align:center">No item selected</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="box-footer clearfix">
                        <button type="submit" class="btn btn-danger btn-flat pull-right" style="margin-right: 5px;">Next <i class="fa fa-arrow-circle-o-right"></i></button>
                        <button type="button" class="btn btn-default btn-flat btn-cancel pull-right" style="margin-right: 5px;">Cancel</button>
                    </div>
                </div>
            </form>
            <form class="form-horizontal code-asset-form hide" id="code-asset-form">
                <div class="box-body">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Tanggal</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm " name="" id="" value="23/03/2019" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Business Area</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm" name="business_area" id="business_area" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">No. PO</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control input-sm" name="po_no" id="po_no" value="5013103287" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Tgl PO</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm" name="description" value="23/03/2019" id="description" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Kode vendor</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" name="description" id="description" value="XXXXX" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Nama vendor</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control input-sm" name="description" value="DAYA ANUGERAH MANDIRI" id="description" autocomplete="off" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Item PO</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm text-right" name="description" value="1" id="description" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Qty Index</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control input-sm text-right" name="description" value="1" id="description" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Kode material</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control input-sm" name="description" value="XXXX-1" id="description" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="plant" class="col-md-2">Nama material</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control input-sm" name="description" value="SEPEDA MOTOR 150 HONDA VERZA" id="description" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#panel-initial" data-toggle="tab" class="panel-initial">Rincian Informasi Asset | page: <span id="page"></span></a></li>
                                    <li class="pull-right"><a href="javascript:nextPage()" class="text-muted" id="btn_next">Next <i class="fa fa-arrow-right"></i></a></li>
                                    <li class="pull-right"><a href="javascript:prevPage()" class="text-muted" id="btn_prev"><i class="fa fa-arrow-left"></i> Prev</a></li>
                                </ul>
                                <div class="tab-content" style="border: 1px solid #e0dcdc;border-top:none">
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
                                                    <input type="text" class="form-control input-sm attr-material-group" name="specification" id="specification">
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
                        <button type="submit" class="btn btn-danger btn-flat pull-right" style="margin-right: 5px;"><i class="fa fa-save"></i> Submit</button>
                        <button type="button" class="btn btn-default btn-flat btn-back-request-form pull-right" style="margin-right: 5px;"><i class="fa fa-arrow-circle-left"></i> Back</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
</div>
</div>
@stop
@section('js')
<script>
    var imgFiles = [];
    var addFile = 2;
    var request_item = [];
    var item_count = 1;
    var request_item_page = [];
    var data_page = new Array(3);
    var current_page = 1;
    var records_per_page = 1;
    window.onbeforeunload = confirmExit;


    function confirmExit() {
        return "You have attempted to leave this page.If you have made any changes to the fields without clicking the Save button, your changes will be lost. Are you sure you want to exit this page?";
    }

    jQuery(document).ready(function() {
        jQuery(".btn-cancel").on('click', function() {
            var conf = confirm("Are you sure you want to cancel this request?");
            document.getElementById("request-form").reset();
            if (conf == true) {
                request_item = [];
                request_item_page = [];
                createItemRequestTable();
                notify({
                    type: 'error',
                    message: 'form has been cleared!'
                });
            }
        });

        jQuery("#request_date").datepicker({
            format: "dd/mm/yyyy",
            autoclose: true
        });

        jQuery("#transaction_type").select2({
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

        jQuery("#request-form").on("submit", function(e) {
            e.preventDefault();

            if (request_item.length > 0) {
                jQuery('.request-form').addClass('hide');
                jQuery('.code-asset-form').removeClass('hide');
                topFunction();
            } else {
                notify({
                    type: 'warning',
                    message: 'please, add an item'
                });
            }


        });

        jQuery("#code-asset-form").on("submit", function(e) {
            e.preventDefault();
            jQuery('.code-asset-form').addClass('hide');
            jQuery('.request-form').removeClass('hide');
            notify({
                type: 'error',
                message: 'reqeust has been submited!'
            });
            document.getElementById("request-form").reset();
            topFunction();
        });

        jQuery(".btn-back-request-form").on("click", function(e) {
            jQuery('.code-asset-form').addClass('hide');
            jQuery('.request-form').removeClass('hide');
            topFunction();
        })

        jQuery('#form-basic-data').on('submit', function(e) {
            e.preventDefault();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var form = jQuery('#form-initial').find('input, select, textarea').appendTo('#form-basic-data');
            var param = new FormData(this);
            jQuery.ajax({
                url: "{{ url('materialrequest/post') }}",
                type: "POST",
                data: param,
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function() {
                    jQuery('.loading-event').fadeIn();
                },
                success: function(result) {
                    if (result.status) {
                        notify({
                            type: 'success',
                            message: result.message
                        });
                        window.location.href = "{{ url('mastermaterial') }}";
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
        });

        jQuery("#asset_name").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_name = jQuery(this).val();
        });

        jQuery("#asset_type").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_type = jQuery(this).val();
        });

        jQuery("#asset_group").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_group = jQuery(this).val();
        });

        jQuery("#asset_sub_group").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_sub_group = jQuery(this).val();
        });

        jQuery("#asset_brand").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_brand = jQuery(this).val();
        });

        jQuery("#asset_imei").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_imei = jQuery(this).val();
        });

        jQuery("#asset_police_no").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_police_no = jQuery(this).val();
        });

        jQuery("#asset_serie_no").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_serie_no = jQuery(this).val();
        });

        jQuery("#asset_specification").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_specification = jQuery(this).val();
        });

        jQuery("#asset_year").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_year = jQuery(this).val();
        });

        jQuery("#asset_info").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_info = jQuery(this).val();
        });

        jQuery("#asset_pic_name").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_pic_name = jQuery(this).val();
        });

        jQuery("#asset_pic_level").on('keyup', function() {
            var id = current_page - 1;
            data_page[id].asset_pic_level = jQuery(this).val();
        });
    });

    function addItem() {
        request_item.push({
            item_po: 1,
            code: makeid(5),
            name: "SEPEDA MOTOR 150 HONDA VERZA - " + item_count,
            qty: 1,
            request_qty: 1,
            outstanding_qty: 2,
        });
        item_count++;

        createPage();
        createItemRequestTable();
    }

    function createPage() {
        request_item_page = [];
        data_page = [];
        jQuery.each(request_item, function(key, val) {
            for (var i = 0; i < val.request_qty; i++) {
                request_item_page.push({
                    item_po: val.item_po,
                    code: val.code,
                    name: val.name,
                });

                data_page.push({
                    asset_type: '',
                    asset_group: '',
                    asset_sub_group: '',
                    asset_name: '',
                    asset_brand: '',
                    asset_imei: '',
                    asset_police_no: '',
                    asset_serie_no: '',
                    asset_specification: '',
                    asset_year: '',
                    asset_pic_name: '',
                    asset_pic_level: '',
                    asset_foto: '',
                    asset_info: '',
                    foto_asset: {
                        name: '',
                        size: '',
                        type: '',
                        file: ''
                    },
                    foto_asset_seri: {
                        name: '',
                        size: '',
                        type: '',
                        file: ''
                    },
                    foto_asset_mesin: {
                        name: '',
                        size: '',
                        type: '',
                        file: ''
                    }
                });
            }
        });
        changePage(1);
        assetInfo(1);
    }

    function makeid(length) {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for (var i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function makeInt(length) {
        var text = "";
        var possible = "0123456789";

        for (var i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    function remove(obj) {
        var selected = request_item[obj];
        var conf = confirm("Are you sure you want to delete this data?");
        if (conf == true) {
            /*  if (selected.id) {
                 request_item[obj] = {
                     "id": selected.id,
                     "item": selected.item,
                     "name": selected.name,
                     "price": selected.price,
                     "qty": selected.qty,
                     "deleted": 1
                 }
             } else {
                 var index = product_selected.indexOf(product_selected[obj]);
                 if (index > -1) {
                     product_selected.splice(obj, 1);
                 }
             } */

            var index = request_item.indexOf(request_item[obj]);
            if (index > -1) {
                request_item.splice(obj, 1);
            }

            createItemRequestTable();
        }
    }

    function createItemRequestTable() {
        var item = '<table class="table table-bordered table-condensed" id="request-item-table">';
        item += '<tr>';
        item += '<th>Item PO</th>';
        item += '<th>Kode</th>';
        item += ' <th>Name</th>';
        item += '<th>Qty</th>';
        item += '<th width="115px">Qty diajukan</th></th>';
        item += '<th>Qty Outstanding</th>';
        item += '<th style="width: 40px"></th>';
        item += '</tr>';

        if (request_item.length > 0) {
            jQuery.each(request_item, function(key, val) {
                item += "<tr>";
                item += "<td>" + val.item_po + "</td>";
                item += "<td>" + val.code + "</td>";
                item += "<td>" + val.name + "</td>";
                item += "<td style='text-align:right'>" + val.qty + "</td>";
                item += '<td class="text-center">';
                item += '<div class="input-group">';
                item += ' <div style="cursor:pointer" class="input-group-addon bg-gray"  OnClick="min(\'qty_' + key + '\');qtyEdit(\'' + key + '\')">-</div>';
                item += '<input type="text" class="form-control input-sm text-center" value=' + val.request_qty + ' id="qty_' + key + '" maxlength="6">';
                item += ' <div style="cursor:pointer" class="input-group-addon bg-gray" OnClick="plus(\'qty_' + key + '\');qtyEdit(\'' + key + '\')">+</div>';
                item += '</td>';
                item += "<td style='text-align:right'>" + val.outstanding_qty + "</td>";
                item += '<td width="30px" style="text-align:center"><button type="button" class="btn btn-flat btn-xs btn-danger" onClick="remove(\'' + key + '\');"><i class="fa fa-trash"></i></button></td>';
                item += "</tr>";
            });
        } else {
            item += '<tr>';
            item += ' <td colspan="7" style="text-align:center">No item selected</td>';
            item += '</tr>';
        }
        item += "</table>";
        jQuery("#request-item-table").html(item);
    }

    function qtyEdit(obj) {
        var selected = request_item[obj];
        var qty = jQuery('#qty_' + obj).val();

        request_item[obj] = {
            item_po: selected.item_po,
            code: selected.code,
            name: selected.name,
            qty: selected.qty,
            request_qty: qty,
            outstanding_qty: selected.outstanding_qty,
        };

        createItemRequestTable();
        createPage();
    }

    function assetInfo(id) {
        var obj = id - 1;
        var item = request_item[obj];

        var id = current_page - 1;
        var item = data_page[id];

        jQuery('#asset_name').val(item.asset_name);
        jQuery('#asset_type').val(item.asset_type);
        jQuery('#asset_group').val(item.asset_group);
        jQuery('#asset_sub_group').val(item.asset_sub_group);
        jQuery('#asset_brand').val(item.asset_brand);
        jQuery('#asset_imei').val(item.asset_imei);
        jQuery('#asset_police_no').val(item.asset_police_no);
        jQuery('#asset_serie_no').val(item.asset_police_no);
        jQuery('#asset_specification').val(item.asset_specification);
        jQuery('#asset_year').val(item.asset_year);
        jQuery('#asset_pic_name').val(item.asset_pic_name);
        jQuery('#asset_pic_level').val(item.asset_pic_level);

        if (item.foto_asset.file) {
            jQuery("#foto_asset_thumb_1").prop('src', item.foto_asset.file);
            jQuery(".btn-foto-asset-remove").removeClass('hide');
        } else {
            jQuery("#foto_asset_thumb_1").prop('src', "{{URL::asset('img/add-img.png')}}");
            jQuery(".btn-foto-asset-remove").addClass('hide');
        }

        if (item.foto_asset_seri.file) {
            jQuery("#foto_no_seri_thumb_1").prop('src', item.foto_asset_seri.file);
            jQuery(".btn-foto-seri-remove").removeClass('hide');
        } else {
            jQuery("#foto_no_seri_thumb_1").prop('src', "{{URL::asset('img/add-img.png')}}");
            jQuery(".btn-foto-seri-remove").addClass('hide');
        }

        if (item.foto_asset_mesin.file) {
            jQuery("#foto_mesin_thumb_1").prop('src', item.foto_asset_mesin.file);
            jQuery(".btn-foto-mesin-remove").removeClass('hide');
        } else {
            jQuery("#foto_mesin_thumb_1").prop('src', "{{URL::asset('img/add-img.png')}}");
            jQuery(".btn-foto-mesin-remove").addClass('hide');
        }
    }

    function openFile(code, id) {
        if (code == 'asset') {
            jQuery("#foto_asset_" + id).trigger('click');
        } else if (code == 'seri') {
            jQuery("#foto_no_seri_" + id).trigger('click');
        } else if (code == 'mesin') {
            jQuery("#foto_mesin_" + id).trigger('click');
        }
    }

    function showImage(code, id) {
        var obj = current_page - 1;

        if (code == 'asset') {
            var src = document.getElementById("foto_asset_1");
            var target = document.getElementById("foto_asset_thumb_1");
        } else if (code == 'seri') {
            var src = document.getElementById("foto_no_seri_1");
            var target = document.getElementById("foto_no_seri_thumb_1");
        } else if (code == 'mesin') {
            var src = document.getElementById("foto_mesin_1");
            var target = document.getElementById("foto_mesin_thumb_1");
        }


        var fr = new FileReader();
        fr.onload = function(e) {
            target.src = this.result;
            if (code == 'asset') {
                data_page[obj].foto_asset.file = this.result;
            } else if (code == 'seri') {
                data_page[obj].foto_asset_seri.file = this.result;
            } else if (code == 'mesin') {
                data_page[obj].foto_asset_mesin.file = this.result;
            }
        };

        var foto = src.files[0];
        if (code == 'asset') {
            data_page[obj].foto_asset.name = foto.name;
            data_page[obj].foto_asset.type = foto.type;
            data_page[obj].foto_asset.size = foto.size;
            jQuery(".btn-foto-asset-remove").removeClass('hide');

        } else if (code == 'seri') {
            data_page[obj].foto_asset_seri.name = foto.name;
            data_page[obj].foto_asset_seri.type = foto.type;
            data_page[obj].foto_asset_seri.size = foto.size;
            jQuery(".btn-foto-seri-remove").removeClass('hide');
        } else if (code == 'mesin') {
            data_page[obj].foto_asset_mesin.name = foto.name;
            data_page[obj].foto_asset_mesin.type = foto.type;
            data_page[obj].foto_asset_mesin.size = foto.size;
            jQuery(".btn-foto-mesin-remove").removeClass('hide');
        }

        fr.readAsDataURL(src.files[0]);
        jQuery('.btn-remove-image' + id).removeClass('hide');
        var status = jQuery('#material-images-' + id).data('status');

        data_page[obj].asset_pic_level;
    }

    function removeImage(code, id) {
        var obj = id - 1;
        if (code == 'asset') {
            data_page[obj].foto_asset.file = '';
            data_page[obj].foto_asset.name = '';
            data_page[obj].foto_asset.type = '';
            data_page[obj].foto_asset.size = '';
            jQuery("#foto_asset_thumb_1").prop('src', "{{URL::asset('img/add-img.png')}}");
            jQuery(".btn-foto-asset-remove").addClass('hide');
        } else if (code == 'seri') {
            data_page[obj].foto_asset_seri.file = "";
            data_page[obj].foto_asset_seri.name = "";
            data_page[obj].foto_asset_seri.type = "";
            data_page[obj].foto_asset_seri.size = "";
            jQuery("#foto_no_seri_thumb_1").prop('src', "{{URL::asset('img/add-img.png')}}");
            jQuery(".btn-foto-seri-remove").addClass('hide');
        } else if (code == 'mesin') {
            data_page[obj].foto_asset_mesin.file = "";
            data_page[obj].foto_asset_mesin.name = "";
            data_page[obj].foto_asset_mesin.type = "";
            data_page[obj].foto_asset_mesin.size = "";
            jQuery("#foto_mesin_thumb_1").prop('src', "{{URL::asset('img/add-img.png')}}");
            jQuery(".btn-foto-mesin-remove").addClass('hide');
        }
    }

    function prevPage() {
        if (current_page > 1) {
            current_page--;
            changePage(current_page);
            assetInfo(current_page);
        }
    }

    function nextPage() {
        if (current_page < numPages()) {
            current_page++;
            changePage(current_page);
            assetInfo(current_page);
        }
    }

    function changePage(page) {
        var btn_next = document.getElementById("btn_next");
        var btn_prev = document.getElementById("btn_prev");
        var page_span = document.getElementById("page");

        if (page < 1) page = 1;
        if (page > numPages()) page = numPages();
        page_span.innerHTML = page + '/' + request_item_page.length;

        if (page == 1) {
            btn_prev.style.visibility = "hidden";
        } else {
            btn_prev.style.visibility = "visible";
        }

        if (page == numPages()) {
            btn_next.style.visibility = "hidden";
        } else {
            btn_next.style.visibility = "visible";

        }
    }

    function numPages() {
        return Math.ceil(request_item_page.length / records_per_page);
    }

    function min(param) {
        if (jQuery("#" + param).val() > 1) {
            if (jQuery("#" + param).val() > 1) jQuery("#" + param).val(+jQuery("#" + param).val() - 1);
        }
    }

    function plus(param) {
        jQuery("#" + param).val(+jQuery("#" + param).val() + 1)
    }

    jQuery('.add').click(function() {
        jQuery(this).prev().val(+jQuery(this).prev().val() + 1);
    });

    jQuery('.sub').click(function() {
        if (jQuery(this).next().val() > 1) {
            if (jQuery(this).next().val() > 1) jQuery(this).next().val(+jQuery(this).next().val() - 1);
        }
    });
</script>
@stop