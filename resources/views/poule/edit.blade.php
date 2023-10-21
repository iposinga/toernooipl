<?php
/*echo "<pre>";
print_r($teams);
echo "</pre>";*/
?>
<form id="edit_poule_form" method="post" class="row g-3" action="{{ route('teams.update', $poule_id) }}">
    {{ csrf_field() }}
    <div class="col-md-2">
    </div>
    @foreach($teams as $team)
    <div class="input-group flex-nowrap">
        <span class="input-group-text" id="addon-wrapping">@if($team->team_nr < 10) &nbsp;@endif {{ $team->team_nr }}</span>
        <input name="inputTeamname_{{ $team->id }}" type="text" class="form-control" aria-describedby="addon-wrapping" value="{{ $team->team_name }}">
    </div>
    @endforeach
</form>
