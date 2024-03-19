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
                    <a href="{{ route('pms.quality.ensure.return.list') }}" class="btn btn-danger btn-sm"><i class="las la-arrow-left"></i> Back</a>
                </li>
            </ul><!-- /.breadcrumb -->
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
                                    <th>Unit Price ({{ $grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
                                    <th>Qty</th>
                                    <th>Received Qty</th>
                                    <th>Return Qty</th>
                                    <th>Price ({{ $grn->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                $sumOfReceivedtQty=0;
                                $sumOfReturntQty=0;
                                $sumOfItemQty=0;
                                $sumOfSubtotal=0;

                                $discountAmount= 0;
                                $vatAmount= 0;

                                @endphp
                                @if(isset($returnList))
                                @foreach($returnList as $key=>$item)
                                @php 
                                $received_qty = $item->relGoodsReceivedItems->relGoodsReceivedItemStockIn->sum('received_qty');
                                $sumOfReceivedtQty += ($received_qty);
                                $sumOfItemQty +=($item->relGoodsReceivedItems->qty);
                                $sumOfReturntQty += ($item->return_qty);
                                $sumOfSubtotal += $item->relGoodsReceivedItems->unit_amount*($item->return_qty);

                                $discountAmount += ($item->discount);
                                $vatAmount +=($item->relGoodsReceivedItems->vat);
                                @endphp

                                <tr id="removeApprovedRow{{$item->id}}">
                                    <td>{{$key+1}}</td>
                                    <td>{{isset($item->relGoodsReceivedItems->relProduct->category->name)?$item->relGoodsReceivedItems->relProduct->category->name:''}}</td>
                                    <td>{{isset($item->relGoodsReceivedItems->relProduct->name)?$item->relGoodsReceivedItems->relProduct->name:''}} {{ getProductAttributes($item->relGoodsReceivedItems->relProduct->id) }}</td>
                                    <td>{{isset($item->relGoodsReceivedItems->relProduct->productUnit->unit_name)?$item->relGoodsReceivedItems->relProduct->productUnit->unit_name:''}}</td>
                                    <td class="text-right">{{isset($item->relGoodsReceivedItems->unit_amount)?$item->relGoodsReceivedItems->unit_amount:''}}</td>
                                    <td class="text-center">{{systemDoubleValue($item->relGoodsReceivedItems->qty,0)}}</td>
                                    <td class="text-center">{{$received_qty}}</td>
                                    <td class="text-center">{{$item->return_qty}}</td>
                                    <td class="text-right">{{systemDoubleValue($item->relGoodsReceivedItems->unit_amount*$item->return_qty,2)}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5" class="text-right">Total</td>
                                    <td class="text-center">{{isset($sumOfItemQty)?systemDoubleValue($sumOfItemQty,0):0}}</td>
                                    <td class="text-center">{{isset($sumOfReceivedtQty)?systemDoubleValue($sumOfReceivedtQty,0):0}}</td>
                                    <td class="text-center">{{isset($sumOfReturntQty)?systemDoubleValue($sumOfReturntQty,0):0}}</td>
                                    <td class="text-right" colspan="">{{isset($returnList)?systemDoubleValue($sumOfSubtotal,4):0}}</td>
                                </tr>
                                {{-- <tr>
                                    <td colspan="8" class="text-right">(-) Discount</td>
                                    <td><?= systemDoubleValue($discountAmount,4)?></td>
                                </tr> --}}
                                <tr>
                                    <td colspan="8" class="text-right">(+) Vat</td>
                                    <td class="text-right">{{systemDoubleValue($vatAmount,4)}}</td>
                                </tr>
                                <tr>
                                <td colspan="8" class="text-right"><strong>Total Amount</strong></td>
                                <td class="text-right"><strong>{{systemDoubleValue(($sumOfSubtotal-$discountAmount)+$vatAmount,4)}}</strong></td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="9" class="text-right">No Data Found</td>
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