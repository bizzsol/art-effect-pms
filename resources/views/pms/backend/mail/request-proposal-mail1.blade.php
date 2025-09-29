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
        .invoiceBody{
            margin-top:10px;
            background:#eee;
            padding: 20px;
            padding-left: 30px;
        }

        @page {
            margin-top: 1.45in;
            margin-bottom: 1.25in;
            header: page-header;
            footer: page-footer;

            background: url({{ getUnitPad($unit) }}) no-repeat 0 0;
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
    @if(!$directPurchase)
        {{-- <div class="row mb-3 print-header">
            <div class="col-md-6" style="width: 50%;float:left;padding-top: 135px">
                <h2><strong>Purchase Order</strong></h2>
            </div>
            <div class="col-md-6 text-right" style="width: 50%;float:left;padding-top: 50px">
                @if(!empty($purchaseOrder->Unit->hr_unit_logo) && file_exists(public_path($purchaseOrder->Unit->hr_unit_logo)))
                    <img src="{{ str_replace('/assets','assets', $purchaseOrder->Unit->hr_unit_logo) }}" alt="logo" style="float: right !important;height: 15mm; width:  35mm; margin: 0;" />
                @endif
            </div>
        </div> --}}
    @endif
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
<div id="app">

    <div style="max-width: 1190px;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;">
        <main class="" style="padding-bottom: 0;">
            <div id="main-body">
                <div class="main-content">
                    <div class="main-content-inner">
                        <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                        </div>
                        @if(isset($requestProposal))
                            <div style="display: flex;flex-wrap: wrap;margin-right: -15px;margin-left: -15px;">


                                <div style="flex: 0 0 100%;max-width: 100%; padding-top:30px" id="print_invoice">

                                    <div class="panel panel-body">

                                        <div style="max-width: 100%;">
                                            <div class="invoice-details" style="margin-top:25px;display: flex;flex-wrap: wrap;">
                                                <table  style="width: 100%;margin-bottom: 1rem;color: #212529;border: 1px solid #000;border-collapse: collapse;box-sizing: border-box;isplay: table;border-collapse: separate;box-sizing: border-box;text-indent: initial;border-color: grey;" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 50% !important">
                                                            <strong>Vendor Name:&nbsp;{{isset($supplier->name) ? $supplier->name : ''}}</strong>
                                                        </td>
                                                        <td style="width: 50% !important;text-align: right !important">
                                                            {!! printBarcode($requestProposal->reference_no, 'float: right') !!}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 50% !important;font-size: 14px !important;">
                                                            Address:&nbsp;
                                                            @if(isset($corporateAddress->id))
                                                                {{ $corporateAddress->road.' '.$corporateAddress->village.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
                                                                <br>
                                                                {{ $corporateAddress->adddress }}
                                                            @endif
                                                        </td>
                                                        <td style="width: 50% !important;font-size: 14px !important;text-align: right">
                                                            Date:&nbsp;{{ date('jS F Y', strtotime($requestProposal->request_date)) }}
                                                            <br>
                                                            Reference No:&nbsp;{{ $requestProposal->reference_no }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 100% !important;font-size: 14px !important;" colspan="2">
                                                            Attention:&nbsp;
                                                            @if(isset($contactPersonSales->id))
                                                                {{ $contactPersonSales->name.', '.$contactPersonSales->designation }},
                                                                Mobile:&nbsp;{{ $contactPersonSales->mobile }},
                                                                Mail:&nbsp;{{ $contactPersonSales->email }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div style="display: block;width: 100%;overflow-x: auto;">

                                            <table style="width: 100%;margin-bottom: 1rem;color: #212529;border: 1px solid #000;border-collapse: collapse;box-sizing: border-box;isplay: table;border-collapse: separate;box-sizing: border-box;text-indent: initial;border-color: grey;" cellspacing="0" cellpadding="0" border="1">
                                                <thead style="display: table-header-group;vertical-align: middle;border-color: inherit;">
                                                <tr style="display: table-row;vertical-align: inherit;">
                                                    <th style="padding: 10px 5px">SL</th>
                                                    <th style="padding: 10px 5px">Product</th>
                                                    <th style="padding: 10px 5px">Qty</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($requestProposal->requestProposalDetails as $key=>$value)
                                                    <tr>
                                                        <td>{{$key+1}}</td>
                                                        <td style="text-align:center">{{$value->product->name}}</td>
                                                        <td style="text-align:center">{{$value->request_qty}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="item">
                                                    <th style="padding: 10px 5px" colspan="2" align="right">Total QTY :</th>
                                                    <th style="padding: 10px 5px">{{$requestProposal->requestProposalDetails->sum('request_qty')}}</th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="form-group">
                                            <label for="terms-condition"><strong>Terms & Conditions</strong>:</label>
                                            <div style="padding-left: 20px">{!! isset($supplier->term_condition) ? $supplier->term_condition : '' !!}</div>
                                        </div>

                                        <div class="form-group">
                                            <small>(Note: This Request for Proposal doesn’t require signature as it is automatically generated from SSLZ ERP)</small>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
