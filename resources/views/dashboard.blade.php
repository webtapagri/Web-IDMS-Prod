{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<!-- <h1>Dashboard</h1> -->
@stop

@section('content')
<!-- /.row -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Outstanding</h3>
                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 150px;">
                        <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-hover">
                    <tr>
                        <th>No Document</th>
                        <th>Req. Date</th>
                        <th>Material</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Last Update</th>
                    </tr>
                    <tr>
                        <td>19.03/TAP-PPIC/00101</td>
                        <td>21 Mar 2019</td>
                        <td>Traktor 4 Roda Galaxy 304</td>
                        <td><span class="label label-danger">On Approval</span></td>
                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        <td>21 Mar 2019</td>
                    </tr>
                    <tr>
                        <td>19.03/TAP-PPIC/00101</td>
                        <td>21 Mar 2019</td>
                        <td>Traktor 4 Roda Galaxy 304</td>
                        <td><span class="label label-danger">On Approval</span></td>
                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        <td>21 Mar 2019</td>
                    </tr>
                    <tr>
                        <td>19.03/TAP-PPIC/00101</td>
                        <td>21 Mar 2019</td>
                        <td>Traktor 4 Roda Galaxy 304</td>
                        <td><span class="label label-danger">On Approval</span></td>
                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        <td>21 Mar 2019</td>
                    </tr>
                    <tr>
                        <td>19.03/TAP-PPIC/00101</td>
                        <td>21 Mar 2019</td>
                        <td>Traktor 4 Roda Galaxy 304</td>
                        <td><span class="label label-danger">On Approval</span></td>
                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                        <td>21 Mar 2019</td>
                    </tr>
                  
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
<script>

</script>
@stop 