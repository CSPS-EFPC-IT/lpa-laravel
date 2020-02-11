<div class="no-break">
    <dt>{{ $label ?? trans("forms/$field.label") }}</dt>
    <dd>{!! isset($data) ? sprintf('%02d:%02d:%02d', ($data/3600),($data/60%60), $data%60) : trans('entities/general.none') !!}</dd>
</div>
