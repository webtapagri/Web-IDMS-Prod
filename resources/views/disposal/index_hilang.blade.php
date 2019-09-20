@extends('adminlte::page')
@section('title', 'FAMS - Disposal')

@section('content_header')
<h1>Create Disposal - Hilang</h1><br/>
<input class="form-control" id="fnama-material" name="fnama-material" type="text" name="s" autocomplete="on" placeholder="Cari Asset">

@stop

@section('content')

<form action="{{ url(('/proses_disposal/2')) }}" method="post" id="form-disposal-hilang" onsubmit="return validate(this);">

	{!! csrf_field() !!}
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<?php 
		$now_data = array();
		$new_data = Session::get('data');
		$now_data[] = Session::put($new_data);
	?>

	<div class="box box-default">

		@if(Session::has('alert'))
			@section('js')
			<script type="text/javascript">
				//alert("okesip");
				notify({
		            type: 'warning',
		            message: "{{ Session::get('alert') }}"
		        });

				var jenis_pengajuan = $("#fjenis-pengajuan").val();
				var data_autocomplete = [<?php echo $data['autocomplete']; ?>];
				//alert(data_autocomplete);

				$("#fnama-material").autocomplete({
				    minLength: 0,
				    source: function (request, response) {
				        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
				        var array = $.grep(data_autocomplete, function (value) {
				            return matcher.test(value.id) || matcher.test(value.name) || matcher.test(value.asset);
				        });
				        response(array);
				    },
				    focus: function (event, ui) {
				        $("#fnama-material").val(ui.item.name);
				        return false;
				    },
				    select: function (event, ui) 
				    {
				    	//alert(ui.item.id);
				        $("#fnama-material").val(ui.item.name);
				        $("#project-id").val(ui.item.id);
				        $("#project-description").html(ui.item.asset);

				        return false;
				    }
				}).data("ui-autocomplete")._renderItem = function (ul, item) 
				{
					var jenis_pengajuan = $("#fjenis-pengajuan").val();
					//alert(jenis_pengajuan); return false;

				    return $("<li>")
				        .append("<a href='disposal-hilang/add_hilang/"+item.id+"/2'>" + item.name + "<span class='sub-text' style='margin-left:15px;font-size:15px;font-weight:normal;color:red'>" + item.asset + " <i class='fa fa-plus'></i> Add </span></a> ")
				        .appendTo(ul);
				};

				$('#table-disposal-hilang').on('click', 'a', function (e) 
				{
					var idcontent = $( this ).attr("idcontent");//alert(idcontent);return false;
					var nama_asset = $( this ).attr("namaasset");
					var harga_perolehan = $( this ).attr("hargaperolehan");

					$("#form-detil #kode_asset_ams").val(idcontent);
					$("#form-detil #nama_asset").val(nama_asset);
					$("#form-detil #harga_perolehan").val(harga_perolehan);
				});
				
			</script>
			@stop
		@endif
		
		@if(Session::has('message'))
			<!--div class='box box-alert' id="box-alert">
				<p class="alert {{ Session::get('alert-class', 'alert-success') }}"> <i class="fa fa-success"></i> {{ Session::get('message') }}</p>
			</div-->
			@section('js')
			<script type="text/javascript">
				//alert("okesip");
				notify({
			            type: 'success',
			            message: "{{ Session::get('message') }}"
			        });

				var jenis_pengajuan = $("#fjenis-pengajuan").val();
				var data_autocomplete = [<?php echo $data['autocomplete']; ?>];
				//alert(data_autocomplete);

				$("#fnama-material").autocomplete({
				    minLength: 0,
				    source: function (request, response) {
				        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
				        var array = $.grep(data_autocomplete, function (value) {
				            return matcher.test(value.id) || matcher.test(value.name) || matcher.test(value.asset);
				        });
				        response(array);
				    },
				    focus: function (event, ui) {
				        $("#fnama-material").val(ui.item.name);
				        return false;
				    },
				    select: function (event, ui) 
				    {
				    	//alert(ui.item.id);
				        $("#fnama-material").val(ui.item.name);
				        $("#project-id").val(ui.item.id);
				        $("#project-description").html(ui.item.asset);

				        return false;
				    }
				}).data("ui-autocomplete")._renderItem = function (ul, item) 
				{
					var jenis_pengajuan = $("#fjenis-pengajuan").val();
					//alert(jenis_pengajuan); return false;

				    return $("<li>")
				        .append("<a href='disposal-hilang/add_hilang/"+item.id+"/2'>" + item.name + "<span class='sub-text' style='margin-left:15px;font-size:15px;font-weight:normal;color:red'>" + item.asset + " <i class='fa fa-plus'></i> Add </span></a> ")
				        .appendTo(ul);
				};

				$('#table-disposal-hilang').on('click', 'a', function (e) 
				{
					var idcontent = $( this ).attr("idcontent");//alert(idcontent);return false;
					var nama_asset = $( this ).attr("namaasset");
					var harga_perolehan = $( this ).attr("hargaperolehan");

					$("#form-detil #kode_asset_ams").val(idcontent);
					$("#form-detil #nama_asset").val(nama_asset);
					$("#form-detil #harga_perolehan").val(harga_perolehan);
				});

			</script>
			@stop 

		@endif

		<div class="box-header with-border">
		  	<h3 class="box-title">Latest Disposal</h3>
		</div>

		

		<div class="box-body">

			<div class="table-responsive">
				<?php 
					//echo "<pre>"; print_r($data); die(); 
					$no = 1;
					$all_total = 0;
					
					$l = '<table class="table no-margin" id="table-disposal-hilang">
							  <thead>
							  <tr>
								<th>KODE ASSET AMS</th>
								<th nowrap="nowrap">KODE ASSET SAP</th>
								<th>NAMA MATERIAL</th>
								<th>BA PEMILIK ASSET</th>
								<th nowrap="nowrap">LOKASI ASSET</th>
								<th nowrap="nowrap">NAMA ASSET</th>
								<th>HARGA PEROLEHAN</th>
								<th>ACTION</th>
							  </tr>
							  </thead>
							  <tbody>';

					if(!empty($data['data']))
					{
						foreach($data['data'] as $k => $v)
						{
							$HARGA_PEROLEHAN = $v->HARGA_PEROLEHAN;
							$hp = number_format($HARGA_PEROLEHAN,0,',','.');

							$l .= "
								<tr class='MyClass'>
									<td>{$v->KODE_ASSET_AMS}</td>
									<td>{$v->KODE_ASSET_SAP}</td>
									<td>{$v->NAMA_MATERIAL}</td>
									<td>{$v->BA_PEMILIK_ASSET}</td>
									<td>{$v->LOKASI_BA_DESCRIPTION}</td>
									<td>{$v->NAMA_ASSET_1}</td>
									<td>{$hp}</td>
									<td nowrap='nowrap'>
									
										<a href='#' id='edit-data' idcontent='{$v->KODE_ASSET_AMS}' namaasset='{$v->NAMA_ASSET_1}' hargaperolehan='{$v->HARGA_PEROLEHAN}' class='btn btn-icon-toggle' title='Edit Data' data-toggle='modal' data-target='#mymodal_detil_edit'><i class='fa fa-edit'></i></a>

										<a href='".url('/disposal-hilang/delete_hilang/'.$v->KODE_ASSET_AMS.'')."' id='delete-data' idcontent='{$v->KODE_ASSET_SAP}' class='btn btn-icon-toggle' title='Delete Data' data-toggle='modal'>
											<i class='fa fa-trash'></i></a>
								</td>
								</tr>";
							$no++;
						}
					}
					else
					{
						$l .= '<tr><td colspan="8" align="center"><h5><span class="xlabel xlabel-danger">Belum ada data</span></h5></td></tr>';
					}

					$l .= '	</tbody>
							</table>';
					
					echo $l; 	
			?>
			</div>
		</div>

		<div class="box-footer clearfix">
			<button type="submit" class="btn btn-danger pull-right"><i class="fa fa-plus"></i> PROSES</button>
		</div>

	</div>

</form>

<div class="modal fade" id="mymodal_detil_edit" xtabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		        <h3 class="modal-title" id="myModalLabel">Edit Harga Perolehan</h3>
		    </div>
		    
		    <form id="form-detil" name="form-detil" class="form-horizontal" method="POST" action="{{ url('/disposal/edit_harga') }}" enctype="multipart/form-data">

		    	{!! csrf_field() !!}
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" class="form-control" id="tipe" name="tipe" value="2"/>
		        
		        <div class="modal-body">

		        	<div class="form-group">
		                <label class="control-label col-xs-4" >KODE ASSET AMS</label>
		                <div class="col-xs-8">
		                    <input type="text" class="form-control" id="kode_asset_ams" name="kode_asset_ams" value="" readonly="readonly" />
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="control-label col-xs-4" >NAMA ASSET</label>
		                <div class="col-xs-8">
		                    <input type="text" class="form-control" id="nama_asset" name="nama_asset" value="" readonly="readonly" />
		                </div>
		            </div>

		            <div class="form-group">
		                <label class="control-label col-xs-4" >HARGA PEROLEHAN</label>
		                <div class="col-xs-8">
		                    <input type="text" class="form-control" id="harga_perolehan" name="harga_perolehan" value="" placeholder="Masukkan Harga Perolehan"/>
		                </div>
		            </div>

		        </div>
		        	
		        <div class="modal-footer">
					<button class="btn btn-flat btn-lg btn-info" data-dismiss="modal" aria-hidden="true">Cancel</button>
					<input type="submit" class="btn btn-flat btn-lg btn-danger" id="" value="Update">
		        </div>
		    </form>

		</div>	
	</div>
</div>

<div class="row">
</div>

@stop
@section('js')
<script type="text/javascript">
var jenis_pengajuan = $("#fjenis-pengajuan").val();
var data_autocomplete = [<?php echo $data['autocomplete']; ?>];
//alert(data_autocomplete);

$("#fnama-material").autocomplete({
    minLength: 0,
    source: function (request, response) {
        var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
        var array = $.grep(data_autocomplete, function (value) {
            return matcher.test(value.id) || matcher.test(value.name) || matcher.test(value.asset);
        });
        response(array);
    },
    focus: function (event, ui) 
    {
    	//alert(1);
        $("#fnama-material").val(ui.item.name);
        return false;
    },
    select: function (event, ui) 
    {
        $("#fnama-material").val(ui.item.name);
        $("#project-id").val(ui.item.id);
        $("#project-description").html(ui.item.asset);

        return false;
    }
}).data("ui-autocomplete")._renderItem = function (ul, item) 
{
	var jenis_pengajuan = $("#fjenis-pengajuan").val();
	
	return $("<li>")
    .append("<a href='disposal-hilang/add_hilang/"+item.id+"/2'>" + item.name + "<span class='sub-text' style='margin-left:15px;font-size:15px;font-weight:normal;color:red'>" + item.asset + " <i class='fa fa-plus'></i> Add </span></a> ")
    .appendTo(ul);
    
};

function validate(form) 
{
    var valid = true;

    if(!valid) {
        alert('Please correct the errors in the form!');
        return false;
    }
    else {
        return confirm('Confirm proses disposal ?');
    }
}

$('#table-disposal-hilang').on('click', 'a', function (e) 
{
	var idcontent = $( this ).attr("idcontent");//alert(idcontent);return false;
	var nama_asset = $( this ).attr("namaasset");
	var harga_perolehan = $( this ).attr("hargaperolehan");

	$("#form-detil #kode_asset_ams").val(idcontent);
	$("#form-detil #nama_asset").val(nama_asset);
	$("#form-detil #harga_perolehan").val(harga_perolehan);
});

</script>
@stop