<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{$title}}</title>
</head>
<body>
<p style="font-size:16px;font-weight:500">Dear {{ $supplier->name}},</p>
{{--	<p style="font-size:16px;font-weight:500">Hope you are well.You are requested to quote an order from mbm group. The reference number of the order is {{$reference_no}}.</p>--}}
<p style="font-size:16px;font-weight:500">Greetings {{$unit_name!==null? 'From '. ucfirst($unit_name):''}}!!!</p>
<p style="font-size:16px;font-weight:500">Please see the attached requirement file and send us back financial proposal
    by ASAP.</p>

{{--	@if($proposalType=='online')--}}
{{--	<p style="font-size:16px;">--}}
{{--		Below is an online link, if you want you can go to that link and submit the price of the product or you can submit a proposal to us via mail.--}}
{{--	</p>--}}
{{--	<p style="text-align: center;padding-top: 12px;">--}}
{{--		<a href="{{$current_url.'/pms/rfp/online-quotations/'.encrypt($requestProposal->id).'/'.encrypt($supplier->id)}}" style="color: #ffffff;--}}
{{--		background-color: #089bab;text-decoration: none;border-radius: 5px;--}}
{{--		padding: 10px;">Click Here To Submit Quotation</a>--}}
{{--	</p>--}}
{{--	@else--}}
{{--	<p style="padding-top: 12px;">--}}
{{--		Please submit a proposal to us via mail.--}}
{{--	</p>--}}
{{--	@endif--}}

<p style="font-size:16px;font-weight:500">VAT & AIT: As per Govt. rules.</p>
<p style="font-size:16px;font-weight:500">Yours truly,</p>
<p style="font-size:14px">{{ auth()->user()->name }}</p>

	<p style="margin-top:10px"></p>
</body>
</html>