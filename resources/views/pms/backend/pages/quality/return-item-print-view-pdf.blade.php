@php
$corporateAddress = \App\Models\PmsModels\SupplierAddress::where(['supplier_id' => isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->id) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'corporate'])->first();
$contactPersonSales = \App\Models\PmsModels\SupplierContactPerson::where(['supplier_id' => isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->id) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'sales'])->first();

$purchaseOrder = $quotation->relPurchaseOrder;
$directPurchase = ($purchaseOrder->relQuotation->type == "direct-purchase" ? true : false);
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

				background: url({{ getUnitPad($purchaseOrder->Unit) }}) no-repeat 0 0;
    			background-image-resize: 6;
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
					<img src="{{ str_replace('/assets','assets', $purchaseOrder->Unit->hr_unit_logo) }}" alt="logo" style="float: right !important;height: 15mm; width:  35mm; margin: 0;" />
				</div> --}}
			</div>
		</htmlpageheader>

		<htmlpagefooter name="page-footer">

			<table class="table-bordered">
				<tbody>
					<tr>
						<td colspan="2" style="text-align: center;border: none !important">
							@if($directPurchase)
								ITO Approved by <strong>{{ getManagerInfo('Management') > 0 ? \App\User::find(getManagerInfo('Management'))->name : ''  }}</strong>
							@else
								PO Issued by <strong>{{ getManagerInfo('Purchase-Department') > 0 ? \App\User::find(getManagerInfo('Purchase-Department'))->name : ''  }}</strong>
							@endif
						</td>
					</tr>
					{{-- <tr>
						<td colspan="2" style="border: none !important">
							<small>(Note: This {{ $directPurchase ? 'Internal Job Order' : 'Purchase Order' }} doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
						</td>
					</tr> --}}
					@if(!$directPurchase)
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
					@endif
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
							@if($directPurchase)
								<h2 class="mb-0"><strong>Internal Job Order</strong></h2>
							@else
								<h5 class="mb-0"><strong>Vendor Name:&nbsp;{{isset($purchaseOrder->relQuotation->relSuppliers->name) ? $purchaseOrder->relQuotation->relSuppliers->name : ''}}</strong></h5>
							@endif
						</td>
						<td style="width: 50% !important;text-align: right !important">
							{!! printBarcode($code, 'float: right !important') !!}
						</td>
					</tr>
					@if($directPurchase)
						<tr>
							<td style="width: 50% !important;font-size: 14px !important;">
								{{ $directPurchase ? 'ITO' : 'PO' }} Ref. No:&nbsp;{{ $purchaseOrder->reference_no }}
								<br>
								{{ $directPurchase ? 'ITO' : 'PO' }} Date:&nbsp;{{ date('jS F Y', strtotime($purchaseOrder->po_date)) }}
								<br>
								{{ $directPurchase ? 'Estimate' : 'Quotation' }} Ref. No:&nbsp;{{ isset($purchaseOrder->relQuotation->id) ? $purchaseOrder->relQuotation->reference_no : '' }}
							</td>
							<td style="width: 50% !important;font-size: 14px !important;text-align:right">
								Delivery Location:&nbsp;
								{{isset($quotation->relPurchaseOrder->Unit->hr_unit_name)?$quotation->relPurchaseOrder->Unit->hr_unit_name:''}}
								<div>
									{!! isset($quotation->relPurchaseOrder->Unit->hr_unit_address)?$quotation->relPurchaseOrder->Unit->hr_unit_address:'' !!}
								</div>
							</td>
						</tr>
					@else
						<tr>
							<td style="width: 50% !important;font-size: 14px !important;">
								Address:&nbsp;
								@if(isset($corporateAddress->id))
								{{ $corporateAddress->village.' '.$corporateAddress->road.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
								<br>
								{{ $corporateAddress->adddress }}
								@endif
							</td>
							<td style="width: 50% !important;font-size: 14px !important; text-align:right">
								PO Ref. No:&nbsp;{{ $purchaseOrder->reference_no }}
								<br>
								PO Date:&nbsp;{{ date('jS F Y', strtotime($purchaseOrder->po_date)) }}
								<br>
								Quotation Ref. No:&nbsp;{{ isset($purchaseOrder->relQuotation->id) ? $purchaseOrder->relQuotation->reference_no : '' }}
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
							<td style="width: 50% !important;font-size: 14px !important; text-align:right">
								Delivery Location:&nbsp;
								{{$purchaseOrder->Unit->hr_unit_name}}
								<div>
									{!! $purchaseOrder->Unit->hr_unit_address?$purchaseOrder->Unit->hr_unit_address:'' !!}
								</div>
							</td>
						</tr>
					@endif
					<tr>
						<td style="width: 50% !important;font-size: 14px !important;font-weight: bold !important">
							Payment Mode:&nbsp;{{ isset($purchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term) ? $purchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term : '' }}
						</td>
						<td style="width: 50% !important;font-size: 14px !important;text-align:right">
							Delivery Contact:&nbsp;{!! isset($quotation->relPurchaseOrder->Unit->hr_unit_telephone) ? $quotation->relPurchaseOrder->Unit->hr_unit_telephone : ''!!}
						</td>
					</tr>
				</tbody>
			</table>

			<table class="table table-bordered">
				<tbody>
					<tr>
						<td style="text-align:center"><strong>Sl.</strong></td>
						<td style="text-align:center"><strong>Product</strong></td>
						<td style="text-align:center"><strong>UOM</strong></td>
						<td><strong>Unit Price ({{ $quotation->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</strong></td>
						<td style="text-align:center"><strong>Qty</strong></td>
						<td><strong>Received Qty</strong></td>
						<td><strong>Return Qty</strong></td>
						<td style="text-align:center"><strong>Price ({{ $quotation->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</strong></td>
					</tr>
					@php 
					$sumOfReceivedtQty=0;
					$sumOfReturntQty=0;
					$sumOfItemQty=0;
					$sumOfSubtotal=0;

					$discountAmount= 0;
					$vatAmount= 0;
					$returnRemarks = '';
					$returnFaq = '';
					@endphp
					@if(isset($returned))
					@foreach($returned as $key=>$item)
					@php
					$received_qty = $item->relGoodsReceivedItems->received_qty;

					$sumOfReceivedtQty +=($received_qty);
					$sumOfItemQty +=($item->relGoodsReceivedItems->qty);
					$sumOfReturntQty +=($item->return_qty);
					$sumOfSubtotal += $item->relGoodsReceivedItems->unit_amount*($item->return_qty);

					$discountAmount +=($item->relGoodsReceivedItems->discount);

					$vatAmount +=($item->relGoodsReceivedItems->vat);

					$returnRemarks= $item->relGoodsReceivedItems->relPurchaseOrderReturns;
					$returnFaq = $item->relGoodsReceivedItems->returnChangeFaq;
					@endphp

					<tr id="removeApprovedRow{{$item->id}}">
						<td style="text-align:center">{{$key+1}}</td>
						
						<td>{{isset($item->relGoodsReceivedItems->relProduct->name)?$item->relGoodsReceivedItems->relProduct->name:''}} {{ getProductAttributes($item->relGoodsReceivedItems->relProduct->id) }}</td>
						<td>{{isset($item->relGoodsReceivedItems->relProduct->productUnit->unit_name)?$item->relGoodsReceivedItems->relProduct->productUnit->unit_name:''}}</td>
						<td style="text-align:right">{{$item->relGoodsReceivedItems->unit_amount}}</td>
						<td style="text-align:center">{{systemDoubleValue($item->relGoodsReceivedItems->qty,0)}}</td>
						<td style="text-align:center">{{$received_qty}}</td>
						<td style="text-align:center">{{$item->return_qty}}</td>
						<td style="text-align:right">{{systemMoneyFormat($item->relGoodsReceivedItems->unit_amount*($item->return_qty))}}</td>
					</tr>
					@endforeach
					<tr>
						<td colspan="4" style="text-align:right">Total</td>
						<td style="text-align:center">{{isset($sumOfItemQty)?systemDoubleValue($sumOfItemQty,0):0}}</td>
						<td style="text-align:center">{{isset($sumOfReceivedtQty)?systemDoubleValue($sumOfReceivedtQty,0):0}}</td>
						<td style="text-align:center">{{isset($sumOfReturntQty)?systemDoubleValue($sumOfReturntQty,0):0}}</td>
						<td style="text-align:right">{{isset($returned)?systemMoneyFormat($sumOfSubtotal):0}}</td>
					</tr>
					{{-- <tr>
						<td colspan="7" style="text-align:right">(-) Discount</td>
						<td><?= systemDoubleValue($discountAmount,2)?></td>
					</tr> --}}
					<tr>
						<td colspan="7" style="text-align:right">(+) Vat</td>
						<td style="text-align:right">{{systemMoneyFormat($vatAmount)}}</td>
					</tr>
					<tr>
						<td colspan="7" style="text-align:right"><strong>Total Amount</strong></td>
						<td style="text-align:right"><strong>{{systemMoneyFormat(($sumOfSubtotal-$discountAmount)+$vatAmount)}}</strong></td>
					</tr>
					@else
					<tr>
						<td colspan="8" style="text-align:right">No Data Found</td>
					</tr>
					@endif

				</tbody>
			</table>
			<strong>In word</strong>: <strong>{{ inWordBn((($sumOfSubtotal-$discountAmount)+$vatAmount), $purchaseOrder->relQuotation->exchangeRate->currency->name, $purchaseOrder->relQuotation->exchangeRate->currency->hundreds) }} only</strong>
			<br>
			<br>

			<strong>Remarks:</strong>
			<div>
				{{-- {!! $purchaseOrder->remarks !!} --}}
				{{isset($returnRemarks[0]->return_note)?$returnRemarks[0]->return_note:''}}
			</div>
			<br>
			<br>
			@if(count((array)$returnFaq)>0)
			<strong>Quality Issues:</strong>
			<div>
				
				<ul>
					@foreach($returnFaq as $data)
					<li>{{isset($data->faq->name)?$data->faq->name:''}}</li>
					@endforeach
				</ul>

			</div>
			@endif
			
			@if(!$directPurchase)
				{{-- <strong>Terms & Conditions:</strong>
				<div>
					{!! isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->term_condition) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->term_condition : '' !!}
				</div> --}}
			@endif
		</div>
	</body>
</html>																																																								