@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ session()->get('system-information')['name'] }}
        @endcomponent
    @endslot

    This is an test mail.

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ session()->get('system-information')['name'] }}. @lang('All rights reserved.')
        @endcomponent
    @endslot
@endcomponent
