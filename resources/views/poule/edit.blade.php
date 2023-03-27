<?php
/*echo "<pre>";
print_r($poule);
echo "</pre>";*/
?>
<form id="edit_poule_form" method="post" class="row g-3" action="{{ route('poules.update', $poule[0]->id) }}">
    {{ csrf_field() }}
    <div class="col-md-2">
    </div>
    @foreach($poule[0]->teams as $team)
    <div class="input-group flex-nowrap">
        <span class="input-group-text" id="addon-wrapping">{{ $team->team_nr }}</span>
        <input name="inputTeamname_{{ $team->id }}" type="text" class="form-control" aria-describedby="addon-wrapping" value="{{ $team->team_name }}">
    </div>
    @endforeach
</form>
