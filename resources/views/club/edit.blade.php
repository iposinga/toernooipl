<?php
/*echo "<pre>";
print_r($clubs);
echo "</pre>";*/
?>
<form id="edit_clubs_form" method="post" class="row g-3" action="{{ route('clubs.update', $tournement_id) }}">
    {{ csrf_field() }}
    @foreach($clubs as $club)
        <div class="col-12">
            <div class="input-group flex-nowrap">
                <span class="input-group-text" id="addon-wrapping">@if($club->club_nr < 10) &nbsp;@endif {{ $club->club_nr }}</span>
                <input name="inputClubname_{{ $club->id }}" type="text" class="form-control" aria-describedby="addon-wrapping" value="{{ $club->club_name }}">
            </div>
        </div>
    @endforeach
</form>
