<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{$title}}</title>
        <style>
            @page {
                margin-left: .15in;
                margin-right: .15in;
                margin-top: 1in;
                header: page-header;
                footer: page-footer;

                background: url('assets/idcard/letterhead/{{ getUnitCode(isset($purchaseOrder->id) ? $purchaseOrder->id : 0) }}.png') no-repeat 0 0;
                background-image-resize: 6;
            }
            
            html, body, p  {
                font-size:  10px !important;
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

            .table-no-bordered td {
                border: none !important;
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

            .text-center{
                text-align:  center;
            }
            .text-right{
                text-align:  right;
            }           
        </style>    
    </head>
    
    <body>
        <htmlpageheader name="page-header">
            
        </htmlpageheader>

        <htmlpagefooter name="page-footer">
            
        </htmlpagefooter>
        
        <div class="container">
            <div class="row">
                @if(isset($purchaseOrder->id))
                <div style="width: 100%;float: left;clear: right;margin-bottom: 5;">
                    <table class="table-bordered">
                        <tbody>
                            <tr>
                                <td style="border: 1px solid #ccc">
                                    <table class="table-no-bordered">
                                        <tbody>
                                            <tr>
                                                <td colspan="2" style="text-align: center">
                                                    <h1><strong>MBM ERP - Gate Out</strong></h1>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">PO Reference</td>
                                                <td style="width: 75%">
                                                    {{ $purchaseOrder->reference_no }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">Date</td>
                                                <td style="width: 75%">
                                                    {{ isset($gateOuts[0]) ? date('d-m-Y',strtotime($gateOuts[0]->date)) : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 25%">Supplier</td>
                                                <td style="width: 75%">
                                                    {{isset($purchaseOrder->relQuotation->relSuppliers->name)?$purchaseOrder->relQuotation->relSuppliers->name:''}}
                                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                                    {{isset($purchaseOrder->relQuotation->relSuppliers->email)?$purchaseOrder->relQuotation->relSuppliers->email:''}}
                                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                                    {{isset($purchaseOrder->relQuotation->relSuppliers->phone)?$purchaseOrder->relQuotation->relSuppliers->phone:''}}
                                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                                    {{isset($purchaseOrder->relQuotation->relSuppliers->address)?$purchaseOrder->relQuotation->relSuppliers->address:''}}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table-bordered">
                                        <tbody>
                                            <tr class="text-center">
                                                <td style="width: 70%">
                                                    <strong>Product</strong>
                                                </td>
                                                <td style="width: 15%" class="text-center">
                                                    <strong>UOM</strong>
                                                </td>
                                                <td style="width: 15%" class="text-right">
                                                    <strong>Qty</strong>
                                                </td>
                                            </tr>
                                            @if(isset($gateOuts[0]))
                                            @foreach($gateOuts as $key=>$gateOut)
                                            <tr>
                                                <td>{{isset($gateOut->purchaseReturn->relGoodsReceivedItems->relProduct->name)?$gateOut->purchaseReturn->relGoodsReceivedItems->relProduct->name:''}} ({{ isset($gateOut->purchaseReturn->relGoodsReceivedItems->product_id)? getProductAttributes($gateOut->purchaseReturn->relGoodsReceivedItems->product_id):'' }})</td>
                                                <td class="text-center">{{isset($gateOut->purchaseReturn->relGoodsReceivedItems->relProduct->productUnit->unit_name)?$gateOut->purchaseReturn->relGoodsReceivedItems->relProduct->productUnit->unit_name:''}}</td>
                                                <td class="text-center">{{number_format($gateOut->qty,0)}}</td>
                                            </tr>
                                            @endforeach
                                            @endif

                                            <tr>
                                                <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                                <td class="text-right"><strong>{{ number_format($gateOuts->sum('qty'), 0) }}</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h6 class="text-center" >Thank you for doing business with us!</h6>
                                    <br>
                                    <center>
                                        {!! printBarcode($batch) !!}
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="width: 1.5%;float: left;clear: right;">&nbsp;</div>
                @endif
            </div>
        </div>
    </body>
</html>                                                                                                                                                                                                                             