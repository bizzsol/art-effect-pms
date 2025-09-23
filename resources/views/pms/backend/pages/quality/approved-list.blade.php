@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .list-unstyled .ratings {
        display: none;
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
                    <a href="{{ route('pms.quality.ensure.approved.list') }}" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul>
        </div>

        <div class="page-content">
            <div class="">

                <div class="panel-body">
                    <div class="table-responsive">

                        <table class="table table-striped table-bordered table-head" cellspacing="0" width="100%" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th>SL</th>
                                    <th>Category</th>
                                    <th>Product</th>
                                    <th>UOM</th>
                                    <th>Qty</th>
                                    <th>Unit Price ({{ $grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
                                    <th>Received Qty</th>
                                    <th>Price ({{ $grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                $sumOfReceivedtQty=0;
                                
                                $sumOfItemQty=0;
                                $sumOfSubtotal=0;

                                $discountAmount= 0;
                                $vatAmount= 0;

                                @endphp
                                @if(isset($approval_list))
                                @foreach($approval_list as $key=>$item)
                                @php 
                                $sumOfReceivedtQty +=($item->received_qty);
                                $sumOfItemQty +=($item->relGoodsReceivedItems->qty);
                                
                                $sumOfSubtotal += $item->unit_amount*$item->received_qty;

                                $discountAmount +=($item->discount_percentage * $item->unit_amount*$item->received_qty)/100;

                                $vatAmount +=($item->vat_percentage * $item->unit_amount*$item->received_qty)/100;
                                @endphp

                                <tr id="removeApprovedRow{{$item->id}}">
                                    <td class="text-center">{{$key+1}}</td>
                                    <td>{{isset($item->relGoodsReceivedItems->relProduct->category->name)?$item->relGoodsReceivedItems->relProduct->category->name:''}}</td>
                                    <td>{{isset($item->relGoodsReceivedItems->relProduct->name)?$item->relGoodsReceivedItems->relProduct->name:''}} ({{isset($item->relGoodsReceivedItems->relProduct->sku)?$item->relGoodsReceivedItems->relProduct->sku:''}}) {{ getProductAttributesFaster($item->relGoodsReceivedItems->relProduct)}} {{ getProductAttributesFaster($item->relGoodsReceivedItems)}}</td>
                                    <td class="text-center">{{isset($item->relGoodsReceivedItems->relProduct->productUnit->unit_name)?$item->relGoodsReceivedItems->relProduct->productUnit->unit_name:''}}</td>
                                    <td class="text-center">{{systemDoubleValue($item->relGoodsReceivedItems->qty,0)}}</td>
                                    <td class="text-right">{{$item->unit_amount}}</td>
                                    <td class="text-center">{{$item->received_qty}}</td>
                                    <td class="text-right">{{systemMoneyFormat($item->unit_amount*$item->received_qty)}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-right">Total</td>
                                    <td colspan="" class="text-center">{{isset($sumOfItemQty)?systemMoneyFormat($sumOfItemQty,0):0}}</td>
                                    <td colspan="" class="text-right">{{isset($approval_list)?systemMoneyFormat($approval_list->sum('unit_amount')):0}}</td>
                                    <td class="text-center">{{isset($sumOfReceivedtQty)?systemMoneyFormat($sumOfReceivedtQty):0}}</td>
                                    
                                    <td colspan="" class="text-right">{{isset($approval_list)?systemMoneyFormat($sumOfSubtotal):0}}</td>
                                </tr>
                                
                                <tr>
                                    <td colspan="7" class="text-right">(+) Vat</td>
                                    <td class="text-right">{{systemMoneyFormat($vatAmount)}}</td>
                                </tr>
                                <tr>
                                    <td colspan="7" class="text-right"><strong>Total Amount</strong></td>
                                    <td class="text-right"><strong>{{systemMoneyFormat(($sumOfSubtotal-$discountAmount)+$vatAmount)}}</strong></td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="7" class="text-right">No Data Found</td>
                                </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>



@endsection
@section('page-script')
<script>

</script>
@endsection