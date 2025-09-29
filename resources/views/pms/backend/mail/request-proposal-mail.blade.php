@php
    $corporateAddress = \App\Models\PmsModels\SupplierAddress::where(['supplier_id' => isset($supplier->id) ? $supplier->id : 0, 'type' => 'corporate'])->first();
    $contactPersonSales = \App\Models\PmsModels\SupplierContactPerson::where(['supplier_id' => isset($supplier->id) ? $supplier->id : 0, 'type' => 'sales'])->first();

    $unit = \App\Models\Hr\Unit::where('hr_unit_name', $unit_name)->first();
@endphp
        <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Request For Proposal Mail</title>
    <style type="text/css">
        .invoiceBody {
            margin-top: 10px;
            background: #eee;
            padding: 20px;
            padding-left: 30px;
        }

        @page {
            margin-top: 1.45in;
            margin-bottom: 1.25in;
            header: page-header;
            footer: page-footer;
            @if($unit && $unit->pad)
 background: url({{ getUnitPad($unit) }}) no-repeat 0 0;
            background-image-resize: 6;
        @endif


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

        table td {
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
                <small>{PAGENO} of {nb}</small>
            </td>
        </tr>
        </tbody>
    </table>
</htmlpagefooter>

<div class="container">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <td colspan="2" class="text-center">
                <h3>Request For Proposal</h3>
            </td>
        </tr>
        <tr>
            <td style="width: 50% !important">
                <strong>
                    Vendor Name:&nbsp;{{isset($supplier->name) ? $supplier->name : ''}}
                </strong>
                <br>
                <p>
                    @if(isset($corporateAddress->id))
                        {{ $corporateAddress->road.' '.$corporateAddress->village.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
                        <br>
                        {{ $corporateAddress->adddress }}
                    @endif
                </p>
                <br>
                <br>
                @if(isset($contactPersonSales->id))
                    {{ $contactPersonSales->name.', '.$contactPersonSales->designation }}<br>
                    Mobile:&nbsp;{{ $contactPersonSales->mobile }}
                    <br>
                    Mail:&nbsp;{{ $contactPersonSales->email }}
                @endif
            </td>
            <td style="width: 50% !important;">
                <div style="text-align: right !important">
                    {!! printBarcode($requestProposal->reference_no, 'float: right') !!}
                </div>
                <br>
                <strong>CS Reference:</strong> {{ $requestProposal->reference_no }}
                <br>
                <strong>Date:</strong> {{ date('d-M-y', strtotime($requestProposal->request_date)) }}
                <br>
                <strong>
                    Contact
                    Person:</strong> {!! isset($requestProposal->createdBy) ?  $requestProposal->createdBy->name.'&nbsp;&nbsp;|&nbsp;&nbsp;'.optional($requestProposal->createdBy->employee->designation)->hr_designation_name.'&nbsp;&nbsp;|&nbsp;&nbsp;'.optional($requestProposal->createdBy)->phone : '' !!}
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered">
        <thead>
        <tr class="text-center">
            <td style="text-align:  center;width: 5% !important"><strong>SL</strong></td>
            <td style="text-align:  center;width: 16% !important"><strong>Product</strong></td>
            <td style="text-align:  center;width: 21% !important"><strong>Description</strong></td>
            <td style="text-align:  center;width: 8% !important"><strong>Qty</strong></td>
        </tr>
        </thead>
        <tbody>
        @foreach($requestProposal->requestProposalDetails as $key=>$value)
            <tr>
                <td class="text-center">{{ $key+1}}</td>
                <td class="text-center">
                    {{$value->product->name}}
                </td>
                <td style="text-align: center">{{ getProductAttributesFaster($value->product) }}</td>
                <td style="text-align: center">{{$value->request_qty}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" class="text-right">
                <strong>Total</strong>
            </td>
            <td class="text-center">
                <strong>{{$requestProposal->requestProposalDetails->sum('request_qty')}}</strong>
            </td>
        </tr>
        </tbody>
    </table>

    @if($supplier->term_condition)
        <h3 class="text-center"><strong>Terms & Conditions</strong></h3>
        <div>
            {!! isset($supplier->term_condition) ? $supplier->term_condition : '' !!}
        </div>
    @endif
    <br>
    <br>
    <small>(Note: This Request for Proposal doesn’t require signature as it is automatically generated from SSLZ
        ERP)</small>
</div>

</body>
</html>
