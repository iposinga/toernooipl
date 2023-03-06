<?php
/*echo "<pre>";
print_r($poule);
echo "</pre>";*/
?>
<form id="edit_poule_form" class="row g-3" method="post" action="{{ route('updatepouledata', ['id' => $poule[0]->id]) }}">
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
