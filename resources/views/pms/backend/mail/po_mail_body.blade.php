<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{$title}}</title>
</head>
<body>
	<p style="font-size:16px;font-weight:500">Dear {{ $purchaseOrder->relQuotation->relSuppliers->name}},</p>
{{--	<p style="font-size:16px;font-weight:500">You receive an order against this quotation number ({{$purchaseOrder->relQuotation->reference_no}}) from MBM group, PO reference is  {{$purchaseOrder->reference_no}}.</p>--}}
	<p style="font-size:16px;font-weight:500">Greetings from SSL Wireless!!!</p>
	<p style="font-size:16px;font-weight:500">Please see the attached PO and take necessary steps from your end. If you have any query please contact with us.</p>
	<p style="font-size:16px;font-weight:500">For Billing Issue:
		Contact Person:  Mr. Ahsanul Haque Punam
		Contact Number: 01718410996
	</p>
	<p style="font-size:16px;font-weight:500">Yours truly,</p>
	<p style="font-size:14px">SSLZ.</p>
	<p style="margin-top:10px"></p>
</body>
</html>