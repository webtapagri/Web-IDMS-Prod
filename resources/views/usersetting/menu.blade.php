@extends('adminlte::page')
@section('title', 'FAMS - menu')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-xs-4">
            <span style="font-size:24px">Menu</span>
        </div>
        <div class="col-xs-8" align="right">
            <span href="#" class="btn btn-sm btn-flat btn-danger btn-add {{ (isset($access['CREATE']) ? '':'') }}">&nbsp;<i class="glyphicon glyphicon-plus" title="Add new data"></i>&nbsp; Add</span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="data-table" class="table table-bordered table-hover table-condensed" width="100%">
                        <thead>
                            <tr>
                                <th width="8%">Sort</th>
                                <th width="15%">Module</th>
                                <th>Name</th>
                                <th>Url</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
                            <label class="control-label" for="module">Module</label>
                            <input class="form-control" name='module' id="module" requried>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="name">Nama</label>
                            <input class="form-control" name='name' id="name" maxlength="200" requried>
                            <input type="hidden" name='edit_id' id="edit_id">
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="url">Url</label>
                            <input class="form-control" name='url' id="url" placeholder="put the url from routes (e.g: menu for {{ url('/') }}/menu)" maxlength="200" requried>
                        </div>
                        <div class="col-xs-4">
                            <label class="control-label" for="sorting">Sorting</label>
                            <input class="form-control" name='sorting' id="sorting" maxlength="4" onkeypress="return isNumber(event)" onpaste="return false" ondrop="return false">
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
    jQuery(document).ready(function() {
        jQuery('#data-table').DataTable({
            ajax: "{!! route('get.grid_menu') !!}",
            columns: [{
                    data: 'sort',
                    name: 'sort'
                },
                {
                    data: 'module_name',
                    name: 'module_name'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'url',
                    name: 'url'
                },
                {
                    "render": function(data, type, row) {
                        var content = '<button class="btn btn-flat btn-xs btn-danger btn-action btn-edit {{ (isset($access["CREATE"]) ? "":"") }}" title="edit data ' + row.id + '" onClick="edit(' + row.id + ')"><i class="fa fa-pencil"></i></button>';
                        content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-activated  {{ (isset($access["CREATE"]) ? "":"") }}  ' + (row.deleted == 0 ? '' : 'hide') + '" style="margin-left:5px"  onClick="inactive(' + row.id + ')"><i class="fa fa-trash"></i></button>';
                        content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-inactivated {{ (isset($access["CREATE"]) ? "":"") }} ' + (row.deleted == 1 ? '' : 'hide') + '" style="margin-left:5px"  onClick="active(' + row.id + ')"><i class="fa fa-check"></i></button>';

                        return content;
                    }
                }
            ],
            columnDefs: [{
                targets: [4],
                className: 'text-center',
                orderable: false
            }, ]
        });

        var role = jQuery.parseJSON(JSON.stringify(dataJson('{!! route("get.select_module") !!}')));
        jQuery('#module').select2({
            data: role,
            width: '100%',
            placeholder: ' ',
            allowClear: true
        });

        jQuery('.btn-add').on('click', function() {
            jQuery("#module").select2("val", "");
            jQuery("#module").val("");
            jQuery("#module").trigger("change");

            document.getElementById("data-form").reset();
            jQuery("#edit_id").val("");
            jQuery("#add-data-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            jQuery("#add-data-modal .modal-title").html("<i class='fa fa-plus'></i> Create new data");
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
                url: "{{ url('menu/post') }}",
                method: "POST",
                data: param,
                beforeSend: function() {
                    jQuery('.loading-event').fadeIn();
                },
                success: function(result) {
                    if (result.status) {
                        jQuery("#add-data-modal").modal("hide");
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
        })
    });

    function edit(id) {
        document.getElementById("data-form").reset();
        jQuery("#edit_id").val(id);
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('menu/edit/?id=') }}" + id)));

        jQuery("#name").val(result.name);
        jQuery("#url").val(result.url);
        jQuery("#sorting").val(result.sort);
        jQuery("#module").val(result.module_id);
        jQuery("#module").trigger("change");
        jQuery("#add-data-modal .modal-title").html("<i class='fa fa-edit'></i> Update data " + result.name);
        jQuery("#add-data-modal").modal("show");
    }

    function inactive(id) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: "{{ url('menu/inactive') }}",
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
            url: "{{ url('menu/active') }}",
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

    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>
@stop