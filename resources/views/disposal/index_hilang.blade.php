@extends('adminlte::page')
@section('title', 'FAMS - Disposal')

@section('content_header')
<h1>Create Disposal - Hilang</h1><br/>
<input class="form-control" id="fnama-material" name="fnama-material" type="text" name="s" autocomplete="on" placeholder="Cari Asset">

@stop

@section('content')

<form action="{{ url(('/checkout')) }}" method="post" id="form-cart">

	{!! csrf_field() !!}
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<?php 

				//echo "5<pre>"; print_r($now_data); //die(); 
				/*
				[autocomplete] => {id : '40100194',
					name : 'SEPEDA MOTOR 150CC VERZA HONDA  ',
					asset : 'SEPEDA MOTOR 150CC VERZA HONDA '
				},{id : '40100195',
					name : 'SEPEDA MOTOR 150CC VERZA HONDA  ',
					asset : 'SEPEDA MOTOR 150CC VERZA HONDA '
				}*/

				//echo Session::get('data');
				$now_data = array();
				$new_data = Session::get('data');

				//$kode_asset_ams = @$new_data->kode_asset_ams;
				//echo "1<pre>"; print_r($new_data); 

				$now_data[] = Session::put($new_data);

				//array_push($now_data, $new_data);
				//echo "13<pre>"; print_r($now_data); 


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
					
					$l = '<table class="table no-margin" id="table-cart">
							  <thead>
							  <tr>
								<th>KODE ASSET AMS</th>
								<th nowrap="nowrap">KODE ASSET SAP</th>
								<th>NAMA MATERIAL</th>
								<th>BA PEMILIK ASSET</th>
								<th nowrap="nowrap">LOKASI ASSET</th>
								<th nowrap="nowrap">NAMA ASSET</th>
								<th>DELETE</th>
							  </tr>
							  </thead>
							  <tbody>';

					if(!empty($data['data']))
					{
						foreach($data['data'] as $k => $v)
						{
							
							$l .= "
								<tr class='MyClass'>
									<td>{$v->KODE_ASSET_AMS}</td>
									<td>{$v->KODE_ASSET_SAP}</td>
									<td>{$v->NAMA_MATERIAL}</td>
									<td>{$v->BA_PEMILIK_ASSET}</td>
									<td>{$v->LOKASI_BA_DESCRIPTION}</td>
									<td>{$v->NAMA_ASSET_1}</td>
									<td nowrap='nowrap'>
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

</script>
@stop