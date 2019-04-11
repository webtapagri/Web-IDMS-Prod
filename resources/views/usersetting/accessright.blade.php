@extends('adminlte::page')
@section('title', 'FMDB - Access right')
@section('content')
<section class="content">
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
                                    <th width="30%">Role</th>
                                    <th>Module</th>
                                    <th>Menu</th>
                                    <th>C</th>
                                    <th>R</th>
                                    <th>U</th>
                                    <th>D</th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td><input type="text" class="form-control input-sm form-filter" name="role"> </td>
                                    <td><input type="text" class="form-control input-sm form-filter" name="module"> </td>
                                    <td><input type="text" class="form-control input-sm form-filter" name="menu"> </td>
                                    <td><input type="text" class="form-control input-sm form-filter" name="create"> </td>
                                    <td><input type="text" class="form-control input-sm form-filter" name="read"> </td>
                                    <td><input type="text" class="form-control input-sm form-filter" name="update"> </td>
                                    <td><input type="text" class="form-control input-sm form-filter" name="delete"> </td>
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
    </div>
</section>
<div id="add-data-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" width="900px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <form id="data-form">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-xs-12">
                            <label class="control-label" for="name">Role</label>
                            <select class="form-control select2" name='role_id' id="role_id" requried>
                                <option></option>
                            </select>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="name">Menu</label>
                            <select class="form-control select2" name='menu' id="menu" maxlength="200" requried>
                                <option></option>
                            </select>
                            <input type="hidden" name='edit_id' id="edit_id">
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="name">Operation</label>
                            <select class="form-control select2" name='operation' id="operation" required>
                                <option></option>
                            </select>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="name">Description</label>
                            <textarea class="form-control" name='description' id="description" requried></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-flat btn-success" style="margin-right: 5px;">Submit</button>
                </div>
            </form>
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
                    url: "{!! route('get.accessright_grid') !!}"
                },
                columns: [{
                        data: 'role_name',
                        name: 'role_name'
                    },
                    {
                        data: 'module_name',
                        name: 'module_name'
                    },
                    {
                        data: 'menu_name',
                        name: 'menu_name'
                    },
                    {
                        "render": function(data, type, row) {
                            var content = '<input type="checkbox" class="checkbox" data-id="' + row.access_id + "-" + row.role_id + "-" + row.module_id + "-" + row.menu_id + '"  ' + (row.create ? 'checked' : '') + '/>';

                            return content;
                        }
                    },
                    {
                        "render": function(data, type, row) {
                            var content = '<input type="checkbox" class="checkbox" data-id="' + row.access_id + "-" + row.role_id + "-" + row.module_id + "-" + row.menu_id + '"  ' + (row.read ? 'checked' : '') + '/>';

                            return content;
                        }
                    },
                    {
                        "render": function(data, type, row) {
                            var content = '<input type="checkbox" class="checkbox text-center" data-id="' + row.access_id + "-" + row.role_id + "-" + row.module_id + "-" + row.menu_id + '"  ' + (row.update ? 'checked' : '') + '/>';

                            return content;
                        }
                    },
                    {
                        "render": function(data, type, row) {
                            var content = '<input type="checkbox" class="checkbox text-center" data-id="' + row.access_id + "-" + row.role_id + "-" + row.module_id + "-" + row.menu_id + '"  ' + (row.delete ? 'checked' : '') + '/>';

                            return content;
                        }
                    },
                ],
                columnDefs: [{
                    targets: [3, 4, 5, 6],
                    orderable: false,
                    width: '8%',
                    className: 'align-middle'
                }],
                oLanguage: {
                    sProcessing: "<div id='datatable-loader'></div>",
                    sEmptyTable: "Data tidak di temukan",
                    sLoadingRecords: ""
                },
                "order": [],
            }
        });



        var role = jQuery.parseJSON(JSON.stringify(dataJson("{!! route('get.select_role') !!}")));
        jQuery('input[name="role"]').select2({
            data: role,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        jQuery('input[name="create"], input[name="read"], input[name="update"], input[name="delete"]').select2({
            data: [{
                    id: '0',
                    text: 'N'
                },
                {
                    id: '1',
                    text: 'Y'
                },
            ],
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        var menu = jQuery.parseJSON(JSON.stringify(dataJson("{!! route('get.select_menu') !!}")));
        jQuery('input[name="menu"]').select2({
            data: menu,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        var module = jQuery.parseJSON(JSON.stringify(dataJson("{!! route('get.select_module') !!}")));
        jQuery('input[name="module"]').select2({
            data: module,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        jQuery('.btn-add').on('click', function() {
            document.getElementById("data-form").reset();
            jQuery('#menu').select2('val', '');
            jQuery('#menu').trigger('change');
            jQuery('#role_id').select2('val', '');
            jQuery('#role_id').trigger('change');
            jQuery('#operation').select2('val', '');
            jQuery('#operation').trigger('change');

            jQuery('#role_id').prop('disabled', false);
            jQuery("#edit_id").val("");
            jQuery("#add-data-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            jQuery("#add-data-modal .modal-title").html("<i class='fa fa-plus'></i> Create new data");
            jQuery("#add-data-modal").modal("show");
        });

        jQuery('.btn-edit').on('click', function() {
            jQuery("#add-data-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            jQuery("#add-data-modal .modal-title").html("<i class='fa fa-pencil'></i> Edit data");
            jQuery("#add-data-modal").modal("show");
        });

        jQuery('#data-form').on('submit', function(e) {
            e.preventDefault();
            var param = jQuery(this).serialize();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            jQuery.ajax({
                url: "{{ url('accessright/post') }}",
                method: "POST",
                data: param,
                beforeSend: function() {
                    jQuery('.loading-event').fadeIn();
                },
                success: function(result) {
                    if (result.status) {
                        if (result.exist) {
                            notify({
                                type: 'warning',
                                message: result.message
                            });
                        } else {
                            jQuery("#add-data-modal").modal("hide");
                            jQuery("#data-table").DataTable().ajax.reload();
                            notify({
                                type: 'success',
                                message: result.message
                            });
                        }
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
        })
    });

    function edit(row) {
        document.getElementById("data-form").reset();
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('accessright/edit/?id=') }}" + row)));
        jQuery("#edit_id").val(row);
        jQuery('#menu').select2('val', result.menu_code);
        jQuery('#menu').trigger('change');
        jQuery('#role_id').select2('val', result.id_role);
        jQuery('#role_id').trigger('change');
        jQuery('#operation').select2('val', result.operation);
        jQuery('#operation').trigger('change');
        jQuery('#description').val(result.description);

        jQuery("#add-data-modal .modal-title").html("<i class='fa fa-edit'></i> Update data " + result.role_name);
        jQuery("#add-data-modal").modal("show");
    }

    function inactive(id) {
        var conf = confirm("anda yakin mau menghapus data ini?");
        if (conf == true) {
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            jQuery.ajax({
                url: "{{ url('accessright/inactive') }}",
                method: "POST",
                data: {
                    id: id
                },
                beforeSend: function() {
                    jQuery('.loading-event').fadeIn();
                },
                success: function(result) {
                    if (result.status) {
                        jQuery("#data-table").DataTable().ajax.reload();
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