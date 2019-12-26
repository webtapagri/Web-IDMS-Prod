@extends('layouts.app')

@section('title', 'Road Status list')

@section('theme_js')
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js') }}"></script>
<script src="{{ asset('limitless/global_assets/js/plugins/notifications/sweet_alert.min.js') }}"></script>


@endsection

@section('content')

<div class="card">
<?php print_r(Session::get('job_code')); ?>
	<div class="card-header header-elements-inline">
		@if($data['access']->create == 1)
		<button onclick="sync(this)"
			type="button" class="btn bg-teal-400 btn-labeled btn-labeled-left"><b><i class="icon-sync"></i></b> Sync</button>
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
		
		@if ($errors->any())
			<div class="alert alert-danger no-border">
				Terdapat error:
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
	</div>

	<table class="table datatable-responsive">
		<thead>
			<tr>
				<th>No.</th>
				<th>Company Code</th>
				<th>Company Name</th>
				<th>Region Code</th>
				<th>Address</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Pencarian</th>
				<th>Company Code</th>
				<th>Company Name</th>
				<th>Region Code</th>
				<th>Address</th>
			</tr>
		</tfoot>
	</table>
</div>

@endsection

@section('my_script')
<script>
var table

$(document).ready(()=>{
	
	loadGrid();
	
	$('#reloadGrid').click(()=>{
		table.destroy()
		loadGrid()
		console.log(123)
	})
	
	
});

function sync(dis){
	$.ajax({
		type: 'GET',
		url: "{{ URL::to('api/master/sync-comp') }}/",
		data: null,
		cache:false,
		beforeSend:function(){
			$(dis).html('<b><i class="icon-sync"></i></b> Please wait...')
		},
		complete:function(){
			$(dis).html('<b><i class="icon-sync"></i></b> Sync Now')
		},
		headers: {
			"X-CSRF-TOKEN": "{{ csrf_token() }}"
		}
	}).done(function(rsp){
		
		if(rsp.code=200){
			swal({
                title: 'Success!',
                text: 'Success sync!',
                type: 'success'
            });
			
			table.destroy()
			loadGrid()
		}else{
			alert("Gagal sync");
			console.log(rsp);
		}
	}).fail(function(errors) {
		
		alert("Gagal Terhubung ke Server");
		
	});
}

function loadGrid(){
	$.extend( $.fn.dataTable.defaults, {
				autoWidth: false,
				responsive: true,
				columnDefs: [
					{ 
						orderable: false,
						width: 250,
						targets: [ 3 ]
					},
					{ 
						orderable: false,
						targets: [ 0 ]
					},
					{
						searchable: false,
						targets: [ 0 ]
					},
				],
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
        ajax: '{{ route("master.company_datatables") }}',
		"order": [[1,"asc"],[2, "asc" ]],
        columns: [
            { data: 'no', 	name: 'no' },
            { data: 'company_code', 	name: 'company_code' },
            { data: 'company_name', 	name: 'company_name' },
            { data: 'region_code', 	name: 'region_code' },
            { data: 'address', 		name: 'address' },
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
}
</script>
@endsection