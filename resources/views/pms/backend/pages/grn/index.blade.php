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
                   
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="panel panel-body">
                <form action="{{ url('pms/grn/grn-process') }}" method="get" accept-charset="utf-8">
                    <div class="row">
                        <div class="col-md-2 col-sm-6">
                            <p class="mb-1 font-weight-bold"><label for="from_date">{{ __('From Date') }}:</label></p>
                            <div class="input-group input-group-md mb-3 d-">
                                <input type="date" name="from_date" id="from_date" class="form-control rounded" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="{{ $from_date }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <p class="mb-1 font-weight-bold"><label for="to_date">{{ __('To Date') }}:</label></p>
                            <div class="input-group input-group-md mb-3 d-">
                                <input type="date" name="to_date" id="to_date" class="form-control rounded" aria-label="Large" aria-describedby="inputGroup-sizing-sm" value="{{ $to_date }}">
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <p class="mb-1 font-weight-bold"><label for="received_status">{{ __('Received Status') }}:</label></p>
                            <div class="input-group input-group-md mb-3 d-">
                                <select name="received_status" id="received_status" class="form-control rounded">
                                    <option value="">{{ __('Select One') }}</option>
                                    <option value="full" {{ $received_status == "full" ? 'selected' : '' }}>Full Received</option>
                                    <option value="partial" {{ $received_status == "partial" ? 'selected' : '' }}>Partial Received</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6">
                            <p class="mb-1 font-weight-bold"><label for="searchGRNList"></label></p>
                            <div class="input-group input-group-md">
                                <button class="btn btn-success rounded mt-8" type="submit"><i class="las la-search"></i>&nbsp;Search</button>
                                &nbsp;&nbsp;
                                <a class="btn btn-danger rounded mt-8" href="{{ url('pms/grn/grn-process') }}"><i class="las la-times"></i>&nbsp;Clear</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            @include('yajra.datatable')
                        </div>
                    </div>
               </div>
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
<script type="text/javascript">
    function showGateInPODetails(element) {
        var modalTitle= element.attr('data-title');
        $.ajax({
            url: element.attr('data-src'),
            type: 'get',
            dataType: 'json',
            data: '',
        })
        .done(function(response) {
            if (response.result=='success') {
                $('#POdetailsModel .modal-title').html(modalTitle);
                $('#POdetailsModel').find('#body').html(response.body);
                $('#POdetailsModel').modal('show');
            }
        })
        .fail(function(response){
            notify('Something went wrong!','error');
        });
    }
</script>
@endsection