@php use App\User; @endphp
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

<p style="font-size:16px;font-weight:500">Greetings {{$unit_name!==null? 'From '. ucfirst($unit_name):''}}!!!</p>
<p style="font-size:16px;font-weight:500">Please see the attached requirement file and send us back financial proposal
    by ASAP.</p>

<p style="font-size:16px;font-weight:500">VAT & AIT: As per Govt. rules.</p>

<p style="font-size:16px;font-weight:500">Yours truly,</p>
{{-- Logged-in User Info --}}
@if(auth()->check())
    @php
        $sender = User::find(auth()->id());
    @endphp
    <p style="font-size:16px;">{{ $sender->name }}</p>

    @if(!empty($sender->employee->designation->hr_designation_name))
        <p style="font-size:16px;">
            {{ $sender->employee->designation->hr_designation_name }}
        </p>
    @endif

    @if(!empty($sender->email))
        <p style="font-size:16px;">
            {{ $sender->email }}
        </p>
    @endif

    @if(!empty($sender->phone))
        <p style="font-size:16px;">
            {{ $sender->phone }}
        </p>
    @endif
@else
    <p style="font-size:16px;">System User</p>
@endif

</body>
</html>