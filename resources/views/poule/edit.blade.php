<?php
/*echo "<pre>";
print_r($teams);
echo "</pre>";*/
?>
<form id="edit_poule_form" method="post" class="row g-3" action="{{ route('teams.update', $poule_id) }}">
    {{ csrf_field() }}
    @foreach($teams as $team)
            <div class="col-12">
                 <div class="input-group flex-nowrap">
                    <span class="input-group-text" id="addon-wrapping">@if($team->team_nr < 10) &nbsp;@endif {{ $team->team_nr }}</span>
                    <input name="inputTeamname_{{ $team->id }}" type="text" class="form-control" aria-describedby="addon-wrapping" value="{{ $team->team_name }}">
                     @if($tournement->is_clubcompetition > 0)
                             <select id="club_select_{{ $team->id }}" name="inputClubid_{{ $team->id }}" class="form-select">
                                 <option value="">Kies...</option>
                                 @foreach($clubs as $club)
                                         <?php $selected = ""; ?>
                                     @if($team->club_id ==  $club->id)
                                             <?php $selected = "SELECTED"; ?>
                                     @endif
                                     <option value="{{ $club->id }}" {{ $selected }}>{{ $club->club_name }}</option>
                                 @endforeach
                             </select>
                     @endif
                 </div>
            </div>
    @endforeach
</form>
