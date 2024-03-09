@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .list-unstyled .ratings {
        display: none;
    }
</style>
@include('yajra.css')
@endsection
@section('main-content')

<div class="main-content">
    <div class="main-content-inner">
        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{  route('pms.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li>
                    <a href="#">PMS</a>
                </li>
                <li class="active">{{__($title)}}</li>
                <li class="top-nav-btn">
                    {{--<a href="{{ route('pms.grn.grn-process.index') }}" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>--}}
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="panel panel-info mt-2 p-2">
                @include('yajra.datatable')
            </div>
        </div>
    </div>
</div>

<div class="modal" id="POdetailsModel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Purchase Order</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body" id="body">

            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection
@section('page-script')
@include('yajra.js')
<script>
    function showQEPODetails(element) {
        var modalTitle = element.attr('data-title');
        $.ajax({
            url: element.attr('data-src'),
            type: 'get',
            dataType: 'json',
            data: '',
        })
        .done(function(response) {
            if (response.result=='success') {
                $('#POdetailsModel').find('#body').html(response.body);
                $('#POdetailsModel').find('.modal-title').html(modalTitle);
                $('#POdetailsModel').modal('show');
            }
        })
        .fail(function(response){
            notify('Something went wrong!','error');
        });
    }
</script>
@endsection