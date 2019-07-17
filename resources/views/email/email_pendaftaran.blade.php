<?php 

	if( $data->datax[0]->PO_TYPE == 0 )
	{
		$TYPE_OF_SUBMISSION = 'PO SAP';
	}
	else
	{
		$TYPE_OF_SUBMISSION = 'PO AMP';
	}

?>

<h3>PERMOHONAN PERSETUJUAN PENGAJUAN ASSET - ( {{ $data->datax[0]->document_code }}  )</h3>
<h4>Asset Management PT. Triputra Agro Persada</h4>
<br/>
<br/>
Kepada Yth,
( JABATAN YANG DITUJU : V_USER_ROLE )
<br/>
<br/>
<br/>
Dibutuhkan persetujuan atas dokumen berikut :
<br/>
<br/>
<div style='margin-left:10%'>
	NO DOCUMENT : {{ $data->datax[0]->document_code }} <br/>
	TYPE OF SUBMISSION : {{ $TYPE_OF_SUBMISSION }} <br/>
	PURCHASE NO : {{ $data->datax[0]->NO_PO }} <br/>
	BUSINESS AREA KEPEMILIKAN : {{ $data->datax[0]->BA_PEMILIK_ASSET }} <br/><br/>
	<?php 

		//echo "1<pre>"; print_r($data->datax); 
		$content = "";

		if( $data->datax )
		{
			$no = 1;
			$l = " 
				<table border='1' cellpadding='1' cellspacing='1'>
					<tr>
						<th>NO</th>
						<th>KODE MATERIAL</th>
						<th>ASSETS</th>
						<th>BUSINESS AREA LOKASI</th>
					</tr>
			";
			foreach($data->datax as $k => $v)
			{
				$l .= " 
					<tr>
						<td>$no</td>
						<td>$v->KODE_MATERIAL</td>
						<td>$v->NAMA_MATERIAL</td>
						<td>$v->LOKASI_BA_CODE</td>
					</tr> 
				";
				$no++;
			}
			$l .= "</table>";

			$content = $l; 
		}

		echo $content;
	?>
	<br/>
	<h4> Historical Approval : </h4>
	<?php 

		$content2 = "";
		
		//echo "1<pre>"; print_r($data->history_approval);
		
		if( $data->history_approval )
		{
			$no = 1;
			$l = "";

			foreach( $data->history_approval as $kk => $vv )
			{
				$l .= $no.". ".$vv->name." :: ".$vv->status_approval."<br/>";
				$no++;
			}

			$content2 = $l;
		} 

		echo $content2; 

	?>
</div>

<br/>
<br/>

Email ini dihasilkan otomatis dari Asset Management System PT. Triputra Agro Persada <br/>
Mohon tidak melakukan reply.

<br/>
<br/>
<br/>

<h2>CATATAN PENTING</h2>
1.Melakukan Persetujuan secara Parsial <br/>
2.Melihat sejarah dokumen <br/>
3.Mengurangi jumlah barang/ jasa yang diajukan <br/>
4.Pengajuan revisi ke pembuat dokumen <br/>
5.Permohonan penambahan data pendukung ke pembuat dokumen <br/>
dapat dilakukan dengan memilih action Detail <br/>

<br/>

Email <br/>
Ext <br/>
HP <br/>
PIN <br/>
Website 
