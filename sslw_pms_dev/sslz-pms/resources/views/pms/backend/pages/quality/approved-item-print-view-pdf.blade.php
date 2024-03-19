@php
$corporateAddress = \App\Models\PmsModels\SupplierAddress::where(['supplier_id' => isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->id) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'corporate'])->first();
$contactPersonSales = \App\Models\PmsModels\SupplierContactPerson::where(['supplier_id' => isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->id) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'sales'])->first();
@endphp
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

				background: url('assets/idcard/letterhead/{{ getUnitCode(isset($quotation->relPurchaseOrder->id) ? $quotation->relPurchaseOrder->id : 0) }}.png)no-repeat 0 0; background-image-resize: 6;
			}

			html, body, p  {
				font-size:  12px !important;
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

			.text-right{
				text-align:  right;
			}
			.text-center{
				text-align:  center;
			}			
		</style>	
	</head>
	
	<body>
		<htmlpageheader name="page-header">
			<div class="row mb-3 print-header">
				<div class="col-md-6" style="width: 50%;float:left;padding-top: 135px">
					<h2><strong>{{ $title }}</strong></h2>
				</div>
				{{-- <div class="col-md-6 text-right" style="width: 50%;float:left;padding-top: 50px">
					@if(!empty($purchaseOrder->Unit->hr_unit_logo) && file_exists(public_path($purchaseOrder->Unit->hr_unit_logo)))
						<img src="{{ str_replace('/assets','assets', $purchaseOrder->Unit->hr_unit_logo) }}" alt="logo" style="float: right !important;height: 15mm; width:  35mm; margin: 0;" />
					@endif
				</div> --}}
			</div>
		</htmlpageheader>

		<htmlpagefooter name="page-footer">

			<table class="table-bordered">
				<tbody>
					<tr>
						<td colspan="2" style="text-align: center;border: none !important">
							{{ $title }} Printed by <strong>{{ auth()->user()->name  }}</strong>
						</td>
					</tr>
					{{-- <tr>
						<td colspan="2" style="border: none !important">
							<small>(Note: This {{ $title }} doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
						</td>
					</tr> --}}
					{{-- <tr>
						<td style="border-left: none !important;border-bottom: none !important">
							<small>
								Factory: M-19 & M-14, Section-14, Mirpur, Dhaka-1206
								<br>
								Phone: +8809678-411412, Mail: info@mbm.group
							</small>
						</td>
						<td style="padding-left: 25px;border-right: none !important;border-bottom: none !important">
							<small>
								Corporate Office: Plot: 1358, Road: 50 (Old), 9 (New)
								<br>
								Avenue: 11, DOHS, Mirpur-12, Dhaka-1216
								<br>
								Website: www.mbm.group
							</small>
						</td>
					</tr> --}}
					<tr style="border: none !important">
						<td style="height: 50px; !important;border: none !important;border-right: none !important">
							
						</td>
						<td style="height: 50px; !important;border: none !important;border-left: none !important">
							
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: right;border: none !important">
							<small>Page {PAGENO} of {nb}</small>
						</td>
					</tr>
				</tbody>
			</table>
		</htmlpagefooter>
		
		<div class="container">
			<table class="table table-bordered">
				<tbody>
					<tr>
						<td style="width: 50% !important">
							<h5 class="mb-0"><strong>Vendor Name:&nbsp;{{isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->name) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->name : ''}}</strong></h5>
						</td>
						<td style="width: 50% !important;text-align: right !important">
							{!! printBarcode($quotation->relPurchaseOrder->reference_no, 'float: right', '8px') !!}
						</td>
					</tr>
					<tr>
						<td style="width: 50% !important;font-size: 14px !important;">
							Address:&nbsp;
							@if(isset($corporateAddress->id))
							{{ $corporateAddress->village.' '.$corporateAddress->road.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
							<br>
							{{ $corporateAddress->adddress }}
							@endif
						</td>
						<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
							PO Ref. No:&nbsp;{{ isset($quotation->relPurchaseOrder->reference_no)?$quotation->relPurchaseOrder->reference_no:'' }}
							<br>
							PO Date:&nbsp;{{ isset($quotation->relPurchaseOrder->po_date)? date('jS F Y', strtotime($quotation->relPurchaseOrder->po_date)) :'' }}
							<br>
							Quotation Ref. No:&nbsp;{{ isset($quotation->relPurchaseOrder->relQuotation->id) ? $quotation->relPurchaseOrder->relQuotation->reference_no : '' }}
						</td>
					</tr>
					<tr>
						<td style="width: 50% !important;font-size: 14px !important;">
							Attention:&nbsp;
							@if(isset($contactPersonSales->id))
							{{ $contactPersonSales->name.', '.$contactPersonSales->designation }},
							<br>
							Mobile:&nbsp;{{ $contactPersonSales->mobile }},
							<br> 
							Mail:&nbsp;{{ $contactPersonSales->email }}
							@endif
						</td>
						<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
							Delivery Location:&nbsp;
							{{isset($quotation->relPurchaseOrder->Unit->hr_unit_name)?$quotation->relPurchaseOrder->Unit->hr_unit_name:""}}
							<div>
								{!! isset($quotation->relPurchaseOrder->Unit->hr_unit_address)?$quotation->relPurchaseOrder->Unit->hr_unit_address:'' !!}
							</div>
						</td>
					</tr>
					<tr>
						<td style="width: 50% !important;font-size: 14px !important;font-weight: bold !important">
							Payment Mode:&nbsp;<strong>{{ isset($quotation->relPurchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term) ? $quotation->relPurchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term : '' }}</strong>
						</td>
						<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
							Delivery Contact:&nbsp;{!! isset($quotation->relPurchaseOrder->Unit->hr_unit_telephone) ? $quotation->relPurchaseOrder->Unit->hr_unit_telephone : ''!!}
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-bordered">
				<tbody>
					<tr class="text-center">
						<td style="width: 5%"><strong>SL.</strong></td>
						<td style="width: 25%"><strong>Product</strong></td>
						<td style="width: 10%"><strong>UOM</strong></td>
						<td style="width: 15%"><strong>Unit Price ({{ $quotation->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</strong></td>
						<td style="width: 10%"><strong>Qty</strong></td>
						<td style="width: 15%"><strong>Received Qty</strong></td>
						<td style="width: 15%"><strong>Price ({{ $quotation->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</strong></td>
					</tr>
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

						<td>{{isset($item->relGoodsReceivedItems->relProduct->name)?$item->relGoodsReceivedItems->relProduct->name:''}} {{ getProductAttributes($item->relGoodsReceivedItems->relProduct->id) }}</td>
						<td>{{isset($item->relGoodsReceivedItems->relProduct->productUnit->unit_name)?$item->relGoodsReceivedItems->relProduct->productUnit->unit_name:''}}</td>
						<td class="text-right">{{number_format($item->unit_amount,2)}}</td>
						<td class="text-center">{{systemDoubleValue($item->relGoodsReceivedItems->qty,0)}}</td>
						<td class="text-center">{{$item->received_qty}}</td>
						<td class="text-right">{{systemDoubleValue($item->unit_amount*$item->received_qty,2)}}</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="3" class="text-right">Total</td>
						<td colspan="" class="text-right">{{isset($approval_list)?systemDoubleValue($approval_list->sum('unit_amount'),2):0}}</td>
						<td colspan="" class="text-center">{{isset($sumOfItemQty)?systemDoubleValue($sumOfItemQty,0):0}}</td>
						<td class="text-center">{{isset($sumOfReceivedtQty)?systemDoubleValue($sumOfReceivedtQty,0):0}}</td>
						<td colspan="" class="text-right">{{isset($approval_list)?systemDoubleValue($sumOfSubtotal):0}}</td>
					</tr>
					
					<tr>
						<td colspan="6" class="text-right">(+) Vat</td>
						<td class="text-right">{{systemDoubleValue($vatAmount,4)}}</td>
					</tr>
					<tr>
						<td colspan="6" class="text-right"><strong>Total Amount</strong></td>
						<td class="text-right"><strong>{{systemDoubleValue(($sumOfSubtotal-$discountAmount)+$vatAmount,4)}}</strong></td>
					</tr>
					@else
					<tr>
						<td colspan="6" class="text-right">No Data Found</td>
					</tr>
					@endif
				</tbody>
			</table>

			{{-- <h4><strong>Remarks</strong></h4>
			<div>{!! isset($quotation->relPurchaseOrder->remarks)?$quotation->relPurchaseOrder->remarks:'' !!}</div>

			<h4><strong>Terms & Conditions</strong></h4>
			<div>{!! isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->term_condition) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->term_condition : '' !!}</div> --}}
		</div>
	</body>
</html>																																																								