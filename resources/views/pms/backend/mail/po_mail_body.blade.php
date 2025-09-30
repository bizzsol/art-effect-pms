@php
    use App\User;
@endphp

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Purchase Order Notification' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height:1.5; color:#333;">

<p style="font-size:16px; font-weight:500;">
    Dear {{ $purchaseOrder->relQuotation->relSuppliers->name }},
</p>

<p style="font-size:16px; font-weight:500;">
    Greetings from {{ ucwords((optional($purchaseOrder->Unit)->hr_unit_name)) }}!!
</p>

@if($flag === 'confirmed')
    <p style="font-size:16px; font-weight:500;">
        Please see the attached PO and take necessary steps from your end.
        If you have any query, please contact us.
    </p>
@elseif($flag === 'cancelled')
    <p style="font-size:16px; font-weight:500;">
        We would like to inform you that the Purchase Order
        <strong>{{ $purchaseOrder->po_no }}</strong> has been <strong>cancelled</strong>.
        If you have any query, please contact us.
    </p>
@elseif($flag === 'restore')
    <p style="font-size:16px; font-weight:500;">
        Please see the attached <strong>Revised PO</strong> and take necessary steps from your end.
        If you have any query, please contact us.
    </p>
@endif
<br>
<br>
{{-- Billing Contact --}}
@if(optional($purchaseOrder->Unit->contactPerson))
    <p style="font-size:16px; font-weight:500; margin-top:15px;">For Billing Issues:</p>
    <p style="font-size:14px; font-weight:500;">
        Contact Person: {{ $purchaseOrder->Unit->contactPerson->name }} <br>
        Designation: {{ $purchaseOrder->Unit->contactPerson->employee->designation->hr_designation_name ?? 'N/A' }}<br>
        Email: {{ $purchaseOrder->Unit->contactPerson->email }}<br>
        Contact Number: {{ $purchaseOrder->Unit->contactPerson->phone ?? 'N/A' }}
    </p>
@endif

<p style="font-size:14px; font-weight:500;">
    BIN No: {{ optional($purchaseOrder->Unit)->bin_number ?? 'N/A' }}
</p>

<br>
<br>


<p style="font-size:16px; font-weight:500; margin-top:20px;">Yours truly,</p>

{{-- Logged-in User Info --}}
@if(auth()->check())
    @php
        $sender = User::find(auth()->id());
    @endphp
    <p style="font-size:14px;">
        {{ $sender->name }} <br>
        {{optional($sender->employee->designation)->hr_designation_name }} <br>
        {{ $sender->email }}<br>
        {{ $sender->phone}}
    </p>
@else
    <p style="font-size:16px;">System User</p>
@endif


</body>
</html>
