<?php 
	//echo "2<pre>"; print_r($data['content']);die();
  $qrcode = url('master-asset/edit-data/'.base64_encode($data['id']).'');
  //echo  "3<br/>".$qrcode; die();
?>

@extends('adminlte::page')
@section('title', 'Edit Data - Master Asset')
@section('content')

<style>
.show_qrcode{cursor:pointer;}
</style>

<div class="row">
<section class="content-header" style="margin-top:-3%">
	<h1>
		Master Asset
		<small>Preview</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Master Data</a></li>
		<li class="active">Master Asset</li>
	</ol>
</section>

<section class="content">

<form class="form-horizontal">

<div class="box box-default">

    <div class="box-header with-border">
      <h3 class="box-title"><span class="direct-chat-text" style="margin-left:0%">KODE ASSET AMS : <b>{{ $data['id'] }}</b></span></h3>
      <span class="xpull-right badge bg-green show_qrcode" OnClick="show_qrcode('{{ $data['id'] }}')"><i class="fa fa-fw fa-barcode"></i> SHOW QR CODE</span>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button-->
      </div>
    </div><!-- /.box-header -->
    
    <div class="box-body">

      <div class="row">

        <div class="col-md-6">

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Document Code</label>

              <div class="col-sm-8">
                <input type="text" class="form-control" id="no_reg" placeholder="Document Code" value="{{@$data['content']->NO_REG}}" readonly="1">
              </div>
            </div>

            <!--div class="form-group">
              <label for="" class="col-sm-3 control-label">No Reg Item</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="no_reg_item" placeholder="No Reg Item" value="{{@$data['content']->NO_REG_ITEM}}" readonly="1">
              </div>
            </div-->

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Kode Material</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="kode_material" placeholder="Kode Material" value="{{@$data['content']->KODE_MATERIAL}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Lokasi BA Code</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="lokasi_ba_code" placeholder="Lokasi BA Code" value="{{@$data['content']->LOKASI_BA_CODE}}" readonly="1">
              </div>
            </div>

        </div>
        <!-- /.col -->
        
        <div class="col-md-6">

        	<div class="form-group">
              <label for="" class="col-sm-4 control-label">No PO</label>

              <div class="col-sm-8">
                <input type="text" class="form-control" id="no_po" placeholder="No PO" value="{{@$data['content']->NO_PO}}" readonly="1">
              </div>
            </div>
        	
        	<!--div class="form-group">
              <label for="" class="col-sm-3 control-label">Item PO</label>

              <div class="col-sm-9">
                <input type="text" class="form-control" id="item_po" placeholder="Item PO" value="{{@$data['content']->ITEM_PO}}" readonly="1">
              </div>
            </div-->

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Nama Material</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nama_material" placeholder="Nama Material" value="{{@$data['content']->NAMA_MATERIAL}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Lokasi BA Description</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="lokasi_ba_description" placeholder="Lokasi BA Description" value="{{@$data['content']->LOKASI_BA_DESCRIPTION}}" readonly="1">
              </div>
            </div>
        
        </div>
        <!-- /.col -->



      </div>
      <!-- /.row -->
    </div><!-- /.box-body -->
</div><!-- /.box default -->

<div class="box box-danger">
	<div class="box-header with-border">
      <h3 class="box-title"><span class="direct-chat-text" style="margin-left:0%"><b>RINCIAN INFORMASI ASSET</b></span></h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button-->
      </div>
    </div><!-- /.box-header -->

    <div class="box-body">

      <div class="row">
        <div class="col-md-6">

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Jenis Asset</label>

              <div class="col-sm-8">
                <input type="text" class="form-control" id="jenis_asset" placeholder="Jenis Asset" value="{{@$data['content']->JENIS_ASSET}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Group</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="group" placeholder="Group" value="{{@$data['content']->GROUP}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Asset Class</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="asset_class" placeholder="Asset Class" value="{{@$data['content']->ASSET_CLASS}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Merk</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="merk" placeholder="Merk" value="{{@$data['content']->MERK}}" readonly="1">
              </div>
            </div>

             <div class="form-group">
              <label for="" class="col-sm-4 control-label">No Mesin / IMEI</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="no_mesin_or_imei" placeholder="No Mesin / Imei" value="{{@$data['content']->NO_MESIN_OR_IMEI}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">No Polisi</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="no_polisi" placeholder="No Polisi" value="{{@$data['content']->NO_POLISI}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Kondisi</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="kondisi_asset" placeholder="Kondisi Asset" value="{{@$data['content']->KONDISI_ASSET}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Penanggung Jawab</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nama_penanggung_jawab_asset" placeholder="Nama Penanggung Jawab Asset" value="{{@$data['content']->NAMA_PENANGGUNG_JAWAB_ASSET}}" readonly="1">
              </div>
            </div>

        </div>
        <!-- /.col -->
        
        <div class="col-md-6">

        	<div class="form-group">
              <label for="" class="col-sm-4 control-label">BA Pemilik Asset</label>

              <div class="col-sm-8">
                <input type="text" class="form-control" id="ba_pemilik_asset" placeholder="BA Pemilik Asset" value="{{@$data['content']->BA_PEMILIK_ASSET}}" readonly="1">
              </div>
            </div>
        	
        	<div class="form-group">
              <label for="" class="col-sm-4 control-label">Sub Group</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="sub_group" placeholder="Sub Group" value="{{@$data['content']->SUB_GROUP}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Nama Asset</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nama_asset" placeholder="Nama Asset" value="{{@$data['content']->NAMA_ASSET}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Spesifikasi / Warna</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="spesifikasi_or_warna" placeholder="Spesifikasi / Warna" value="{{@$data['content']->SPESIFIKASI_OR_WARNA}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">No Rangka / No Seri</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="no_rangka_or_no_seri" placeholder="No Rangka / No Seri" value="{{@$data['content']->NO_RANGKA_OR_NO_SERI}}" readonly="1">
              </div>
            </div>

             <div class="form-group">
              <label for="" class="col-sm-4 control-label">Tahun Asset</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="tahun_asset" placeholder="Tahun Asset" value="{{@$data['content']->TAHUN_ASSET}}" readonly="1">
              </div>
            </div>

             <div class="form-group">
              <label for="" class="col-sm-4 control-label">Informasi</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="informasi" placeholder="Informasi" value="{{@$data['content']->INFORMASI}}" readonly="1">
              </div>
            </div>

             <div class="form-group">
              <label for="" class="col-sm-4 control-label">Jabatan</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="jabatan_penanggung_jawab_asset" placeholder="Jabatan Penanggung Jawab Asset" value="{{@$data['content']->JABATAN_PENANGGUNG_JAWAB_ASSET}}" readonly="1">
              </div>
            </div>
        
        </div>
        <!-- /.col -->



      </div>
      <!-- /.row -->
    </div><!-- /.box-body -->
</div><!-- /.box danger -->

<div class="box box-primary">

    <div class="box-header with-border">
      <h3 class="box-title"><span class="direct-chat-text" style="margin-left:0%"><b>DETAIL ASSET SAP</b></span></h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button-->
      </div>
    </div><!-- /.box-header -->
    
    <div class="box-body">

      <div class="row">
        <div class="col-md-6">

        	<div class="form-group">
              <label for="" class="col-sm-4 control-label">Kode Asset SAP</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="kode_asset_sap" name="kode_asset_sap" placeholder="Kode Asset SAP" value="{{@$data['content']->KODE_ASSET_SAP}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Asset Controller</label>

              <div class="col-sm-8">
                <input type="text" class="form-control" id="asset_controller" placeholder="Asset Controller" value="{{@$data['content']->ASSET_CONTROLLER}}" readonly="1">
              </div>
            </div>

            <!--div class="form-group">
              <label for="" class="col-sm-3 control-label">No Reg Item</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" id="no_reg_item" placeholder="No Reg Item" value="{{@$data['content']->NO_REG_ITEM}}" readonly="1">
              </div>
            </div-->

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Nama Asset 1</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nama_asset_1" name="nama_asset_1" placeholder="Nama Asset 1" value="{{@$data['content']->NAMA_ASSET_1}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Nama Asset 3</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nama_asset_3" name="nama_asset_3" placeholder="Nama Asset 3" value="{{@$data['content']->NAMA_ASSET_3}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Capitalized On</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="capitalized_on" name="capitalized_on" placeholder="Capitalized On" value="{{@$data['content']->CAPITALIZED_ON}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Deactivation On</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="deactivation_on" name="deactivation_on" placeholder="Deactivation On" value="{{@$data['content']->DEACTIVATION_ON}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Kode Asset SubNo SAP</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="kode_asset_subno_sap" name="kode_asset_subno_sap" placeholder="Kode Asset SubNo SAP" value="{{@$data['content']->KODE_ASSET_SUBNO_SAP}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">GI Number</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="gi_number" name="gi_number" placeholder="GI Number" value="{{@$data['content']->GI_NUMBER}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">GI Year</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="gi_year" name="gi_year" placeholder="GI Year" value="{{@$data['content']->GI_YEAR}}" readonly="1">
              </div>
            </div>

        </div>
        <!-- /.col -->
        
        <div class="col-md-6">

        	<div class="form-group">
              <label for="" class="col-sm-4 control-label">Kode Asset Controller</label>

              <div class="col-sm-8">
                <input type="text" class="form-control" id="kode_asset_controller" placeholder="Kode Asset Controller" value="{{@$data['content']->CODE_ASSET_CONTROLLER}}" readonly="1">
              </div>
            </div>
        	
        	<!--div class="form-group">
              <label for="" class="col-sm-3 control-label">Item PO</label>

              <div class="col-sm-9">
                <input type="text" class="form-control" id="item_po" placeholder="Item PO" value="{{@$data['content']->ITEM_PO}}" readonly="1">
              </div>
            </div-->

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Nama Asset 2</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="nama_asset_2" placeholder="Nama Asset 2" value="{{@$data['content']->NAMA_ASSET_2}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Quantity</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="quantity_asset_sap" placeholder="Quantity Asset SAP" value="{{@$data['content']->QUANTITY_ASSET_SAP}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">UOM</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="uom_asset_sap" placeholder="UOM Asset SAP" value="{{@$data['content']->UOM_ASSET_SAP}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Cost Center</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="cost_center" placeholder="Cost Center" value="{{@$data['content']->COST_CENTER}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Book Deprec 01</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="book_deprec_01" placeholder="Book Deprec 01" value="{{@$data['content']->BOOK_DEPREC_01}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Fiscal Deprec 15</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="fiscal_deprec_15" placeholder="Fiscal Deprec 15" value="{{@$data['content']->FISCAL_DEPREC_15}}" readonly="1">
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Group Deprec 30</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="group_deprec_01" placeholder="Group Deprec 30" value="{{@$data['content']->GROUP_DEPREC_30}}" readonly="1">
              </div>
            </div>
        
        </div>
        <!-- /.col -->



      </div>
      <!-- /.row -->
    </div><!-- /.box-body -->
</div><!-- /.box default -->

<div class="box box-default">

    <div class="box-header with-border">
      <h3 class="box-title"><span class="direct-chat-text" style="margin-left:0%"><b>RINCIAN FILE ASSET</b></span></h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        <!--button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button-->
      </div>
    </div><!-- /.box-header -->
    
    <div class="box-body">

    	<div class="row">
    	<?php 

    		//echo "2<pre>"; print_r($data['file']);
    		$l = "";

    		if(!empty($data['file']))
    		{
    			header("Content-type: image/jpeg");

    			foreach( $data['file'] as $k => $v )
    			{
    				if( $v->FILE_CATEGORY == 'asset' )
    				{
    					$l .= "<div class='col-md-4' align='center'>";
    					$l .= "<span class='username'><b>".$v->JENIS_FOTO."</b></span>";
    					$l .= "<img src='".$v->FILE_UPLOAD."' class='img img-responsive'/>";
    					$l .= "<span class='username'><b>".$v->FILENAME."</b></span>";
    					$l .= "</div>";
    				}
    				else
    				{
    					$l .= "<div class='col-md-4' align='center'>";
    					$l .= "<span class='username'>foto aset</span>";
    					$l .= "<img src='' class='img img-responsive'/>";
    					$l .= "<span class='username'></span>";
    					$l .= "</div>";
    				}

    				if( $v->FILE_CATEGORY == 'no seri' )
    				{
    					$l .= "<div class='col-md-4' align='center'>";
    					$l .= "<span class='username'><b>".$v->JENIS_FOTO."</b></span>";
    					$l .= "<img src='".$v->FILE_UPLOAD."' class='img img-responsive'/>";
    					$l .= "<span class='username'><b>".$v->FILENAME."</b></span>";
    					$l .= "</div>";
    				}
    				else
    				{
    					$l .= "<div class='col-md-4' align='center'>";
    					$l .= "<span class='username'>no seri</span>";
    					$l .= "<img src='' class='img img-responsive'/>";
    					$l .= "<span class='username'></span>";
    					$l .= "</div>";
    				}

    				if( $v->FILE_CATEGORY == 'imei' )
    				{
    					$l .= "<div class='col-md-4' align='center'>";
    					$l .= "<span class='username'><b>".$v->JENIS_FOTO."</b></span>";
    					$l .= "<img src='".$v->FILE_UPLOAD."' class='img img-responsive'/>";
    					$l .= "<span class='username'><b>".$v->FILENAME."</b></span>";
    					$l .= "</div>";
    				}
    				else
    				{
    					$l .= "<div class='col-md-4' align='center'>";
    					$l .= "<span class='username'>IMEI</span>";
    					$l .= "<img src='' class='img img-responsive'/>";
    					$l .= "<span class='username'></span>";
    					$l .= "</div>";
    				}

    				//echo "3<pre>"; print_r($v);
    				/*
    					[ID] => 2
					    [KODE_ASSET] => 40100194
					    [NO_REG_ITEM_FILE] => 1
					    [NO_REG] => 19.07/AMS/PDFA/00030
					    [JENIS_FOTO] => foto asset
					    [FILENAME] => 144cda8044.jfif
					    [DOC_SIZE] => 77312
					    [FILE_CATEGORY] => asset
					    [FILE_UPLOAD] => data:image/jpeg;base64,/9j/4AAQSkZJRgABAQIAOQA5AAD/
					    [CREATED_BY] => 29
					    [CREATED_AT] => 2019-07-08 09:55:37
					    [UPDATED_BY] => 
					    [UPDATED_AT] => 
    				*/
    			}
    		}

    		echo $l; 
    	?>
    	</div>

    	<?php /*
		<div class="row">
		<div class="col-md-6">
		</div>
		<!-- /.col -->

		<div class="col-md-6">
		</div>
		<!-- /.col -->
		</div><!-- /.row -->
		*/ ?>
    
    </div><!-- /.box-body -->
</div><!-- /.box default -->

</form>

</section>

</div>

<div id="qrcode-modal" class="modal fade" role="dialog" aria-labelledby="largeModal" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="xbox-body">
                    <!--div class="generate-qrcode text-center"></div-->
                    <div class="xvisible-print text-center">      

                        <!--img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(250)->generate('$qrcode')) !!} "-->
                        <?php echo QrCode::size(250)->generate(''.$qrcode.''); ?>

                        <a href="data:image/png;base64, <?php echo base64_encode(QrCode::format('png')->size(350)->generate(''.$qrcode.'')); ?>" target="_blank" download="{!! $data['id'].'.png' !!}"><button type="button" class="btn bg-navy btn-flat margin"><i class="fa fa-download"></i> DOWNLOAD </button></a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

@stop
@section('js')
<script type="text/javascript">

$(document).ready(function() 
{
    //alert("modal");
    $('#qrcode-modal').on('show.bs.modal', function () {
           $(this).find('.modal-body').css({
                  width:'auto', //probably not needed
                  height:'auto', //probably not needed 
                  'max-height':'100%'
           });
    });
});


function show_qrcode(amscode)
{
    //alert(amscode);

    var ams = btoa(amscode);

    $.ajax({
        type: 'GET',
        url: "{{ url('/master-asset/show_qrcode') }}/"+ams,
        data: "",
        //async: false,
        dataType: 'json',
        success: function(data) 
        {
            //alert(data.filename);
            $("#qrcode-modal .generate-qrcode").html("<span='bg-green'>"+data.filename+"</span>");
            $("#qrcode-modal .modal-title").html("<i class='fa fa-edit'></i>  QR Code AMS - <span style='color:#dd4b39'>"+amscode+"</span>");
            $('#qrcode-modal').modal('show');
        },
        error: function(x) 
        {                           
            alert("Error: "+ "\r\n\r\n" + x.responseText);
        }
    }); 

    
}

</script>
@stop