<?php
/*echo "<pre>";
print_r($pitches);
echo "</pre>";*/
?>
<form id="edit_pitches_form" method="post" class="row g-3" action="{{ route('pitches.update', $tournement_id) }}">
    {{ csrf_field() }}
    <div class="col-md-2">
    </div>
    @foreach($pitches as $pitch)
        <div class="input-group flex-nowrap">
            <span class="input-group-text text-end" id="addon-wrapping">@if($pitch->pitch_nr < 10) &nbsp;@endif {{ $pitch->pitch_nr }}</span>
            <input name="inputPitchname_{{ $pitch->id }}" type="text" class="form-control" aria-describedby="addon-wrapping" value="{{ $pitch->pitch_name }}">
            <input name="inputPitchspot_{{ $pitch->id }}" type="text" class="form-control" aria-describedby="addon-wrapping" value="{{ $pitch->pitch_spot }}">
        </div>
    @endforeach
</form>
