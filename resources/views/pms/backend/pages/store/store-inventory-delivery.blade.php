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
            <th>Product</th>
            <th>Attributes</th>
            <th>UOM</th>
            <th>Stock Qty</th>
            <th>Req. Qty</th>
            <th>PO. Qty</th>
            <th>QC. Qty</th>
            <th>Prv. Dlv.  Qty</th>
            <th>Left. Qty</th>
            <th>Dlv. UOM</th>
            <th>Dlv. Qty</th>
            <th>WareHouse</th>
            <th>Cost Centre</th>
        </tr>
    </thead>
    <tbody>

        @if(isset($requisitionItems[0]))
            @foreach($requisitionItems as $key=>$item)
                @if(isset($item->product->id))
                        @if($item->qty != $item->delivery_qty)
                        @php
                            $stock = isset($stocks[$item->id]) ? array_sum(array_values($stocks[$item->id])) : 0;
                            $left = floor(($item->purchase_qty > 0 ? $item->qc_qty : $item->qty) < $stock ? ($item->purchase_qty > 0 ? $item->qc_qty : $item->qty)-$item->delivery_qty : $stock);
                        @endphp
                            <tr id="SelectedRow{{$item->uid}}">
                                <td>{{$key+1}}</td>
                                <td>{{isset($item->product->name)?$item->product->name:''}} {{ getProductAttributesFaster($item->product) }}</td>
                                <td>{{ getProductAttributesFaster($item) }}</td>
                                <td class="text-center">
                                    {{ $item->product->productUnit->unit_name }}
                                </td>
                                <td class="text-center">{{ $stock }}</td>
                                <td class="text-center">{{$item->qty}}</td>
                                <td class="text-center">{{$item->purchase_qty}}</td>
                                <td class="text-center">{{$item->qc_qty}}</td>
                                <td class="text-center">{{$item->delivery_qty}}</td>
                                <td class="text-center">
                                    <input type="hidden" value="{{$item->qc_qty-$item->delivery_qty}}" id="leftQty_{{$item->uid}}">
                                    <span id="leftQtyAfterSubTract_{{$item->uid}}">{{$item->qc_qty-$item->delivery_qty}}</span>
                                </td>
                                <td class="text-center">
                                    <select name="product_unit_id[{{ $item->uid }}]" id="product_unit_id_{{ $item->uid }}" onchange="updateDeliveryQuantity($(this))" class="product_unit_id">
                                        <option value="{{ $item->product->product_unit_id }}" data-conversion-rate="1">{{ $item->product->productUnit->unit_name }}</option>
                                        @if($item->product->productUnitConversions->where('conversion_rate', '>', 0)->count() > 0)
                                        @foreach($item->product->productUnitConversions->where('conversion_rate', '>', 0) as $key => $this_unit)
                                        <option value="{{ $this_unit->conversion_unit_id }}" data-conversion-rate="{{ $this_unit->conversion_rate }}">{{ $this_unit->conversionUnit->unit_name }} ({{ $this_unit->conversion_rate }})</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="delivery_qty[{{$item->uid}}]" id="delivery_qty_{{$item->uid}}" class="form-control delivery_qty text-right" min="0" max="{{$left}}" value="{{$left}}" data-id="{{$item->uid}}" step="1" onchange="checkDeliveryQuantity($(this))" onkeyup="checkDeliveryQuantity($(this))">
                                </td>
                                <td>
                                    <select class="form-control not-select2 warehouse_id" name="warehouse_id[{{$item->uid}}]" id="warehouse_{{$item->uid}}" onchange="updateDeliveryQuantity($(this))">
                                        @if($warehouses->whereIn('id', array_keys($stocks[$item->id]))->count() > 0)
                                            @foreach($warehouses->whereIn('id', array_keys($stocks[$item->id])) as $warehouse)
                                                <option value="{{$warehouse->id}}" data-remaining="{{ $stocks[$item->id][$warehouse->id] }}"> {!! $warehouse->name. ' ('.$stocks[$item->id][$warehouse->id].' '.$item->product->productUnit->unit_name.')' !!}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                                <td>
                                    @if(!($item->product->is_fixed_asset == 1 || $item->product->is_cwip == 1))
                                        <select class="form-control not-select2 cost_centre" name="cost_centre_id[{{$item->uid}}]" id="cost_centre_{{$item->uid}}">
                                           {!! $costCentres !!}
                                        </select>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endif
                @endforeach
            @endif
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
    $('body').addClass('sidebar-main');

    function updateDeliveryQuantity(element) {
        var rate = element.parent().parent().find('.product_unit_id').find(':selected').attr('data-conversion-rate') != undefined ? element.parent().parent().find('.product_unit_id').find(':selected').attr('data-conversion-rate') : 0;
        var left = element.parent().parent().find('.warehouse_id').find(':selected').attr('data-remaining') != undefined ? element.parent().parent().find('.warehouse_id').find(':selected').attr('data-remaining') : 0;
        var qty = Math.floor(rate*left);

        element.parent().parent().find('.delivery_qty').val(qty).attr('max', qty);
    }

    function checkDeliveryQuantity(element) {
        var value = parseFloat(element.val());
        var max = parseFloat(element.attr('max'));
        var min = parseFloat(element.attr('min'));

        if(value > max){
            element.val(max);
        }

        if(value < min){
            element.val(min);
        }
    }
</script>

@endsection