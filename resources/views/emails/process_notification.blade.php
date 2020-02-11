@component('mail::layout')

{{-- Header --}}

@slot('header')
    @component('mail::header', ['url' => ''])
    {{ $entity->name }}
    @endcomponent
@endslot

{{-- Body --}}

@slot('body')
<table class="inner-body" align="center" width="800" cellpadding="0" cellspacing="0">
    <!-- Body content -->
    <tr colspan="2">
        <td class="content-cell border-right">
            {!! nl2br($content['fr']['body']) !!}
        </td>
        <td class="content-cell">
            {!! nl2br($content['en']['body']) !!}
        </td>
    </tr>
    <!-- Actions content -->
    <tr colspan="2">
        <td class="action-cell border-right">
            @component('mail::button', ['url' => $content['fr']['actionUrl']])
            {{ $content['fr']['actionText'] }}
            @endcomponent
        </td>
        <td class="action-cell">
            @component('mail::button', ['url' => $content['en']['actionUrl']])
            {{ $content['en']['actionText'] }}
            @endcomponent
        </td>
    </tr>
</table>
@endslot

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} CSPS / EFPC
    @endcomponent
@endslot

@endcomponent
