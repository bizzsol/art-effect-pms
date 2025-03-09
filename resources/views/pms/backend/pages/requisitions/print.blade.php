<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin-top: 1.25in;
            margin-bottom: 1.25in;
            header: page-header;
            footer: page-footer;

            background: url({{ getUnitPad($requisition->Unit) }}) no-repeat 0 0;
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
                <td colspan="9" class="text-center">
                    <h3>{{ $title }}</h3>
                </td>
            </tr>
            <tr>
                <td style="width: 10% !important">
                    <strong>Name</strong>
                </td>
                <td style="width: 4% !important" class="text-center">
                    <strong>:</strong>
                </td>
                <td style="width: 20% !important">
                    {{ isset($requisition->relUsersList->name) ? $requisition->relUsersList->name : '' }}
                </td>

                <td style="width: 10% !important">
                    <strong>Unit</strong>
                </td>
                <td style="width: 3% !important" class="text-center">
                    <strong>:</strong>
                </td>
                <td style="width: 20% !important">
                    {{ $requisition->unit->hr_unit_short_name }}
                </td>

                <td style="width: 10% !important">
                    <strong>Location</strong>
                </td>
                <td style="width: 3% !important" class="text-center">
                    <strong>:</strong>
                </td>
                <td style="width: 20% !important">
                    {{ isset($requisition->relUsersList->employee->location->hr_location_name) ? $requisition->relUsersList->employee->location->hr_location_name : '' }}
                </td>
            </tr>
            <tr>
                <td style="width: 10% !important">
                    <strong>Department</strong>
                </td>
                <td style="width: 4% !important" class="text-center">
                    <strong>:</strong>
                </td>
                <td style="width: 20% !important">
                    {{ isset($requisition->relUsersList->employee->department->hr_department_name) ? $requisition->relUsersList->employee->department->hr_department_name : '' }}
                </td>

                <td style="width: 10% !important">
                    <strong>Date</strong>
                </td>
                <td style="width: 3% !important" class="text-center">
                    <strong>:</strong>
                </td>
                <td style="width: 20% !important">
                    {{ date('d-m-Y', strtotime($requisition->requisition_date)) }}
                </td>

                <td style="width: 10% !important">
                    <strong>Saleable</strong>
                </td>
                <td style="width: 3% !important" class="text-center">
                    <strong>:</strong>
                </td>
                <td style="width: 20% !important">
                    {{ ucwords($requisition->saleable) }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
            <tr>
                <td><strong>SL<strong></td>
                <td><strong>Category<strong></td>
                <td><strong>Product<strong></td>
                <td><strong>Attributes<strong></td>
                <td><strong>UOM<strong></td>
                <td><strong>Requisition Qty<strong></td>
                <td><strong>Approved Qty<strong></td>
                <td class="text-right"><strong>Budgeted Price<strong></td>
                <td class="text-right"><strong>Estimated Amount<strong></td>
            </tr>
            @php
                $totalEstimation = 0;
            @endphp
            @if(isset($requisition->items[0]))
            @foreach($requisition->items as $key => $item)
            @php
                $totalEstimation += ($item->unit_price*($requisition->status == 1 ? $item->qty : $item->requisition_qty));
            @endphp
                <tr>
                    <td class="text-center">
                        {{ $key+1 }}
                    </td>
                    <td>
                        {{ isset($item->product->category->name) ? $item->product->category->name : '' }}
                    </td>
                    <td>
                        {{ isset($item->product->name) ? $item->product->name : '' }}
                        ({{isset($item->product->sku) ? $item->product->sku : '' }}) {{ getProductAttributesFaster($item->product) }}
                    </td>
                    <td>
                        {{ getProductAttributesFaster($item) }}
                    </td>
                    <td>
                        {{ isset($item->product->productUnit->unit_name) ? $item->product->productUnit->unit_name : '' }}
                    </td>
                    <td class="text-center">
                        {{ number_format($item->requisition_qty, 0) }}
                    </td>
                    <td class="text-center">
                        {{ $item->qty }}
                    </td>
                    <td class="text-right">
                        {{ systemMoneyFormat($item->unit_price) }}
                    </td>
                    <td class="text-right">
                        {{ systemMoneyFormat($item->unit_price*($requisition->status == 1 ? $item->qty : $item->requisition_qty)) }}
                    </td>
                </tr>
            @endforeach
            @endif

            <tr>
                <td colspan="8" class="text-right">
                    <strong>Estimated Total Amount:</strong>
                </td>
                <td class="text-right">
                    {{ systemMoneyFormat($totalEstimation) }}
                </td>
            </tr>
            <tr>
                <td colspan="9">
                    <strong>Explanations:</strong>
                    <br>
                    {{ !empty($requisition->explanations) ? $requisition->explanations : '' }}
                </td>
            </tr>
            <tr>
                <td colspan="9">
                    <strong>Notes:</strong>
                    <br>
                    {{ $requisition->remarks }}
                </td>
            </tr>

            @if($requisition->status == 2 && !empty($requisition->admin_remark))
            <tr>
                <td colspan="9">
                    <strong>Holding Reason:</strong>
                    <br>
                    {{ $requisition->admin_remark }}
                </td>
            </tr>
            @endif

        </tbody>
    </table>
</div>
</body>
</html>