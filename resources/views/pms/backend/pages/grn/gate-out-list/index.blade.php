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
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info">
                    <form action="{{ url('pms/grn/gate-out-list') }}" method="get" accept-charset="utf-8">
                        <div class="row pl-4 pt-3">
                            <div class="col-md-3 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="from_date">{{ __('From Date') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="date" name="from_date" id="from_date" class=" form-control rounded" aria-label="Large" aria-describedby="inputGroup-sizing-sm"  value="{{ $from_date }}">
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="to_date">{{ __('To Date') }}:</label></p>
                                <div class="input-group input-group-md mb-3 d-">
                                    <input type="date" name="to_date" id="to_date" class="form-control rounded " aria-label="Large" aria-describedby="inputGroup-sizing-sm"  value="{{ $to_date }}">
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6">
                                <p class="mb-1 font-weight-bold"><label for="searchPOList"></label></p>
                                <div class="input-group input-group-md">
                                    <button type="submit" class="btn btn-success rounded mt-8"><i class="las la-search"></i>Search</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel panel-body">
                    <div class="table-responsive" id="viewResult">
                        @include('yajra.datatable')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-script')
@include('yajra.js')
@endsection