@component('mail::layout')

{{-- Header --}}

@slot('header')
    @component('mail::header', ['url' => ''])
    {{ $subject }}
    @endcomponent
@endslot

{{-- Body --}}

@slot('body')
<table class="inner-body" cellpadding="0" cellspacing="0">
    <tr>
        <td class="content-cell">
            <strong>Envoyé par / Sent by:</strong>
            <br>
            <a href="mailto:{{ $user->email }}">{{ $user->name }}</a>
            <br><br>
            <strong>URL référent / Referrer URL:</strong>
            <br>
            {{ $referrer }}
            <br><br>
            <strong>Message:</strong>
            <br>
            {{ $body }}
        </td>
    </tr>
</table>
@endslot

{{-- Footer --}}

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} CSPS / EFPC
    @endcomponent
@endslot

@endcomponent
