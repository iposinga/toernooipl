<?php
/*echo "<pre>";
print_r($poules);
echo "</pre>";*/
//echo "<pre>";
//print_r($finalgames_roundearlier);
//echo "</pre>";
//echo $finalround_minimal_nr[0]->minround_nr;
switch ($finalgame->round->finalround) {
    case 18:
        $titel = "Achtste finalewedstrijd";
    break;
    case 8:
        $titel = "Achtste finalewedstrijd";
    break;
    case 14:
        $titel = "Kwartfinalewedstrijd";
    break;
    case 4:
        $titel = "Kwartfinalewedstrijd";
        break;
    case 12:
        $titel = "Halve finalewedstrijd";
        break;
    case 2:
        $titel = "Halve finalewedstrijd";
        break;
    case 11:
        $titel = "Finalewedstrijd";
        break;
    case 1:
        $titel = "Finale wedstrijd";
        break;
    default:
        $titel = "Anders";
}
?>
<form id="edit_finalewedstr_form" method="post" action="/public/js/finalgames/update">
    {{ csrf_field() }}
    <input type="hidden" id="inputFinalgameId" name="id" value="{{ $finalgame->id }}">
    <input type="hidden" id="inputHomepoule_Teamsnmbr" name="homepoule_teamsnmbr" value="{{ $finalgame->homepoule_teamsnmbr }}">
    <input type="hidden" id="inputAwaypoule_Teamsnmbr" name="awaypoule_teamsnmbr" value="{{ $finalgame->awaypoule_teamsnmbr }}">
    {{--eerste rij--}}
    <div class="row mb-3 g-3">
        <div class="col-md-11 offset-md-1 ">
            <div class="input-group">
            <span class="input-group-text" id="basic-addon1">{{ $titel }}</span>
            <input type="text" class="form-control" id="inputFinaleWedstr" name="name" value="{{ $finalgame->name }}">
            </div>
        </div>
    </div>
    {{--tweede rij--}}
    @if( $finalgame->round->finalround >= 10)
    <div class="row mb-3 g-3">
        <div class="col-md-3 offset-md-1">
            <label for="inputHomePoule" class="form-label">Poule</label>
            <SELECT class="form-control" id="inputHomePoule" name="homepoule_id" onchange="getRankingOptions(0, {{ $poules }}); return false">
                <option value="">kies...</option>
                <option value="0" @if($finalgame->homepoule_id == 0) selected @endif>Best</option>
                @foreach($poules as $poule)
                    <option value="{{ $loop->index }}_{{ $poule->id }}" @if($poule->id == $finalgame->homepoule_id) selected @endif >{{ $poule->poule_name }}</option>
                @endforeach
            </SELECT>
        </div>
        <div class="col-md-2">
            <label for="inputHomeRanking" class="form-label">Plek</label>
            <SELECT class="form-control" id="inputHomeRanking" name="home_ranking" onchange="selectTeamByRanking(0); return false">
                <option value="">...</option>
                @for($i = 1; $i <= $finalgame->homepoule_teamsnmbr; $i++)
                    <option value="{{ $i }}" @if($i == $finalgame->home_ranking) selected @endif >{{ $i }}</option>
                @endfor
            </SELECT>
        </div>
    <div class="col-md-1 text-center" style="padding-top: 35px;">-
    </div>
    <div class="col-md-3">
        <label for="inputAwayPoule" class="form-label">Poule</label>
        <SELECT class="form-control" id="inputAwayPoule" name="awaypoule_id" onchange="getRankingOptions(1, {{ $poules }}); return false;">
            <option value="">kies...</option>
            <option value="0" @if($finalgame->awaypoule_id == 0) selected @endif>Best</option>
            @foreach($poules as $poule)
                <option value="{{ $loop->index }}_{{ $poule->id }}" @if($poule->id == $finalgame->awaypoule_id) selected @endif>{{ $poule->poule_name }}</option>
            @endforeach
        </SELECT>
    </div>
    <div class="col-md-2">
        <label for="inputAwayRanking" class="form-label">Plek</label>
        <SELECT class="form-control" id="inputAwayRanking" name="away_ranking" onchange="selectTeamByRanking(1); return false">
            <option value="">...</option>
            @for($i = 1; $i <= $finalgame->awaypoule_teamsnmbr; $i++)
                <option value="{{ $i }}" @if($i == $finalgame->away_ranking) selected @endif >{{ $i }}</option>
            @endfor
        </SELECT>
    </div>
    </div>
    @else
        <div class="row mb-3 g-3">
            <div class="col-md-5 offset-md-1 text-center">
                <label for="inputHomeGameWinner" class="form-label">Winnaar wedstrijd</label>
                <select class="form-select" id="home_winnergame_id" name="home_winnergame_id">
                    <option value="">kies...</option>
                    @foreach($finalgames_roundearlier as $finalgame_roundearlier)
                        <option value="{{ $finalgame_roundearlier->id }}" @if($finalgame_roundearlier->id == $finalgame->home_winnergame_id) selected @endif>{{ $finalgame_roundearlier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 text-center" style="padding-top: 35px;">-</div>
            <div class="col-md-5 text-center">
                <label for="inputAwayGameWinner" class="form-label">Winnaar wedstrijd</label>
                <select class="form-select" id="away_winnergame_id" name="away_winnergame_id">
                    <option value="">kies...</option>
                    @foreach($finalgames_roundearlier as $finalgame_roundearlier)
                        <option value="{{ $finalgame_roundearlier->id }}" @if($finalgame_roundearlier->id == $finalgame->away_winnergame_id) selected @endif>{{ $finalgame_roundearlier->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    {{--verborgen rij--}}
    <div class="row mb-3 g-3 hiddenrow">
        <div id="thuisteaminput" class="col-md-5 offset-md-1 text-center">
            <select id="inputHomeTeam" name="hometeam_id" class="form-select HomeTeamLableInput">
                @if($finalgame->homepoule_id == 0)
                    <option value="">kies...</option>
                    @foreach($teams as $team)
                        @if( $team->team_ranking == $finalgame->home_ranking)
                            <option value="{{ $team->id }}" @if($team->id == $finalgame->hometeam_id) selected @endif>{{ $team->poule->poule_name }}: {{ $team->team_name }} - {{ $team->points }} | {{ $team->goaldifference }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-1 text-center" style="padding-top: 35px;"></div>
        <div id="uitteaminput" class="col-md-5 text-center">
            <select id="inputAwayTeam" name="awayteam_id" class="form-select AwayTeamLableInput">
                @if($finalgame->awaypoule_id == 0)
                    <option value="">kies...</option>
                    @foreach($teams as $team)
                        @if( $team->team_ranking == $finalgame->away_ranking)
                            <option value="{{ $team->id }}" @if($team->id == $finalgame->awayteam_id) selected @endif>{{ $team->poule->poule_name }}: {{ $team->team_name }} - {{ $team->points }} | {{ $team->goaldifference }}</option>
                       @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    {{--derde rij met uitslag --}}
    <div class="row mb-3 g-3">
        <div class="col-md-5 offset-md-1 text-center">
            <label id="HomeTeamname" for="inputHomeScore" class="form-label">Thuis-team</label>
            <input type="text" class="form-control text-center" id="inputHomeScore" name="home_score" value="">
        </div>
        <div class="col-md-1 text-center" style="padding-top: 35px;">-</div>
        <div class="col-md-5 text-center">
            <label id="AwayTeamname" for="inputAwayLabel" class="form-label">Uit-team</label>
            <input type="text" class="form-control text-center" id="inputAwayScore" name="away_score" value="">
        </div>
    </div>
</form>
<script type="text/javascript">tournement_id ="{{ $finalgame->tournement_id }}" </script>
<script src="{{ asset('js/finalgame.edit.js') }}"></script>
<script>
    $(".hiddenrow").hide()
    if($("#inputHomePoule").val() == 0 || $("#inputAwayPoule").val() == 0)
        $(".hiddenrow").show()
    if($("#inputHomePoule").val() !== "" && $("#inputHomeRanking").val() !== "" && $("#inputHomePoule").val() != 0)
        document.addEventListener( "DOMContentLoaded", selectTeamByRanking(0))
    if($("#inputAwayPoule").val() !== "" && $("#inputAwayRanking").val() !== "" && $("#inputAwayPoule").val() != 0)
        document.addEventListener( "DOMContentLoaded", selectTeamByRanking(1))
    document.addEventListener("DOMContentLoaded", getTeams(tournement_id))
</script>
