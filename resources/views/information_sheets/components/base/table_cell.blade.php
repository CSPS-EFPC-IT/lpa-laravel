@php $innerData = $data ?? $slot; @endphp
@php $basePath = 'information_sheets.components.base' @endphp
<{{ $tag ?? 'td' }} colspan="1" rowspan="1" class="{{ $class ?? '' }} {{ $tag == 'th' ? 'is-leaf' : '' }}">
    <div class="cell">
        @if ($tag == 'th')
            {{ $label ?? trans("forms/$field.label") }}
        @else
            @if (isset($type))
                @component("$basePath.type_$type", ['data' => $innerData]) @endcomponent
            @else
                {!! isset($innerData) && strlen($innerData) > 0 ? nl2br($innerData) : trans('entities/general.none') !!}
            @endif
        @endif
    </div>
</{{ $tag ?? 'td' }}>
