<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin-top: 1.85in;
            margin-bottom: 1.25in;
            header: page-header;
            footer: page-footer;
            background: url('assets/idcard/letterhead/{{ $quotations[0]->relRequestProposal->requestProposalRequisition[0]->relRequisition->Unit->hr_unit_short_name }}.png') no-repeat 0 0;
            background-image-resize: 6;
        }

        html, body, p {
            font-size: 12px !important;
            color: #000000;
        }

        table {
            width: 100% !important;
            border-spacing: 0px !important;
            margin-top: 10px !important;
            margin-bottom: 15px !important;
        }

        table caption {
            color: #000000 !important;
        }

        table, td {
            padding-top: 1px !important;
            padding-bottom: 1px !important;
            padding-left: 7px !important;
            padding-right: 7px !important;
        }

        .table-non-bordered {
            padding-left: 0px !important;
        }

        .table-bordered {
            border-collapse: collapse;
        }

        .table-bordered td {
            border: 1px solid #000000;
            padding: 5px;
        }

        .table-bordered tr:first-child td {
            border-top: 0;
        }

        .table-bordered tr td:first-child {
            border-left: 0;
        }

        .table-bordered tr:last-child td {
            border-bottom: 0;
        }

        .table-bordered tr td:last-child {
            border-right: 0;
        }

        .table-bordered th {
            border: 1px solid #000000;
            padding: 5px;
        }

        .table-bordered tr:first-child th {
            border-top: 0;
        }

        .table-bordered tr th:first-child {
            border-left: 0;
        }

        .table-bordered tr:last-child th {
            border-bottom: 0;
        }

        .table-bordered tr th:last-child {
            border-right: 0;
        }

        .mt-0 {
            margin-top: 0;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .image-space {
            white-space: wrap !important;
            padding-top: 45px !important;
        }

        .break-before {
            page-break-before: always;
            break-before: always;
        }

        .break-after {
            break-after: always;
        }

        .break-inside {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .break-inside-auto {
            page-break-inside: auto;
            break-inside: auto;
        }

        .space-top {
            margin-top: 10px;
        }

        .space-bottom {
            margin-bottom: 10px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .invoiceBody {
            margin-top: 10px;
            background: #eee;
            padding: 10px;
        }

        .panel {
            background: #fff;
            border-radius: 15px;
            margin-bottom: 1rem;
        }

        .panel-body {
            padding: 15px 20px;
        }

        .ratings {
            margin-right: 10px;
        }

        .text-dark {
            color: #3f414d !important;
        }

        .list-unstyled {
            padding-left: 0;
            list-style: none;
        }
    </style>
</head>
<body>
<htmlpageheader name="page-header">
</htmlpageheader>
<htmlpagefooter name="page-footer">
    <table class="table-bordered">
        <tbody>
        <tr>
            <td colspan="2" style="text-align: right;border: none !important;">
                <small>Page {PAGENO} of {nb}</small>
            </td>
        </tr>
        </tbody>
    </table>
</htmlpagefooter>


@if($quotations)
    <div class="row">
        <div class="col-md-12">
            <h2><strong>Compare Statement View</strong></h2>
            <div class="panel panel-info">
                <div class="col-lg-12 invoiceBody" style="width:100%">
                    <div class="invoice-details mt25 row">

                        @foreach($quotations as $key=>$quotation)
                            @if($key==0)
                                <div class="col-md-6 well" style="width: 50%; float:left;padding-top: 10px;">
                                    <ul class="list-unstyled mb0">
                                        <li><strong>{{__('Request Proposal No')}} :</strong>
                                            {{$quotation->relRequestProposal->reference_no}}
                                        </li>
                                        <li><strong>Project Name:</strong></li>
                                    </ul>
                                </div>
                                <div class="col-md-6 well" style="width: 50%; float:right;padding-top: 10px;">
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
                <div class="table-responsive" style="width: auto;">

                    <table class="table table-bordered table-hover">
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
                                <th class="invoiceBody"
                                    colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">

                                    <p class="ratings text-dark">
                                        <a target="_blank"><span>{{$quotation->relSuppliers->name}}
                                            ({{$quotation->relSuppliers->code}})</span></a>
                                        @if(in_array($quotation->type,['manual','online']))
                                            ({!!ratingGenerate($totalScore,$totalCount,true)!!})
                                        @endif
                                    </p>

                                    <p class="text-dark"><strong>{{__('Q:Ref:No')}}
                                            :</strong> {{$quotation->reference_no}}
                                    </p>
                                </th>
                            @endforeach
                        </tr>
                        <tr class="text-center">
                            <th style="width: 35px">SL</th>
                            <th style="width: 100px">Product</th>
                            <th style="width: 300px">Attributes</th>
                            <th style="width: 10px">Description</th>
                            <th style="width: 85px">UOM</th>
                            <th style="width: 65px">Qty</th>
                            @foreach($quotations as $quotation)
                                {{-- <td class="text-center" style="width: 30px">
                                    @if($quotation->is_approved == 'approved')
                                        <svg width="15" height="15" fill="green" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 448 512">
                                            <path d="M400 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zm0 400H48V80h352v352zm-35.864-241.724L191.547 361.48c-4.705 4.667-12.303 4.637-16.97-.068l-90.781-91.516c-4.667-4.705-4.637-12.303.069-16.971l22.719-22.536c4.705-4.667 12.303-4.637 16.97.069l59.792 60.277 141.352-140.216c4.705-4.667 12.303-4.637 16.97.068l22.536 22.718c4.667 4.706 4.637 12.304-.068 16.971z"/>
                                        </svg>
                                    @else
                                        <svg width="15" height="15" fill="red" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 512 512">
                                            <path d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z"/>
                                        </svg>
                                    @endif
                                </td> --}}
                                <td class="text-center" style="width: 30px">
                                    <strong><small>Approved Qty</small></strong>
                                </td>
                                <th class="text-center" style="min-width: 150px">Unit Price
                                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})
                                </th>
                                <th class="text-center" style="min-width: 185px">Item Total
                                    ({{ $quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '' }})
                                </th>
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
                                    <td>{{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributesFaster($item->relProduct) }}</td>
                                    <td>{{ getProductAttributesFaster($requisitionItems->where('product_id', $item->product_id)->first()) }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-center">{{isset($item->relProduct->productUnit->unit_name)?$item->relProduct->productUnit->unit_name:''}}
                                    </td>
                                    <td class="text-center">{{$item->approved_qty}}</td>

                                    @foreach($quotations as $key=>$quotation)
                                        {{-- <td class="text-center">
                                            @if($quotation->relQuotationItems->where('product_id', $item->product_id)->first()->is_approved == 'approved')
                                                <svg width="15" height="15" fill="green"
                                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <path d="M400 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zm0 400H48V80h352v352zm-35.864-241.724L191.547 361.48c-4.705 4.667-12.303 4.637-16.97-.068l-90.781-91.516c-4.667-4.705-4.637-12.303.069-16.971l22.719-22.536c4.705-4.667 12.303-4.637 16.97.069l59.792 60.277 141.352-140.216c4.705-4.667 12.303-4.637 16.97.068l22.536 22.718c4.667 4.706 4.637 12.304-.068 16.971z"/>
                                                </svg>
                                            @else
                                                <svg width="15" height="15" fill="red"
                                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                    <path d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z"/>
                                                </svg>
                                            @endif
                                        </td> --}}
                                        <td class="text-center {{ $quotationInfo[$quotation->id]['items'][$item->product_id]['approved_qty'] > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $quotationInfo[$quotation->id]['items'][$item->product_id]['approved_qty'] }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($quotationInfo[$quotation->id]['items'][$item->product_id]['unit_price'] ,) }}
                                        </td>
                                        <td class="text-right">
                                            {{ number_format($quotationInfo[$quotation->id]['items'][$item->product_id]['sub_total'] ,2) }}
                                        </td>
                                        @if($systemCurrency->code != ($quotation->exchangeRate ?
                                        $quotation->exchangeRate->currency->code : ''))
                                        <td class="text-right">
                                            {{ number_format($quotationInfo[$quotation->id]['items'][$item->product_id]['exchange_sub_total'] ,2) }}
                                        </td>
                                        @endif
                                    @endforeach

                                </tr>
                                @php
                                    $total_qty += $item->approved_qty;
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
                                <td colspan="2"><strong>(+) Vat </strong></td>
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
                                    <strong>{{ makePaymentTermsString($quotation->supplier_payment_terms_id) }}
                                        )</strong>
                                </td>
                            @endforeach

                        </tr>
                        <tr>
                            <td colspan="6" class="text-right"></td>
                            @foreach($quotations as $key => $quotation)
                                <td colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}">
                                    Delivery Date: <span><strong>{!! date('d M Y', strtotime($quotation->delivery_date)) !!}</strong></span></td>
                            @endforeach
                        </tr>
                        <tr>
                            <td colspan="6" class="text-right">Notes</td>
                            @foreach($quotations as $key => $quotation)
                                <td colspan="{{ $systemCurrency->code != ($quotation->exchangeRate ? $quotation->exchangeRate->currency->code : '') ? 4 : 3 }}"><span><strong>@if(!empty($quotation->note)) {{ $quotation->creator->name }}: @endif</strong> {!! $quotation->note !!} </span>
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
</body>