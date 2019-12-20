@extends('layouts.app')

@section('title', 'Road Category List')

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/bootbox.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/select2.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/forms/styling/switchery.min.js') }}"></script>

@endsection

@section('content')

<div class="card">
	<div class="card-header header-elements-inline">
		@if($access['create']=='1')
		<button 
			data-toggle="modal" data-target="#modal_add"
			type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-plus3"></i></b> 
			Tambah
		</button>
		@endif
		<div class="header-elements">
			<div class="list-icons">
				<a class="list-icons-item" id="reloadGrid" data-action="reload"></a>
			</div>
		</div>
	</div>

	<div class="card-body">
		@if (\Session::has('success'))
			<div class="alert alert-success no-border">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Berhasil!</span> {{ \Session::get('success') }}
			</div>
		@endif
		
		@if (\Session::has('error'))
			<div class="alert alert-warning no-border">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Error!</span> {{ \Session::get('error') }}
			</div>
		@endif
		
		@if ($errors->has('status_name'))
			<div class="alert alert-warning no-border">
				<button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span></button>
				<span class="text-semibold">Error!</span> {{ $errors->first('status_name') }}
			</div>
		@endif
	</div>
	<table class="table datatable-responsive">
		<thead>
			<tr>
				<th>No</th>
				<th>Road Status</th>
				<th>Road Category</th>
				<th>Kode Road Category</th>
				<th>Initial Road Category</th>
				<th class="text-center">Aksi</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Pencarian</th>
				<th>Pencarian</th>
				<th>Road Category</th>
				<th>Initial Road Category</th>
				<th>Kode Road Category</th>
				<th class="text-center"></th>
			</tr>
		</tfoot>
	</table>
</div>

<div id="modal_add" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Road Category</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('master.road_category_save') }}" method="post" class="form-horizontal">
				@csrf
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Status</label>
						<div class="col-sm-9">
							<select name="status_id" id="rc_status_id" class="form-control">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Category Name</label>
						<div class="col-sm-9">
							<input type="text" name="category_name" maxlength="255" placeholder="Road Category Name" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Category Code</label>
						<div class="col-sm-9">
							<input type="number" name="category_code" maxlength="255" placeholder="Road Category Code" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Category Initial</label>
						<div class="col-sm-9">
							<input type="text" name="category_initial" maxlength="255" placeholder="Road Category Initial" class="form-control">
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn bg-primary">SImpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_edit" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Road Category</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<form action="{{ route('master.road_category_update') }}" method="post" class="form-horizontal">
				@csrf
				<input type="hidden" name="id" id="rc_id">
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Status</label>
						<div class="col-sm-9">
							<select name="status_id" id="rc_status_id_edit" class="form-control">
								<option value=""></option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Category Name</label>
						<div class="col-sm-9">
							<input type="text" name="category_name" id="rc_category_name" maxlength="255" placeholder="Road Category Name" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Category Code</label>
						<div class="col-sm-9">
							<input type="number" name="category_code" id="rc_category_code" maxlength="255" placeholder="Road Category Code" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Road Category Initial</label>
						<div class="col-sm-9">
							<input type="text" name="category_initial" id="rc_category_initial" maxlength="255" placeholder="Road Category Initial" class="form-control">
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-link" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn bg-primary">SImpan</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('my_script')
<script>
var table

$(document).ready(()=>{
	
	loadGrid();
	loadStatus()
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
		console.log(123)
	})
	
	
});

function edit(id, cn, cc, ci,si){
	console.log(si)
	$('#rc_id').val(id)
	$('#comboid_'+si).attr('selected','selected')
	$('#rc_category_name').val(cn)
	$('#rc_category_code').val(cc)
	$('#rc_category_initial').val(ci)
	$('#modal_edit').modal('show')
	return false;
}

function del(url){
	swal({
		title: 'Anda yakin ingin menghapus data?',
		text: "",
		type: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Hapus',
		cancelButtonText: 'Batal',
		confirmButtonClass: 'btn btn-success',
		cancelButtonClass: 'btn btn-danger',
		buttonsStyling: false
	}).then(function (is) {
		if(is.value){
			swal(
				'Terhapus!',
				'Data telah dihapus',
				'success'
			);
			setTimeout(function(){
				window.location.href = url;
			}, 1000);
		}else{
			
		}
	});
}
function loadStatus(){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/road-status') }}/",
		data: null,
		cache:false,
		beforeSend:function(){
			// HoldOn(light);
		},
		complete:function(){
			// HoldOff(light);
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			var cont = rsp.contents;
			var htm = '<option value="">-- Pilih Status --</option>'
			var htm2 = '<option value="">-- Pilih Status --</option>'
			$.each(cont, (k,v)=>{
				htm += '<option value="'+v.id+'" >'+v.status_name+'</option>'
				htm2 += '<option value="'+v.id+'" id="comboid_'+v.id+'">'+v.status_name+'</option>'
			});
			$('#rc_status_id').html(htm);
			$('#rc_status_id_edit').html(htm2);
		}else{
			$('#rc_status_id').html('<option value="">Gagal mengambil data</option>');	
			$('#rc_status_id_edit').html('<option value="">Gagal mengambil data</option>');	
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}

function loadGrid(){
	console.log('load grid')
	$.extend( $.fn.dataTable.defaults, {
				autoWidth: false,
				responsive: true,
				columnDefs: [{ 
					orderable: false,
					width: 100,
					// targets: [ 5 ]
				}],
				dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
				language: {
					search: '<span>Filter:</span> _INPUT_',
					searchPlaceholder: 'Type to filter...',
					lengthMenu: '<span>Show:</span> _MENU_',
					paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
				}
			});
	

	table = $('.datatable-responsive').DataTable( {
        processing: true,
        serverSide: true,
        ajax: '{{ route("master.road_category_datatables") }}',
		"order": [[0,"asc"],[2, "asc" ]],
        columns: [
            { data: 'status_name', 	name: 'status_name' },
            { data: 'status_name', 	name: 'status_name' },
            { data: 'category_name', 	name: 'category_name' },
            { data: 'category_code', 	name: 'category_code' },
            { data: 'category_initial', 	name: 'category_initial' },
            { data: 'action', 		name: 'action' },
        ],
		initComplete: function () {
			this.api().columns().every(function (k) {
				if(k > 0 && k < 5){
					var column = this;
					var input = document.createElement("input");
					$(input).appendTo($(column.footer()).empty())
					.on('change', function () {
						column.search($(this).val(), false, false, true).draw();
					}).attr('placeholder',' Cari').addClass('form-control');
				}
			});
		}
    } );
	
	table.on( 'order.dt search.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
}
</script>
@endsection