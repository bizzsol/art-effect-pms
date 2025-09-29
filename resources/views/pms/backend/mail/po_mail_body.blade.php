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
<p style="font-size:16px;font-weight:500">Greetings from {{ucfirst(optional($purchaseOrder->Unit)->hr_unit_name)}}!!</p>

@if($flag == 'confirmed')
    <p style="font-size:16px;font-weight:500">Please see the attached PO and take necessary steps from your end. If you
        have
        any query please contact with us.</p>
@elseif($flag=='cancelled')
    <p style="font-size:16px;font-weight:500">We would like to inform you that the Purchase Order
        <strong>{{$purchaseOrder->po_no}}</strong> has been cancelled. If you have any query please contact with us.
    </p>
@elseif($flag=='restore')
    <p style="font-size:16px;font-weight:500">Please see the attached Revised PO and take necessary steps from your end.
        If you
        have
        any query please contact with us.</p>
@endif
<p style="font-size:16px;font-weight:500">For Billing Issue- </p>
<p style="font-size:16px;font-weight:500">Contact Person: Mr. Ahsanul Haque Punam </p>
<p style="font-size:16px;font-weight:500">Contact Number: 01718410996 </p>
<p style="font-size:16px;font-weight:500">Yours truly,</p>
<p style="font-size:16px">{{ auth()->user()->name }}</p>

<p style="margin-top:10px"></p>
</body>
</html>