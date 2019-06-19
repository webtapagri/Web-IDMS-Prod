@extends('adminlte::page')
@section('title', 'FAMS - Workflow')
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
                                <th>NAME</th>
                                <th>MENU CODE</th>
                                <th width="8%">ACTION</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="workflow_name" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="menu_code" autocomplete="off"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            
            <div id="row-detail" style="margin-top:10px;display:none">
                <div class="callout callout-info">
                    <h4>DETAIL WORKFLOW</h4>
                    <p>WORKFLOW CODE : <span id="workflow-code-detail"></p>
                </div>
                <div class="table-container">
                    <div class="table-actions-wrapper">
                        <span></span>
                        <button class="btn btn-sm btn-flat btn-danger btn-refresh-data-table" title="refresh"><i class="glyphicon glyphicon-refresh"></i></button>
                        @if($data['access']->create == 1)
                        <button class="btn btn-sm btn-flat btn-danger btn-add"><i class="glyphicon glyphicon-plus" title="Add new data detail"></i></button>
                        @endif
                    </div>
                    <table id="data-table-detail" class="table table-condensed" width="100%">
                        <thead>
                            <tr role="row" class="heading">
                                <th>ID</th>
                                <th>WORKFLOW CODE</th>
                                <th>GROUP NAME</th>
                                <th>SEQUENCE</th>
                                <th>DESCRIPTION</th>
                                <th width="8%">ACTION</th>
                            </tr>
                            <tr role="row" class="filter">
                                <th></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="workflow_code" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="workflow_group_name" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="seq" autocomplete="off"></th>
                                <th><input type="text" class="form-control input-xs form-filter" name="description" autocomplete="off"></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div id="row-detail-job" style="margin-top:10px;display:none">
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
                            <label class="control-label" for="workflow-name">Workflow Name</label>
                            <input class="form-control" name='workflow_name' id="workflow_name" maxlength="400" requried>
                            <input type="hidden" name='edit_workflow_code' id="edit_workflow_code">
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="menu-code">Menu Code</label>
                            <input class="form-control" name='menu_code' id="menu_code" requried>
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
        $("#row-detail").hide();

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
                    url: "{!! route('get.grid_workflow') !!}"
                },
                columns: [
                    {
                        data: 'workflow_code',
                        name: 'workflow_code'
                    },
                    {
                        data: 'workflow_name',
                        name: 'workflow_name'
                    },
                    {
                        data: 'menu_code',
                        name: 'menu_code'
                    },
                    {
                        "render": function(data, type, row) {
                            var update = "{{ $data['access']->update }}";
                            var remove = "{{ $data['access']->delete }}";
                            var content = '';
                            if (update == 1) 
                            {
                                content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-edit" title="edit data ' + row.workflow_code + '" onClick="edit(' + row.workflow_code + ')"><i class="fa fa-pencil"></i></button>';
                                content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-view" title="detail data ' + row.workflow_code + '" onClick="detail(' + row.workflow_code + ')"><i class="fa fa-clone"></i></button>';
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

        jQuery("#icon").on('keyup', function() {
            jQuery("#font-awesome-result").removeClass();
            jQuery("#font-awesome-result").addClass(jQuery(this).val());
        });

        jQuery('.btn-add').on('click', function() 
        {
            document.getElementById("data-form").reset();
            jQuery('#role_id').prop('disabled', false);
            jQuery("#edit_workflow_code").val("");
            jQuery("#font-awesome-result").removeClass();
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
                    url: "{{ url('workflow/post') }}",
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
        })
    });

    function edit(id) 
    {
        //alert(id);
        document.getElementById("data-form").reset();
        jQuery("#edit_workflow_code").val(id);
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('workflow/edit/?workflow_code=') }}" + id)));
        jQuery("#edit_workflow_code").val(result.workflow_code);
        jQuery("#workflow_name").val(result.workflow_name);
        $("#menu_code").val(result.menu_code);
        $("#menu_code").trigger("change");

        jQuery("#add-data-modal .modal-title").html("<i class='fa fa-edit'></i> Update data " + result.workflow_name);
        jQuery("#add-data-modal").modal("show");
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

    function detail(id)
    {
        $("#data-table-detail").DataTable().destroy()

        //alert(id);
        $("#row-detail").fadeOut();
        $("#workflow-code-detail").html(id);
        $("#row-detail").fadeIn();

        if ( ! $.fn.DataTable.isDataTable( '#data-table-detail' ) ) 
        {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var grid_detail = new Datatable();
            grid_detail.init({
                src: $("#data-table-detail"),
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
                        url: '{{ url("grid-workflow-detail/") }}'+'/'+id
                    },
                    columns: [
                        {
                            data: 'workflow_detail_code',
                            name: 'workflow_detail_code'
                        },
                        {
                            data: 'workflow_code',
                            name: 'workflow_code'
                        },
                        {
                            data: 'workflow_group_name',
                            name: 'workflow_group_name'
                        },
                        {
                            data: 'seq',
                            name: 'seq'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            "render": function(data, type, row) {
                                var update = "{{ $data['access']->update }}";
                                var remove = "{{ $data['access']->delete }}";
                                var content = '';
                                if (update == 1) 
                                {
                                    content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-edit" title="edit data ' + row.workflow_code + '" onClick="edit_detail(' + row.workflow_detail_code + ')"><i class="fa fa-pencil"></i></button>';
                                    content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-view" title="detail data ' + row.workflow_code + '" onClick="detail_job(' + row.workflow_detail_code + ')"><i class="fa fa-clone"></i></button>';
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
        }
       
    }

    function edit_detail(id) 
    {
        alert(id); return false;
        document.getElementById("data-form").reset();
        jQuery("#edit_workflow_code").val(id);
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('workflow/edit/?workflow_code=') }}" + id)));
        jQuery("#edit_workflow_code").val(result.workflow_code);
        jQuery("#workflow_name").val(result.workflow_name);
        $("#menu_code").val(result.menu_code);
        $("#menu_code").trigger("change");

        jQuery("#add-data-modal .modal-title").html("<i class='fa fa-edit'></i> Update data " + result.workflow_name);
        jQuery("#add-data-modal").modal("show");
    }

    function detail_job(id)
    {
        alert(id); return false;
    }

</script>
@stop