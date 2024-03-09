@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .select2-container{
        width:  100% !important;
    }
</style>
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
                <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" title="Back" > <i class="las la-chevron-left"></i>Back</a>
            </li>
        </ul>
    </div>
<div class="page-content">
<div class="">
<div class="panel panel-info">
<form  method="post" id="updateInventoryForm" action="{{ route('pms.store-manage.store-requisition.submit') }}">
@csrf
<div class="panel-body">
<div class="row">

<div class="col-md-3">
    <div class="form-group">
        <div class="form-line">
            {!! Form::label('delivery_date', 'Delivery Date', array('class' => 'mb-1 font-weight-bold')) !!} <strong><span class="text-danger">*</span></strong>
            {!! Form::text('delivery_date',old('delivery_date',date('d-m-Y')),['id'=>'delivery_date','class' => 'form-control rounded air-datepicker','placeholder'=>'','readonly'=>'readonly']) !!}

        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <div class="form-line">
            {!! Form::label('requisition_reference_no', 'Requisition Ref No', array('class' => 'mb-1 font-weight-bold')) !!} <strong><span class="text-danger">*</span></strong>
            
            <input type="text" id="requisition_reference_no" name="" class="form-control rounded" readonly value="{{$requisition->reference_no}}">

        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <div class="form-line">
            {!! Form::label('reference_no', 'Ref No', array('class' => 'mb-1 font-weight-bold')) !!} <strong><span class="text-danger">*</span></strong>
            {!! Form::text('reference_no',old('reference_no')?old('reference_no'):$refNo,['id'=>'reference_no','required'=>false,'class' => 'form-control rounded','placeholder'=>'Enter Reference No','readonly'=>'readonly']) !!}

            @if ($errors->has('reference_no'))
            <span class="help-block"><strong class="text-danger">{{ $errors->first('reference_no') }}</strong>
            </span>
            @endif

        </div>
    </div>
</div>
</div><!--end row -->
<div class="table-responsive mt-5">
<h5 class="mb-3">Requisition Details</h5>

<table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
    <thead>
        <tr class="text-center">
            <th>SL</th>
            <th>Category</th>
            <th>Product</th>
            <th>Stock Qty</th>
            <th>Req. Qty</th>
            <th>PO. Qty</th>
            <th>QC. Qty</th>
            <th>Prv. Dlv.  Qty</th>
            <th>Dlv. Qty</th>
            <th>Left. Qty</th>
            <th>WareHouse</th>
            <th>Cost Centre</th>
        </tr>
    </thead>
    <tbody>

        @if(isset($requisitionItems[0]))
            @php 
            $totalStockQty = 0;
            $totalRequisitionQty = 0;
            @endphp
            @foreach($requisitionItems as $key=>$item)
                @if(isset($item->product->id))
                    @if($item->qty!=$item->delivery_qty)
                        
                            <tr id="SelectedRow{{$item->product->id}}">
                                <td>{{$key+1}}</td>
                                <td>{{isset($item->product->category->name)?$item->product->category->name:''}}</td>
                                <td>{{isset($item->product->name)?$item->product->name:''}} {{ getProductAttributesFaster($item->product) }}</td>
                                <td class="text-center">{{$item->item_stock_sum}}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-center">{{$item->purchase_qty}}</td>
                                <td class="text-center">{{$item->qc_qty}}</td>
                                <td class="text-center">{{$item->delivery_qty}}</td>
                                <td>
                                    <input type="number" name="delivery_qty[{{$item->product->id}}]" id="delivery_qty_{{$item->product->id}}" class="form-control deliveryQty" min="0" max="{{$item->qc_qty<$item->item_stock_sum ? $item->qc_qty-$item->delivery_qty : $item->item_stock_sum}}" data-id="{{$item->product->id}}" value="{{$item->qc_qty<$item->item_stock_sum ? $item->qc_qty-$item->delivery_qty : $item->item_stock_sum}}" data-id="{{$item->product->id}}">
                                    </td>
                                    <td>
                                        <input type="hidden" value="{{$item->qc_qty-$item->delivery_qty}}" id="leftQty_{{$item->product->id}}">
                                        <span id="leftQtyAfterSubTract_{{$item->product->id}}">{{$item->qc_qty-$item->delivery_qty}}</span>
                                    </td>
                                    <td>
                                        <select class="form-control not-select2 warehouse" name="warehouse_id[{{$item->product->id}}]" id="warehouse_{{$item->product->id}}">
                                            @if(isset($item->product->relInventoryDetails))
                                                @foreach($item->product->relInventoryDetails as $data)
                                                    @if(in_array($data->warehouse_id, auth()->user()->relUsersWarehouse->pluck('id')->toArray()))
                                                    
                                                    <option value="{{$data->warehouse_id}}" data-role="{{$data->item_warehouse_stock_sum}}"> {!! (isset($data->relWarehouse->name)?$data->relWarehouse->name:'') . '('.$data->item_warehouse_stock_sum.')' !!}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        @if(!($item->product->category->is_fixed_asset == 1 || $item->product->category->is_cwip == 1))
                                            <select class="form-control not-select2 cost_centre" name="cost_centre_id[{{$item->product->id}}]" id="cost_centre_{{$item->product->id}}">
                                               {!! $costCentres !!}
                                            </select>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                $totalStockQty += isset($item->product->relInventorySummary->qty)?$item->product->relInventorySummary->qty:0;
                                $totalRequisitionQty += $item->qty;
                                @endphp
                        @endif
                    @endif
                @endforeach
            @endif
            <tr>
                <td colspan="3" class="text-right">Total</td>
                <td colspan="" class="text-center">{{isset($totalStockQty) ? $totalStockQty : 0}}</td>
                <td colspan="" class="text-center">{{isset($totalRequisitionQty) ? $totalRequisitionQty : 0}}</td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="1"></td>
                <td colspan="4" id="totalDeliveryQty"></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="form-row">
    <input type="hidden" name="requisition_id" value="{{$requisition->id}}">
    <div class="col-12 text-right">
        <button type="submit" class="btn btn-success rounded">{{ __('Confirm Delivery') }}</button>
    </div>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
@endsection
@section('page-script')
<script>
    (function ($) {
        "use strcit";

        const calculateDeliveryQty = () => {
            $('.deliveryQty').on('keyup', function () {
                let id = $(this).attr('data-id');

                let deliveryQty= parseFloat($('#delivery_qty_'+id).val());
                let maxQty= parseFloat($('#delivery_qty_'+id).attr('max'));
                let mainLeftQty = parseFloat($('#leftQty_'+id).val());

                if (deliveryQty<=maxQty) {
                    $('#leftQtyAfterSubTract_'+id).text(mainLeftQty-deliveryQty);

                    if (deliveryQty>0){
                        $('#warehouse_'+id).attr('required',true);
                    }else {
                        $('#warehouse_'+id).attr('required',false);
                    }

                    let totalDeliveryQty=0;
                    $('.deliveryQty').each(function () {
                        if ($(this).val().length!==0){
                            totalDeliveryQty+=parseInt($(this).val())
                        }
                    })
                    $('#totalDeliveryQty').text(totalDeliveryQty);
                }else{
                    setTimeout(notify('Total delivery qty not greater then left qty.', 'warning'),20000)
                    $('#delivery_qty_'+id).val(maxQty);
                }

                return false;
            });
        };

        calculateDeliveryQty();

    })(jQuery);
</script>

@endsection