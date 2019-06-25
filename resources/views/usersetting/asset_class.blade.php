@extends('adminlte::page')
@section('title', 'FAMS - Asset Class')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="table-container">
                    <div class="table-actions-wrapper">
                        <span></span>
                        <button class="btn btn-sm btn-flat btn-danger btn-refresh-data-table" title="refresh"><i class="glyphicon glyphicon-refresh"></i></button>
                        @if($data['access']->create == 1)
                        <button class="btn btn-sm btn-flat btn-danger btn-add"><i class="glyphicon glyphicon-plus" title="Add new data"></i></button>
                        @endif
                    </div>
                    <table id="data-table" class="table table-condensed" width="100%">
                        <thead>
                            <tr role="row" class="heading">
                                <th>ID</th>
                                <th>JENIS ASSET CODE</th>
                                <th>JENIS ASSET DESCRIPTION</th>
                                <th width="8%">ACTION</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="jenis_asset_code" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="jenis_asset_description" autocomplete="off"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            
            <div id="row-group-asset" style="margin-top:10px;display:none">
                <div class="callout callout-info">
                    <h4>GROUP ASSET</h4>
                    <p>JENIS ASSET CODE : <span id="jenis-asset-code"></span> </p>
                </div>
               
                <div class="table-container">
                     <div class="xtable-actions-wrapper pull-right">
                        <button class="btn btn-sm btn-flat btn-danger btn-refresh-data-table" title="refresh"><i class="glyphicon glyphicon-refresh"></i></button>
                        <!-- @if($data['access']->create == 1)-->
                        <button class="btn btn-sm btn-flat btn-danger btn-add-detail"><i class="glyphicon glyphicon-plus" title="Add new data detail"></i></button>
                        <!-- @endif -->
                    </div>
                    <table id="data-table-group-asset" class="table table-condensed" width="100%">
                        <thead>
                            <tr role="row" class="heading">
                                <th>ID</th>
                                <th>GROUP CODE</th>
                                <th>GROUP DESCRIPTION</th>
                                <th width="8%">ACTION</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="group_code" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="group_description" autocomplete="off"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div id="row-detail-job" style="margin-top:10px;display:none">
                <div class="callout callout-info">
                    <h4>JOB DETAIL WORKFLOW</h4>
                    <p>WORKFLOW DETAIL CODE : <span id="workflow-detail-code"></span> </p>
                </div>
                <div class="table-container">
                     <div class="xtable-actions-wrapper pull-right">
                        <button class="btn btn-sm btn-flat btn-danger btn-refresh-data-table" title="refresh"><i class="glyphicon glyphicon-refresh"></i></button>
                        <!-- @if($data['access']->create == 1)-->
                        <button class="btn btn-sm btn-flat btn-danger btn-add-detail-job"><i class="glyphicon glyphicon-plus" title="Add new data detail job"></i></button>
                        <!-- @endif -->
                    </div>
                    <table id="data-table-detail-job" class="table table-condensed" width="100%">
                        <thead>
                            <tr role="row" class="heading">
                                <th>ID</th>
                                <th>WORKFLOW DETAIL CODE</th>
                                <th>ID ROLE</th>
                                <th>SEQUENCE</th>
                                <th>OPERATION</th>
                                <th>LINTAS</th>
                                <th width="8%">ACTION</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="workflow_group_name" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="name" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="seq" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="operation" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="lintas" autocomplete="off"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>


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
                            <label class="control-label" for="jenis-asset-code">Jenis Asset Code</label>
                            <input class="form-control" name='jenis_asset_code' id="jenis_asset_code" maxlength="400" requried>
                            <input type="hidden" name='edit_id' id="edit_id">
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="jenis-asset-description">Jenis Asset Description</label>
                            <input class="form-control" name='jenis_asset_description' id="jenis_asset_description" requried>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-flat btn-danger" style="margin-right: 5px;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="add-data-modal-detail" class="modal fade" role="dialog">
    <div class="modal-dialog" width="900px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <form id="data-form-detail">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-xs-12">
                            <label class="control-label" for="workflow-code">Workflow Code</label>
                            <input class="form-control" name='workflow_code' id="workflow_code" requried>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="group-name">Group Name</label>
                            <input class="form-control" name='workflow_group_name' id="workflow_group_name" maxlength="400" requried>
                            <input type="hidden" name='edit_workflow_code_detail' id="edit_workflow_code_detail">
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="seq">Seq</label>
                            <input class="form-control" name='seq' id="seq" maxlength="400" requried>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="description">Description</label>
                            <input class="form-control" name='description' id="description" maxlength="400" requried>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-flat btn-danger" style="margin-right: 5px;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="add-data-modal-detail-job" class="modal fade" role="dialog">
    <div class="modal-dialog" width="900px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <form id="data-form-detail-job">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-xs-12">
                            <label class="control-label" for="workflow-detail-code">Workflow Detail Code</label>
                            <input class="form-control" name='workflow_detail_code' id="workflow_detail_code" requried>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="id-role">ID Role</label>
                            <input class="form-control" name='id_role' id="id_role" maxlength="400" requried>
                            <input type="hidden" name='edit_workflow_code_detail_job' id="edit_workflow_code_detail_job">
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="seq">Seq</label>
                            <input class="form-control" name='seq_job' id="seq_job" maxlength="400" requried>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="operation">Operation</label>
                            <input class="form-control" name='operation' id="operation" maxlength="400" requried>
                        </div>
                         <div class="col-xs-12">
                            <label class="control-label" for="lintas">Lintas</label>
                            <input class="form-control" name='lintas' id="lintas" maxlength="400" requried>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-flat btn-danger" style="margin-right: 5px;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@stop
@section('js')
<script>
    var attribute = [];
    jQuery(document).ready(function() 
    {
        $("#row-group-asset").hide();

        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var grid = new Datatable();
        grid.init({
            src: $("#data-table"),
            onSuccess: function(grid) {},
            onError: function(grid) {},
            onDataLoad: function(grid) {},
            destroy: true,
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
                    url: "{!! route('get.grid_asset_class') !!}"
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'jenis_asset_code',
                        name: 'jenis_asset_code'
                    },
                    {
                        data: 'jenis_asset_description',
                        name: 'jenis_asset_description'
                    },
                    {
                        "render": function(data, type, row) {
                            var update = "{{ $data['access']->update }}";
                            var remove = "{{ $data['access']->delete }}";
                            var content = '';

                            if (update == 1) 
                            {
                                content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-edit" title="edit data ' + row.id + '" onClick="edit(' + row.id + ')"><i class="fa fa-pencil"></i></button>';
                                content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-view" title="detail data ' + row.id + '" onClick="group_asset(\''+row.jenis_asset_code+'\',\'a\')"><i class="fa fa-clone"></i></button>';
                            }
                            
                            /*
                            if (remove == 1) 
                            {
                                content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-activated  {{ ($data["access"]->delete == 1 ? "":"hide") }}  ' + (row.deleted == 0 ? '' : 'hide') + '" style="margin-left:5px"  onClick="inactive(' + row.id + ')"><i class="fa fa-trash"></i></button>';
                                content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-inactivated {{ ($data["access"]->delete == 1 ? "":"hide") }}  ' + (row.deleted == 1 ? '' : 'hide') + '" style="margin-left:5px"  onClick="active(' + row.id + ')"><i class="fa fa-check"></i></button>';
                            }
                            */

                            return content;
                        }
                    }
                ],
                columnDefs: [
                    /*{
                        targets: [0],
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        targets: [2],
                        className: 'text-center',
                        orderable: false,
                        width: '10%'
                    }*/
                ],
                oLanguage: {
                    sProcessing: "<div id='datatable-loader'></div>",
                    sEmptyTable: "Data tidak di temukan",
                    sLoadingRecords: ""
                },
                "order": [],
            }
        });

        jQuery("input[name='status']").select2({
            data: [{
                    id: 0,
                    text: 'Y'
                },
                {
                    id: 1,
                    text: 'N'
                },
            ],
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        var role = jQuery.parseJSON(JSON.stringify(dataJson('{!! route("get.select_menu") !!}')));
        $('input[name="menu_code"], #menu_code').select2({
            data: role,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        var role_detail = jQuery.parseJSON(JSON.stringify(dataJson('{!! route("get.select_workflow_code") !!}')));
        $('input[name="workflow_code"], #workflow_code').select2({
            data: role_detail,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        var role_detail_job_code = jQuery.parseJSON(JSON.stringify(dataJson('{!! route("get.select_workflow_detail_code") !!}')));
        $('input[name="workflow_detail_code"], #workflow_detail_code').select2({
            data: role_detail_job_code,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        var role_detail_job_role = jQuery.parseJSON(JSON.stringify(dataJson('{!! route("get.select_workflow_detail_role") !!}')));
        $('input[name="id_role"], #id_role').select2({
            data: role_detail_job_role,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        jQuery("#icon").on('keyup', function() {
            jQuery("#font-awesome-result").removeClass();
            jQuery("#font-awesome-result").addClass(jQuery(this).val());
        });

        jQuery('.btn-add').on('click', function() 
        {
            document.getElementById("data-form").reset();
            jQuery('#role_id').prop('disabled', false);
            jQuery("#edit_id").val("");
            jQuery("#font-awesome-result").removeClass();
            jQuery("#add-data-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            jQuery("#add-data-modal .modal-title").html("<i class='fa fa-plus'></i> CREATE NEW DATA");
            jQuery("#add-data-modal").modal("show");
        });

        $('.btn-add-detail').on('click', function() 
        {
            document.getElementById("data-form-detail").reset();
            $('#role_id').prop('disabled', false);
            $("#edit_workflow_code_detail").val("");
            $("#font-awesome-result").removeClass();
            $("#add-data-modal-detail").modal({
                backdrop: 'static',
                keyboard: false
            });
            $("#add-data-modal-detail .modal-title").html("<i class='fa fa-plus'></i> Create new data detail");
            $("#add-data-modal-detail").modal("show");
        });

        $('.btn-add-detail-job').on('click', function() 
        {
            document.getElementById("data-form-detail-job").reset();
            $('#role_id').prop('disabled', false);
            $("#edit_workflow_code_detail-job").val("");
            $("#font-awesome-result").removeClass();
            $("#add-data-modal-detail-job").modal({
                backdrop: 'static',
                keyboard: false
            });
            $("#add-data-modal-detail-job .modal-title").html("<i class='fa fa-plus'></i> Create new data detail job");
            $("#add-data-modal-detail-job").modal("show");
        });

        jQuery('.btn-edit').on('click', function() {
            jQuery("#add-data-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            jQuery("#add-data-modal .modal-title").html("<i class='fa fa-pencil'></i> Edit data");
            jQuery("#add-data-modal").modal("show");
        });

        jQuery('#data-form').on('submit', function(e) 
        {
            if(confirm('confirm submit data?'))
            {
                e.preventDefault();
                var param = jQuery(this).serialize();
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    url: "{{ url('asset-class/post') }}",
                    method: "POST",
                    data: param,
                    beforeSend: function() {
                        jQuery('.loading-event').fadeIn();
                    },
                    success: function(result) 
                    {
                        if (result.status) {
                            jQuery("#add-data-modal").modal("hide");
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
        });

        $('.btn-edit-detail').on('click', function() 
        {
            alert("dialog detail");
            $("#add-data-modal-detail").modal({
                backdrop: 'static',
                keyboard: false
            });
            $("#add-data-modal-detail .modal-title").html("<i class='fa fa-pencil'></i> Edit data Detail");
            $("#add-data-modal-detail").modal("show");
        });

        $('#data-form-detail').on('submit', function(e) 
        {
            if(confirm('confirm submit data detail ?'))
            {
                //alert("submit data detail cuk");

                e.preventDefault();
                var param = jQuery(this).serialize();
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    url: "{{ url('workflow/post-detail') }}",
                    method: "POST",
                    data: param,
                    beforeSend: function() {
                        jQuery('.loading-event').fadeIn();
                    },
                    success: function(result) 
                    {
                        if (result.status) {
                            jQuery("#add-data-modal-detail").modal("hide");
                            jQuery("#data-table-group-asset").DataTable().ajax.reload();
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
        });

        $('#data-form-detail-job').on('submit', function(e) 
        {
            if(confirm('confirm submit data detail job ?'))
            {
                //alert("submit data detail cuk");

                e.preventDefault();
                var param = jQuery(this).serialize();
                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                jQuery.ajax({
                    url: "{{ url('workflow/post-detail-job') }}",
                    method: "POST",
                    data: param,
                    beforeSend: function() {
                        jQuery('.loading-event').fadeIn();
                    },
                    success: function(result) 
                    {
                        if (result.status) {
                            jQuery("#add-data-modal-detail-job").modal("hide");
                            jQuery("#data-table-detail-job").DataTable().ajax.reload();
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
        });
    });

    function edit(id) 
    {
        //alert(id);
        document.getElementById("data-form").reset();
        $("#edit_id").val(id);
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('asset-class/edit/?id=') }}" + id)));
        $("#edit_id").val(result.ID);
        $("#jenis_asset_code").val(result.JENIS_ASSET_CODE);
        $("#jenis_asset_description").val(result.JENIS_ASSET_DESCRIPTION);
        //$("#menu_code").val(result.menu_code);
        //$("#menu_code").trigger("change");

        $("#add-data-modal .modal-title").html("<i class='fa fa-edit'></i> Update data " + result.JENIS_ASSET_DESCRIPTION);
        $("#add-data-modal").modal("show");
    }

    function inactive(id) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: "{{ url('modules/inactive') }}",
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
                        type: 'error',
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

    function active(id) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: "{{ url('modules/active') }}",
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
                        type: 'error',
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

    function group_asset(id,name)
    {
        //alert(name);
        $("#data-table-group-asset").DataTable().destroy()

        //alert(id);
        $("#row-group-asset").fadeOut();
        $("#jenis-asset-code").html(id);
        //$("#workflow-code-name").html('('+name+')');
        $("#row-group-asset").fadeIn();

        //if ( ! $.fn.DataTable.isDataTable( '#data-table-detail' ) ) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var grid_group_asset = new Datatable();
            grid_group_asset.init({
                src: $("#data-table-group-asset"),
                onSuccess: function(grid_detail) {},
                onError: function(grid_detail) {},
                onDataLoad: function(grid_detail) {},
                destroy: true,
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
                        url: '{{ url("grid-ac-group-asset/") }}'+'/'+id
                    },
                    columns: [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'group_code',
                            name: 'group_code'
                        },
                        {
                            data: 'group_description',
                            name: 'group_description'
                        },
                        {
                            "render": function(data, type, row) {
                                var update = "{{ $data['access']->update }}";
                                var remove = "{{ $data['access']->delete }}";
                                var content = '';
                                if (update == 1) 
                                {
                                    content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-edit-detail" title="edit data detail : ' + row.id + '" onClick="edit_group_asset(' + row.id + ')"><i class="fa fa-pencil"></i></button>';
                                    content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-view-detail" title="detail data ' + row.group_code + '" onClick="detail_job(' + row.group_code + ')"><i class="fa fa-clone"></i></button>';
                                }
                                
                                /*
                                if (remove == 1) 
                                {
                                    content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-activated  {{ ($data["access"]->delete == 1 ? "":"hide") }}  ' + (row.deleted == 0 ? '' : 'hide') + '" style="margin-left:5px"  onClick="inactive(' + row.id + ')"><i class="fa fa-trash"></i></button>';
                                    content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-inactivated {{ ($data["access"]->delete == 1 ? "":"hide") }}  ' + (row.deleted == 1 ? '' : 'hide') + '" style="margin-left:5px"  onClick="active(' + row.id + ')"><i class="fa fa-check"></i></button>';
                                }
                                */

                                return content;
                            }
                        }
                    ],
                    columnDefs: [
                        /*{
                            targets: [0],
                            className: 'text-center',
                            orderable: false
                        },
                        {
                            targets: [2],
                            className: 'text-center',
                            orderable: false,
                            width: '10%'
                        }*/
                    ],
                    oLanguage: {
                        sProcessing: "<div id='datatable-loader'></div>",
                        sEmptyTable: "Data tidak di temukan",
                        sLoadingRecords: ""
                    },
                    "order": [],
                }
            });
        //}
    }

    function edit_group_asset(id) 
    {
        //alert(id); return false;
        document.getElementById("data-form-detail").reset();
        $("#edit_workflow_code_detail").val(id);
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('asset-class/edit-group-asset/?id=') }}" + id)));
        $("#edit_workflow_code_detail").val(result.workflow_detail_code);
        $("#workflow_group_name").val(result.workflow_group_name);
        $("#workflow_code").val(result.workflow_code);
        $("#workflow_code").trigger("change");
        $("#seq").val(result.seq);
        $("#description").val(result.description);

        $("#add-data-modal-detail .modal-title").html("<i class='fa fa-edit'></i> Update data detail "+result.workflow_detail_code+" : " + result.workflow_group_name);
        $("#add-data-modal-detail").modal("show");
    }

    function detail_job(id)
    {
        //alert(id); return false;
        //alert(name);
        $("#data-table-detail-job").DataTable().destroy()

        //alert(id);
        $("#row-detail-job").fadeOut();
        $("#workflow-detail-code").html(id);
        //$("#workflow-code-name").html('('+name+')');
        $("#row-detail-job").fadeIn();

        //if ( ! $.fn.DataTable.isDataTable( '#data-table-detail' ) ) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var grid_detail = new Datatable();
            grid_detail.init({
                src: $("#data-table-detail-job"),
                onSuccess: function(grid_detail) {},
                onError: function(grid_detail) {},
                onDataLoad: function(grid_detail) {},
                destroy: true,
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
                        url: '{{ url("grid-workflow-detail-job/") }}'+'/'+id
                    },
                    columns: [
                        {
                            data: 'workflow_job_code',
                            name: 'workflow_job_code'
                        },
                        {
                            data: 'workflow_group_name',
                            name: 'workflow_group_name'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'seq',
                            name: 'seq'
                        },
                        {
                            data: 'operation',
                            name: 'operation'
                        },
                        {
                            data: 'lintas',
                            name: 'lintas'
                        },
                        {
                            "render": function(data, type, row) {
                                var update = "{{ $data['access']->update }}";
                                var remove = "{{ $data['access']->delete }}";
                                var content = '';
                                if (update == 1) 
                                {
                                    content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-edit-detail-job" title="edit data detail job : ' + row.workflow_job_code + '" onClick="edit_detail_job(' + row.workflow_job_code + ')"><i class="fa fa-pencil"></i></button>';
                                }

                                return content;
                            }
                        }
                    ],
                    columnDefs: [
                    ],
                    oLanguage: {
                        sProcessing: "<div id='datatable-loader'></div>",
                        sEmptyTable: "Data tidak di temukan",
                        sLoadingRecords: ""
                    },
                    "order": [],
                }
            });
        //}
    }

    function edit_detail_job(id) 
    {
        //alert(id); return false;
        document.getElementById("data-form-detail-job").reset();
        $("#edit_workflow_code_detail_job").val(id);
        
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('workflow/edit-detail-job/?workflow_job_code=') }}" + id)));
        
        $("#edit_workflow_code_detail_job").val(result.workflow_job_code);
        $("#workflow_detail_code").val(result.workflow_detail_code);
        $("#workflow_detail_code").trigger("change");
        $("#id_role").val(result.id_role);
        $("#id_role").trigger("change");
        $("#seq_job").val(result.seq);
        $("#operation").val(result.operation);
        $("#lintas").val(result.lintas);

        $("#add-data-modal-detail-job .modal-title").html("<i class='fa fa-edit'></i> Update data detail job "+result.workflow_job_code);
        $("#add-data-modal-detail-job").modal("show");
    }

</script>
@stop