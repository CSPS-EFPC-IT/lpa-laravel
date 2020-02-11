<div class="no-break">
    <dt>{{ trans("forms/$field.label") }} ({{ trans('entities/form.en') }})</dt>
    <dd>{!! isset($data_en) ? nl2br($data_en) : trans('entities/general.none') !!}</dd>
    <dt>{{ trans("forms/$field.label") }} ({{ trans('entities/form.fr') }})</dt>
    <dd>{!! isset($data_fr) ? nl2br($data_fr) : trans('entities/general.none') !!}</dd>
</div>
