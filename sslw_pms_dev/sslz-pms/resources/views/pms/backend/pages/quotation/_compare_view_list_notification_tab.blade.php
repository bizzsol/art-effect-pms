@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
.form-check-input {
    margin-top: -4px !important;
}
</style>
@if(count($quotations)>2)
<style type="text/css">
thead,
tbody tr {
    display: table;
    width: 2000px;
    table-layout: fixed;
}

thead {
    width: calc(2000px)
}

ul {
    list-style: none;
}
</style>
@endif
@endsection
@section('main-content')
@php
use \App\Models\PmsModels\QuotationsItems;
@endphp

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
                    <a href="javascript:history.back()" class="btn btn-sm btn-warning text-white" data-toggle="tooltip"
                        title="Back"> <i class="las la-chevron-left"></i>Back</a>
                </li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="">
                <div class="panel panel-body">
                    @if($quotations)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-info table-responsive">
                                <div class="col-lg-12 invoiceBody">
                                    <div class="invoice-details mt25 row">

                                        @foreach($quotations as $key=>$quotation)
                                        @if($key==0)

                                        <div class="col-md-6 well">
                                            <ul class="list-unstyled mb0">
                                                <li><strong>{{__('Request Proposal No')}} :</strong>
                                                    {{$quotation->relRequestProposal->reference_no}}
                                                </li>
                                                <li><strong>Project Name:</strong></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6 well">
                                            <ul class="list-unstyled mb0">
                                                <li><strong>{{__('RFP Provide By')}} :</strong>
                                                    {{$quotation->relRequestProposal->createdBy->name}}
                                                </li>
                                                <li><strong>{{__('RFP Date')}} :</strong>
                                                    {{date('d-m-Y h:i:s A',strtotime($quotation->relRequestProposal->request_date))}}
                                                </li>
                                            </ul>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="table-responsive">

                                    <table class="table table-bordered table-hover ">
                                        <thead class="thead">
                                            <tr>
                                                <th colspan="5">Party Name</th>
                                                @foreach($quotations as $q_key => $quotation)
                                                @php
                                                $TS =
                                                number_format($quotation->relSuppliers->SupplierRatings->sum('total_score'),2);
                                                $TC = $quotation->relSuppliers->SupplierRatings->count();

                                                $totalScore = isset($TS)?$TS:0;
                                                $totalCount = isset($TC)?$TC:0;
                                                @endphp
                                                <th class="invoiceBody"
                                                    colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}">

                                                    <p class="ratings text-dark">

                                                        <a href="{{route('pms.supplier.profile',$quotation->relSuppliers->id)}}"
                                                            target="_blank"><span>{{$quotation->relSuppliers->name}}
                                                                ({{$quotation->relSuppliers->code}})</span></a>
                                                        @if(in_array($quotation->type,['manual','online']))
                                                        ({!!ratingGenerate($totalScore,$totalCount)!!})
                                                        @endif

                                                    </p>

                                                    <p class="text-dark"><strong>{{__('Q:Ref:No')}}:</strong>
                                                        {{$quotation->reference_no}}
                                                    </p>
                                                    <p class="mt-2"></strong><span
                                                            class="btn btn-xs btn-{{ $quotation->is_approved == 'approved' ? 'success' : ($quotation->is_approved == 'processing' ? 'warning' : 'danger') }}">{{ucfirst($quotation->is_approved)}}</span>
                                                    </p>

                                                </th>
                                                @endforeach
                                            </tr>
                                            <tr class="text-right">
                                                <th>SL</th>
                                                <th>Category</th>
                                                <th>Product</th>
                                                <th>UOM</th>
                                                <th>Qty</th>
                                                @foreach($quotations as $quotation)
                                                <th class="text-center">Unit Price
                                                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})
                                                </th>
                                                <th class="text-center">Item Total
                                                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})
                                                </th>
                                                @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                $quotation->exchangeRate->currency->code : ''))
                                                <th class="text-center">Item Total ({{ $systemCurrency->code }})
                                                </th>
                                                @endif
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="tbody">
                                            @php
                                            $total_qty=0;
                                            @endphp
                                            @if(isset($quotation->id))
                                            @foreach($quotation->relQuotationItems()->groupBy('product_id')->get()
                                            as $key=>$item)
                                            <tr>
                                                <td class="text-center">{{$key+1}}</td>
                                                <td>{{isset($item->relProduct->category->name)?$item->relProduct->category->name:''}}
                                                </td>
                                                <td>{{isset($item->relProduct->name)?$item->relProduct->name:''}}
                                                    {{getProductAttributes($item->relProduct->id)}}
                                                </td>
                                                <td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}
                                                </td>
                                                <td class="text-center">{{$item->qty}}</td>

                                                @foreach($quotations as $key=>$quotation)
                                                <td class="text-right">
                                                    {{number_format(isset(QuotationsItems::where('product_id', $item->product_id)->where('quotation_id', $quotation->id)->first()->unit_price) ? QuotationsItems::where('product_id', $item->product_id)->where('quotation_id', $quotation->id)->first()->unit_price : 0,2)}}
                                                </td>
                                                <td class="text-right">
                                                    {{number_format(isset(QuotationsItems::where('product_id', $item->product_id)->where('quotation_id', $quotation->id)->first()->sub_total_price) ? QuotationsItems::where('product_id', $item->product_id)->where('quotation_id', $quotation->id)->first()->sub_total_price : 0,2)}}
                                                </td>
                                                @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                $quotation->exchangeRate->currency->code : ''))
                                                <td class="text-right">
                                                    {{number_format(isset(QuotationsItems::where('product_id', $item->product_id)->where('quotation_id', $quotation->id)->first()->sub_total_price) ? QuotationsItems::where('product_id', $item->product_id)->where('quotation_id', $quotation->id)->first()->sub_total_price*exchangeRate($quotation->exchangeRate, $systemCurrency->id) : 0,2)}}
                                                </td>
                                                @endif
                                                @endforeach

                                            </tr>
                                            @php
                                            $total_qty += $item->qty;
                                            @endphp
                                            @endforeach
                                            @endif

                                            <tr>
                                                <td colspan="4" class="text-right"><strong>Total</strong></td>
                                                <td class="text-center"><strong>{{$total_qty}}</strong></td>
                                                @foreach($quotations as $key=>$quotation)
                                                <td colspan=""><strong>Sub Total</strong></td>
                                                <td class="text-right">
                                                    <strong>{{number_format(QuotationsItems::where('quotation_id', $quotation->id)->sum('sub_total_price'),2)}}</strong>
                                                </td>
                                                @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                $quotation->exchangeRate->currency->code : ''))
                                                <td class="text-right">
                                                    <strong>{{number_format(QuotationsItems::where('quotation_id', $quotation->id)->sum('sub_total_price')*exchangeRate($quotation->exchangeRate, $systemCurrency->id),2)}}</strong>
                                                </td>
                                                @endif
                                                @endforeach
                                            </tr>

                                            <tr>
                                                <td colspan="5" class="text-right"></td>

                                                @foreach($quotations as $key=>$quotation)
                                                <td><strong>(-) Discount</strong></td>
                                                <td class="text-right">
                                                    <strong>{{number_format($quotation->discount,2)}}</strong>
                                                </td>
                                                @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                $quotation->exchangeRate->currency->code : ''))
                                                <td class="text-right">
                                                    <strong>{{number_format($quotation->discount*exchangeRate($quotation->exchangeRate, $systemCurrency->id),2)}}</strong>
                                                </td>
                                                @endif
                                                @endforeach
                                            </tr>

                                            <tr>
                                                <td colspan="5" class="text-right"></td>

                                                @foreach($quotations as $key=>$quotation)
                                                <td><strong>(+) Vat </strong></td>
                                                <td class="text-right"><strong>{{$quotation->vat}}</strong>
                                                </td>
                                                @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                $quotation->exchangeRate->currency->code : ''))
                                                <td class="text-right">
                                                    <strong>{{$quotation->vat*exchangeRate($quotation->exchangeRate, $systemCurrency->id)}}</strong>
                                                </td>
                                                @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>

                                                @foreach($quotations as $key=>$quotation)
                                                <td><strong>Gross Total</strong></td>
                                                <td class="text-right">
                                                    <strong><?= number_format($quotation->gross_price, 2); ?>
                                                    </strong>
                                                </td>
                                                @if($systemCurrency->code != ($quotation->exchangeRate ?
                                                $quotation->exchangeRate->currency->code : ''))
                                                <td class="text-right">
                                                    <strong><?= number_format($quotation->gross_price * exchangeRate($quotation->exchangeRate, $systemCurrency->id), 2); ?>
                                                    </strong>
                                                </td>
                                                @endif
                                                @endforeach
                                            </tr>

                                            <tr>
                                                <td colspan="5"></td>
                                                @foreach($quotations as $key=>$quotation)
                                                <td colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}"
                                                    class="text-left">
                                                    Payment Term:
                                                    <strong>{{ makePaymentTermsString($quotation->supplier_payment_terms_id) }})</strong>
                                                </td>
                                                @endforeach

                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                @foreach($quotations as $key => $quotation)
                                                <td
                                                    colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}">
                                                    Delivery Date: <span><strong>{!! date('d M Y',
                                                            strtotime($quotation->delivery_date))
                                                            !!}</strong></span></td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right">Notes</td>
                                                @foreach($quotations as $key => $quotation)
                                                <td
                                                    colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 3 : 2 }}">
                                                    <span><strong>@if(!empty($quotation->note))
                                                            {{ $quotation->creator->name }}:
                                                            @endif</strong> {!! $quotation->note !!} </span>
                                                </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection