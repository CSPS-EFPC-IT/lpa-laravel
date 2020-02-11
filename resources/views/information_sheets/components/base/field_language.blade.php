<dt>{{ $label ?? trans("forms/$field.label") }}</dt>
<dd>
@isset($data)
    {{ trans("entities/form.$data") }}
@else
    {{ trans('entities/general.none') }}
@endif
</dt>
