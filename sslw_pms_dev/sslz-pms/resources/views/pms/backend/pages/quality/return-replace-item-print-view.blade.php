@php
$corporateAddress = \App\Models\PmsModels\SupplierAddress::where(['supplier_id' => isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->id) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'corporate'])->first();
$contactPersonSales = \App\Models\PmsModels\SupplierContactPerson::where(['supplier_id' => isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->id) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->id : 0, 'type' => 'sales'])->first();
@endphp

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{$title}}</title>
	<link rel="shortcut icon" href="{{ asset('images/mbm.ico')}} " />
	<link href="{{ asset('css/app.css') }}" rel="stylesheet" media='screen,print'>
	<link rel="stylesheet" href="{{ asset('assets/css/all.css') }}" media='screen,print'>
	@stack('css')
	<link rel="stylesheet" href="{{ asset('assets/css/custom.css?v=1.3') }}" media='screen,print'>
	<!-- Responsive CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" media='screen,print'>
	<style type="text/css">
		@media print {
			.print_the_pages {
				display: none;
			}
		}
		* {
			font-size: 16px !important;
		}
		.list-unstyled .ratings {
			display: none;
		}
	</style>
</head>
<body>
	<div id="app">

		<div id="content-page" class="container">
			<main class="" style="padding-bottom: 0;">
				<div id="main-body" class="">
					<div class="main-content">
						<div class="main-content-inner">
							@if(isset($quotation->relPurchaseOrder->relQuotation))
							<div class="row">
								<div class="col-md-12 p-30" id="print_invoice">

									<div class="panel panel-body">
										<div class="row mb-3">
											<div class="col-md-6 text-left">
												<h3>Return Replace Approved List</h3>
											</div>
											<div class="col-md-6 text-right">
												@if(!empty($quotation->relPurchaseOrder->Unit->hr_unit_logo))
												<img src="{{ asset($quotation->relPurchaseOrder->Unit->hr_unit_logo) }}" alt="" style="height: 100px;width: 250px;">
												@else
												<img src="{{ asset('images/mbm.png') }}" alt="" style="height: 100px;width: 250px;">
												@endif
											</div>
										</div>
										<div class="table-responsive">
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
															{{ $corporateAddress->road.' '.$corporateAddress->village.', '.$corporateAddress->city.'-'.$corporateAddress->zip.', '.$corporateAddress->country }}
															<br>
															{{ $corporateAddress->adddress }}
															@endif
														</td>
														<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
															PO Ref. No:&nbsp;{{ isset($quotation->relPurchaseOrder->reference_no)?$quotation->relPurchaseOrder->reference_no:'' }}
															<br>
															PO Date:&nbsp;{{ date('jS F Y', strtotime($quotation->relPurchaseOrder->po_date)) }}
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
															{{$quotation->relPurchaseOrder->Unit->hr_unit_name}}
															<div>
																{!! isset($quotation->relPurchaseOrder->Unit->hr_unit_address)?$quotation->relPurchaseOrder->Unit->hr_unit_address:'' !!}
															</div>
														</td>
													</tr>
													<tr>
														<td style="width: 50% !important;font-size: 14px !important;font-weight: bold !important">
															Payment Mode:&nbsp;{{ isset($quotation->relPurchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term) ? $quotation->relPurchaseOrder->relQuotation->relSupplierPaymentTerm->relPaymentTerm->term : '' }}
														</td>
														<td style="width: 50% !important;font-size: 14px !important;" class="text-right">
															Delivery Contact:&nbsp;{!! isset($quotation->relPurchaseOrder->Unit->hr_unit_telephone) ? $quotation->relPurchaseOrder->Unit->hr_unit_telephone : ''!!}
														</td>
													</tr>
												</tbody>
											</table>

											<table class="table table-bordered">

											<thead>
												<tr class="text-center">
													<th>SL</th>
													<th>Product</th>
													<th style="width: 12% !important">Unit Price ({{ $quotation->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
													<th>Gate-In Qty</th>
													<th style="width: 15% !important">Received Qty</th>
													<th style="width: 12% !important">Return Qty</th>
													<th>Price ({{ $quotation->relPurchaseOrder->relQuotation->exchangeRate->currency->code }})</th>
												</tr>
											</thead>
											<tbody>

												@php
												$sumOfReceivedtQty=0;
												$sumOfPrice=0;
												$sumOfReturnQty=0;
												$discountAmount= 0;
												$vatAmount= 0;
												@endphp
												@if(isset($returnChangeList))
												@foreach($returnChangeList as $key=>$item)
												@php 
												$sumOfReceivedtQty +=($item->received_qty);
												$returnQty=$item->qty-$item->received_qty;
												$sumOfPrice +=($item->received_qty*$item->unit_amount);
												$sumOfReturnQty +=$returnQty;

												$discountAmount +=($item->discount_percentage * ($item->received_qty*$item->unit_amount))/100;

												$vatAmount +=($item->vat_percentage * ($item->received_qty*$item->unit_amount))/100;

												@endphp

												<tr id="removeApprovedRow{{$item->id}}">
													<td class="text-center">{{$key+1}}</td>
													
													<td>{{isset($item->relProduct->name)?$item->relProduct->name:''}} {{ getProductAttributes($item->relProduct->id) }}</td>
													<td class="text-right">
														{{$item->unit_amount}}
													</td>
													<td class="text-center">{{systemDoubleValue($item->qty,0)}}</td>
													<td class="text-center">{{$item->received_qty}}</td>
													<td class="text-center">{{$returnQty}}</td>
													<td class="text-right">{{systemDoubleValue($item->received_qty*$item->unit_amount,2)}}</td>	
												</tr>
												@endforeach
												<tr>
													<td colspan="3" class="text-right">Total</td>
													<td class="text-center">{{isset($returnChangeList)?systemDoubleValue($returnChangeList->sum('qty'),0):0}}</td>

													<td class="text-center">{{isset($sumOfReceivedtQty)?systemDoubleValue($sumOfReceivedtQty,0):0}}</td>
													<td class="text-center">{{isset($sumOfReturnQty)?$sumOfReturnQty:0}}</td>
													
													<td id="sumOfPrice" class="text-right">{{isset($sumOfPrice)?systemDoubleValue($sumOfPrice,4):0}}</td>

												</tr>
												{{-- <tr>
													<td colspan="6" class="text-right">(-) Discount</td>
													<td><?= systemDoubleValue($discountAmount,2)?></td>
												</tr> --}}
												<tr>
													<td colspan="6" class="text-right">(+) Vat</td>
													<td class="text-right">{{systemDoubleValue($vatAmount,4)}}</td>
												</tr>
												<tr>
													<td colspan="6" class="text-right"><strong>Total Amount</strong></td>
													<td class="text-right"><strong>{{systemDoubleValue(($sumOfPrice-$discountAmount)+$vatAmount,4)}}</strong></td>
												</tr>
												@else
												<tr>
													<td colspan="7" class="text-right">No Data Found</td>
												</tr>
												@endif
											</tbody>
										</table>

									</div>
									<div class="form-group">
											<label for="remarks"><strong>Remarks</strong>:</label>

											<span style="font-size:18px !important">{!! $quotation->relPurchaseOrder->remarks?$quotation->relPurchaseOrder->remarks:'' !!}</span>
										</div>

										<div class="form-group">
											<label for="terms-condition"><strong>Terms & Conditions</strong>:</label>
											<div class="pl-4">{!! isset($quotation->relPurchaseOrder->relQuotation->relSuppliers->term_condition) ? $quotation->relPurchaseOrder->relQuotation->relSuppliers->term_condition : '' !!}</div>
										</div>

										<div class="form-group">
											<small>(Note: This purchase order doesn’t require signature as it is automatically generated from MBM Group’s ERP)</small>
										</div>

										<div class="form-group row pt-2" style="border-top: 3px solid black">
											<div class="col-md-6" style="border-right: 1px solid black">
												Factory: M-19 & M-14, Section-14, Mirpur, Dhaka-1206
												<br>
												Phone: +8809678-411412, Mail: info@mbm.group
											</div>
											<div class="col-md-6 pl-5" style="border-left: 2px solid black">
												Corporate Office: Plot: 1358, Road: 50 (Old), 9 (New)
												<br>
												Avenue: 11, DOHS, Mirpur-12, Dhaka-1216
												<br>
												Website: www.mbm.group
											</div>
										</div>

									</div>
								</div>
								<div class="col-md-12 mb-3">
									<center>
										<a href="#" class="btn btn-info btn-sm print_the_pages text-center">
											<i class="las la-print" aria-hidden="true"></i>
											<span>Print Invoice</span></a>
										</center>
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
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{asset('assets/js/all.js')}}"></script>
<script>
	window.print();
	//window.onload=PrintPage();

	var count = 0;
	var refreshIntervalId =setInterval(function(){
		count++;
		jQuery(document).ready(function() {
			clearInterval(refreshIntervalId);
			jQuery("#load").fadeOut();
			jQuery("#loading").fadeOut("");

		});
		if( count == 5){
			clearInterval(refreshIntervalId);
			jQuery("#load").fadeOut();
			jQuery("#loading").fadeOut("");
		}
	}, 300);

	var loaderContent = '<div class="animationLoading"><div id="container-loader"><div id="one"></div><div id="two"></div><div id="three"></div></div><div id="four"></div><div id="five"></div><div id="six"></div></div>';
	let afterLoader = '<div class="loading-select left"><img src="{{ asset('images/loader.gif')}}" /></div>';

	const PrintPage=()=>{
		$('.print_the_pages').on('click', function () {
			var restorepage = $('body').html();
			var printcontent = $('#print_invoice').clone();
			$('body').empty().html(printcontent);
			window.print();
			$('body').html(restorepage)
			return false;
		}); 
	};

	PrintPage();
	

</script>

</html>