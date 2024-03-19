@extends('pms.backend.layouts.master-layout')

@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .dropdown-toggle::after{
        display: none !important;
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
                <li class="active">{{__($title)}} List</li>

            </ul>
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-info">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header bg-primary p-0" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#filter" aria-expanded="true" aria-controls="filter">
                                        <h5 class="text-white"><strong><i class="las la-chevron-circle-right la-spin"></i>&nbsp;Search</strong></h5>
                                    </button>
                                </h5>
                            </div>

                            <div id="filter" class="collapse {{ !request()->has('from') ? 'show' : '' }}" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="{{ url('pms/requisition/delivered-requistion-list') }}" method="get" accept-charset="utf-8">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="search_text"><strong>Search By Text</strong></label>
                                                    <input type="text" name="search_text" id="search_text" value="{{ request()->has('search_text')?request()->get('search_text'):'' }}" class="form-control" placeholder="Search by deliverd ref no..">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="status"><strong>Status</strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="status" id="status" class="form-control">
                                                            <option value="{{null}}">--Select One--</option>
                                                            @foreach($statuss as $value)
                                                            <option value="{{$value}}" {{ request()->has('status')?(request()->get('status')==$value?'selected':''):'' }}>{{$value}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="category_id"><strong>Product Category</strong></label>
                                                    <div class="input-group input-group-md mb-3 d-">
                                                        <select name="category_id" id="category_id" class="form-control">
                                                            <option value="{{null}}">--Select One--</option>
                                                            @if(isset($categories[0]))

                                                            @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ request()->has('category_id') && request()->get('category_id') == $category->id ? 'selected' : '' }}>{{
                                                                $category->name.'('.$category->code.')'}}
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="from"><strong>Start Date</strong></label>
                                                    <input type="date" name="from" id="from" value="{{ $from }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="to"><strong>End Date</strong></label>
                                                    <input type="date" name="to" id="to" value="{{ $to }}" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-1 pt-1 pl-0 mt-4">
                                                <button class="btn btn-sm btn-block btn-success report-button" type="submit"><i class="la la-search"></i>&nbsp;Search</button>
                                            </div>
                                            <div class="col-md-1 pt-1 pl-0 mt-4">
                                                <a class="btn btn-sm btn-block btn-danger" href="{{ url('pms/requisition/delivered-requistion-list') }}"><i class="la la-times"></i>&nbsp;Clear</a>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body" id="viewResult">
                        @include('yajra.datatable')
                        {{-- <table class="table table-striped table-bordered table-head datatable-exportable" id="dataTable" data-table-name="{{ $title }}" border="1">
                            <thead>
                                <tr class="text-center">
                                    <th width="2%">SL No</th>
                                    <th>Requisition No</th>
                                    <th>Requisition Date</th>
                                    <th>Delivered Ref. No</th>
                                    <th>Delivered Date</th>
                                    <th>Category</th>
                                    <th>SubCategory</th>
                                    <th>Product</th>
                                    <th>Delivered Qty</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($deliveredRequisition))
                                @foreach($deliveredRequisition as $key => $values)
                                <tr>
                                    <td class="text-center" width="5%">{{($deliveredRequisition->currentpage()-1) * $deliveredRequisition->perpage() + $key + 1 }}</td>
                                    <td><a href="javascript:void(0)"
                                        data-src="{{route('pms.requisition.list.view.show',$values->relRequisitionDelivery->relRequisition->id)}}"  class="btn btn-link requisition m-1 rounded showRequistionDetails">{{ isset($values->relRequisitionDelivery->relRequisition->reference_no)?$values->relRequisitionDelivery->relRequisition->reference_no:'' }}</a></td>

                                        <td>{{ isset($values->relRequisitionDelivery->relRequisition->requisition_date)? date('d-m-Y',strtotime($values->relRequisitionDelivery->relRequisition->requisition_date)):'' }}</td>

                                        <td>
                                            {{isset($values->relRequisitionDelivery->reference_no)?$values->relRequisitionDelivery->reference_no:''}}
                                        </td>

                                        <td>{{ isset($values->relRequisitionDelivery->delivery_date)? date('d-m-Y',strtotime($values->relRequisitionDelivery->delivery_date)):'' }}</td>
                                        <td>
                                            {{ isset($values->product->category->category->name)?$values->product->category->category->name:'' }}
                                        </td>

                                        <td>
                                            {{ isset($values->product->category->name)?$values->product->category->name:'' }}
                                        </td>
                                        <td>{{ isset($values->product->name)?$values->product->name:'' }} {{ isset($values->product->id)? getProductAttributes($values->product->id):'' }}</td>
                                        <td class="text-center">{{ number_format($values->delivery_qty,0)}}</td>

                                        <td class="text-center" id="action{{$values->id}}">
                                            @if($values->status=='pending' ||$values->status=='delivered')
                                            <div class="btn-group">
                                                <button class="btn dropdown-toggle" data-toggle="dropdown">
                                                    <span id="statusName{{$values->id}}">
                                                        {{ ucfirst($values->status)}} 

                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                        </svg>
                                                    </span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li id="hideBtn{{$values->id}}"><a href="javascript:void(0)" class="deliveredAcknowledge" data-id="{{$values->id}}" title="Acknowledged"><i class="la la-check"></i> {{ __('Acknowledged')}}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            @else
                                            Acknowledged
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>

                            </table>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-right">
                                        @if(count($deliveredRequisition)>0)
                                        <ul>
                                            {{$deliveredRequisition->withQueryString()->links()}}
                                        </ul>
                                        @endif
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END WRAPPER CONTENT ------------------------------------------------------------------------->
    <div class="modal" id="requisitionDetailModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Requisition Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="tableData"></div>
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
        
        function showRequistionDetails(element){
                $.ajax({
                        url: element.attr('data-src'),
                        type: 'get',
                        dataType: 'json',
                        data: '',
                        success: function (response) {
                            $('#requisitionDetailModal').find('#tableData').html(response);
                            $('#requisitionDetailModal').find('.modal-title').html(`Requisition Details`);
                            $('#requisitionDetailModal').modal('show');
                        }
                    });
            }

            function deliveredAcknowledge(element){
                let id = element.attr('data-id');
                    $.ajax({
                        url: "{{ url('pms/requisition/delivered-requistion-ack') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {_token: "{{ csrf_token() }}", id:id},
                    })
                    .done(function(response) {
                        if(response.result=='success'){
                            $('#statusName'+id).html('Acknowledged');
                            $('#hideBtn'+id).hide();
                            notify(response.message,response.result);
                        }
                    })
                    .fail(function(response){
                        notify('Something went wrong!','error');
                    });
                    return false;
            }
    </script>
    @endsection
