@extends('pms.backend.layouts.master-layout')
@section('title', session()->get('system-information')['name']. ' | '.$title)
@section('page-css')
<style type="text/css">
    .form-check-input{
        margin-top: -4px !important;
    }
</style>
@if(count($quotations)>2)
<style type="text/css">
    thead, tbody tr {
        display:table;
        width: 2000px;
        table-layout:fixed;
    }
    thead {
        width: calc(2000px)
    } 
    ul {
        list-style: none;
    }
    .modal-body{
        overflow-x: auto !important;
    }
</style>
@endif
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
                    <a href="{{url('pms/quotation/cs-compare-view-pdf/'.$purchaseOrderId).'?downloadpdf'}}" class="btn btn-sm btn-danger text-white"title="Download CS" > <i class="las la-download"></i>Download CS</a>
                </li>
        </ul>
    </div>

<div class="page-content">
<div class="">
<div class="panel panel-body modal-body">

@if($quotations)
<div class="row">
<div class="col-md-12">
<div class="panel panel-info">
<div class="col-lg-12 invoiceBody">
    <div class="invoice-details mt25 row">

        @foreach($quotations as $key=>$quotation)
        @if($key==0)
        <div class="col-md-6 well">
            <ul class="list-unstyled mb0">
                <li><strong>CS Number :</strong> {{$quotation->relRequestProposal->reference_no}}</li>
            </ul>
        </div>
        <div class="col-md-6 well">
            <ul class="list-unstyled mb0">
                <li><strong>{{__('RFP Provide By')}} :</strong>
                    {{isset($quotation->relRequestProposal->createdBy->name) ? $quotation->relRequestProposal->createdBy->name : ''}}
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
<div class="table-responsive" style="width: auto;">
    <table class="table table-bordered table-hover ">
        <thead class="thead">
            <tr>
                <th colspan="6">Party Name</th>
                @foreach($quotations as $q_key => $quotation)
                @php
                    $TS = number_format($quotation->relSuppliers->SupplierRatings->sum('total_score'), 2);
                    $TC = $quotation->relSuppliers->SupplierRatings->count();

                    $totalScore = isset($TS) ? $TS : 0;
                    $totalCount = isset($TC) ? $TC : 0;
                @endphp
                <th class="invoiceBody" colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                    <p class="ratings text-dark">
                        <a href="{{route('pms.supplier.profile',$quotation->relSuppliers->id)}}"
                            target="_blank"><span>{{$quotation->relSuppliers->name}}
                                ({{$quotation->relSuppliers->code}})</span></a>
                        @if(in_array($quotation->type,['manual','online']))
                        ({!!ratingGenerate($totalScore,$totalCount)!!})
                        @endif
                    </p>
                    <p class="text-dark"><strong>{{__('Q:Ref:No')}}:</strong> {{$quotation->reference_no}}</p>
                </th>
                @endforeach
            </tr>
            <tr class="text-center">
                <th>SL</th>
                <th>Product</th>
                <th>Attributes</th>
                <th>Description</th>
                <th>UOM</th>
                <th>Qty</th>
                @foreach($quotations as $quotation)
                {{-- <th class="text-center">{!! $quotation->is_approved == 'approved' ? '<i class="las la-check-square text-success" style="font-size: 18px;"></i>' : '<i class="las la-ban text-danger" style="font-size: 18px;"></i>' !!}</th> --}}
                <th class="text-center">Approved Qty</th>
                <th class="text-center">Unit Price
                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})</th>
                <th class="text-center">Item Total
                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})</th>
                @if($systemCurrency->code != ($quotation->exchangeRate ?
                $quotation->exchangeRate->currency->code : ''))
                <th class="text-center">Item Total ({{ $systemCurrency->code }})</th>
                @endif
                @endforeach
            </tr>
        </thead>
        <tbody class="tbody">
            @php
            $total_qty=0;
            @endphp
            @if(isset($quotation->id))
            @foreach($quotation->relQuotationItems as $key=>$item)
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>
                    <a class="text-primary" style="cursor: pointer;" onclick="productLogs('{{ $item->uid }}')">{{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }}</a>
                </td>
                <td>{{ getProductAttributesFaster($requisitionItems->where('uid', $item->uid)->first()) }}</td>
                <td>{{ $item->description }}</td>
                <td>{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}
                </td>
                <td class="text-center">{{$item->qty}}</td>

                @foreach($quotations as $key=>$quotation)
                {{-- <td class="text-center">
                    {!! $quotationInfo[$quotation->id]['items'][$item->uid]['is_approved'] == 'approved' ? '<i class="las la-check-square text-success" style="font-size: 18px;"></i>' : '<i class="las la-ban text-danger" style="font-size: 18px;"></i>' !!}
                </td> --}}
                <td class="text-center {{ $quotationInfo[$quotation->id]['items'][$item->uid]['approved_qty'] > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $quotationInfo[$quotation->id]['items'][$item->uid]['approved_qty'] }}
                </td>
                <td class="text-right">
                    {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['unit_price'] ,) }}
                </td>
                <td class="text-right">
                    {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['sub_total'] ,2) }}
                </td>
                @if($systemCurrency->code != ($quotation->exchangeRate ?
                $quotation->exchangeRate->currency->code : ''))
                <td class="text-right">
                    {{ number_format($quotationInfo[$quotation->id]['items'][$item->uid]['exchange_sub_total'] ,2) }}
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
                <td colspan="5" class="text-right"><strong>Total</strong></td>
                <td class="text-center"><strong>{{$total_qty}}</strong></td>
                @foreach($quotations as $key=>$quotation)
                <td colspan="2"><strong>Sub Total</strong></td>
                <td class="text-right">
                    <strong>{{ number_format($quotationInfo[$quotation->id]['sub_total'], 2) }}</strong>
                </td>
                @if($systemCurrency->code != ($quotation->exchangeRate ?
                $quotation->exchangeRate->currency->code : ''))
                <td class="text-right">
                    <strong>{{ number_format($quotationInfo[$quotation->id]['exchange_sub_total'], 2) }}</strong>
                </td>
                @endif
                @endforeach
            </tr>

            <tr>
                <td colspan="6" class="text-right"></td>

                @foreach($quotations as $key=>$quotation)
                <td colspan="2"><strong>(-) Discount</strong></td>
                <td class="text-right">
                    <strong>{{ number_format($quotationInfo[$quotation->id]['discount'], 2) }}</strong>
                </td>
                @if($systemCurrency->code != ($quotation->exchangeRate ?
                $quotation->exchangeRate->currency->code : ''))
                <td class="text-right">
                    <strong>{{ number_format($quotationInfo[$quotation->id]['exchange_discount'], 2) }}</strong>
                </td>
                @endif
                @endforeach
            </tr>

            <tr>
                <td colspan="6" class="text-right"></td>

                @foreach($quotations as $key=>$quotation)
                <td colspan="2"><strong>(+) VAT ({{ ucwords($quotation->relQuotationItems->first()->vat_type) }}{{ $quotation->relQuotationItems->first()->vat_type != 'exempted' ? ', '.$quotation->relQuotationItems->first()->vat_percentage.'%' : '' }})</strong></td>
                <td class="text-right">
                    <strong>{{ number_format($quotationInfo[$quotation->id]['vat'], 2) }}</strong>
                </td>
                @if($systemCurrency->code != ($quotation->exchangeRate ?
                $quotation->exchangeRate->currency->code : ''))
                <td class="text-right">
                    <strong>{{ number_format($quotationInfo[$quotation->id]['exchange_vat'], 2) }}</strong>
                </td>
                @endif
                @endforeach
            </tr>
            <tr>
                <td colspan="6" class="text-right"></td>

                @foreach($quotations as $key=>$quotation)
                <td colspan="2"><strong>Gross Total</strong></td>
                <td class="text-right">
                    <strong>{{ number_format($quotationInfo[$quotation->id]['gross'], 2) }}</strong>
                </td>
                @if($systemCurrency->code != ($quotation->exchangeRate ?
                $quotation->exchangeRate->currency->code : ''))
                <td class="text-right">
                    <strong>
                        {{ number_format($quotationInfo[$quotation->id]['exchange_gross'], 2) }}
                    </strong>
                </td>
                @endif
                @endforeach
            </tr>

            <tr>
                <td colspan="6"></td>
                @foreach($quotations as $key=>$quotation)
                <td colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}"
                    class="text-left">
                    Payment Term:
                    <strong>{{ makePaymentTermsString($quotation->supplier_payment_terms_id) }})</strong>
                </td>
                @endforeach

            </tr>
            <tr>
                <td colspan="6" class="text-right"></td>
                @foreach($quotations as $key => $quotation)
                <td
                    colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                    Delivery Date: <span><strong>{!! date('d M Y', strtotime($quotation->delivery_date))
                            !!}</strong></span></td>
                @endforeach
            </tr>
            <tr>
                <td colspan="6" class="text-right">Notes</td>
                @foreach($quotations as $key => $quotation)
                <td
                    colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                    <span><strong>@if(!empty($quotation->note)) {{ $quotation->creator->name }}:
                            @endif</strong> {!! $quotation->note !!} </span>
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 10%" class="text-center">Priority</th>
                <th style="width: 55%">Approver</th>
                <th style="width: 15%" class="text-center">Response</th>
                <th style="width: 20%" class="text-center">Approved at</th>
            </tr>
        </thead>
        <tbody>
            
            @if(isset($quotations->first()->relRequestProposal->approvals[0]))
            <tr>
                <td class="text-center"></td>
                <td>CS Raised at</td>
                <td class="text-center">Raised</td>
                <td class="text-center">{{ date('Y-m-d g:i a', strtotime($quotations->first()->relRequestProposal->approvals->first()->created_at)) }}</td>
            </tr>
            @foreach($quotations->first()->relRequestProposal->approvals as $key => $approval)
            <tr>
                <td class="text-center">{{ $approval->priority }}</td>
                <td>{{ $approval->user->name }}</td>
                <td class="text-center">{{ ucwords($approval->response) }}</td>
                <td class="text-center">{{ date('Y-m-d g:i a', strtotime($approval->updated_at)) }}</td>
            </tr>
            @endforeach
            @endif
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

@endsection

@section('page-script')
    <script type="text/javascript">
        function productLogs(uid) {
            $.dialog({
                title: 'Product CS logs',
                content: "url:"+location.href+"?get-product-logs&request_proposal_id={{ $purchaseOrderId }}&uid="+uid,
                animation: 'scale',
                columnClass: 'col-md-12',
                closeAnimation: 'scale',
                backgroundDismiss: true,
            });
        }
    </script>


</div>