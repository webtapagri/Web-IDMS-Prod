<?php //echo "<pre>"; print_r(session()->all()); die(); ?>

@extends('adminlte::page')
@section('title', 'FAMS - Download Master General QRCode')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <div class="table-container small">
					
					<h2>Download QRCode</h2>
					
					<form action="{{ route('download_masterasset_qrcode') }}" method="post">
						@csrf
						<div class="form-group">
							<label for="">From</label>
							<input type="text" name="id" id="" class="form-control">
						</div>
						<div class="form-group">
							<label for="">To</label>
							<input type="text" name="di" id="" class="form-control">
						</div>
						<div class="form-group">
							<label for=""></label>
							<input type="submit" name="submit" id="" value="Export" class="form-control btn btn-primary">
						</div>
					</form>
				
				</div>

            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>


@stop
@section('js')

@stop