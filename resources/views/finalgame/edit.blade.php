<?php
/*echo "<pre>";
print_r($poules);
echo "</pre>";*/
?>

<form id="edit_finalewedstr_form" class="row g-3" onsubmit="updateFinaleWedstr({{ $id }}); return false">
    {{ csrf_field() }}
    {{--<input type="hidden" name="id" value="{{ $id }}">--}}
    <input type="hidden" id="inputHometeamId" name="hometeam_id" value="">
    <input type="hidden" id="inputAwayteamId" name="awayteam_id" value="">
    {{--eerste rij--}}
    <div class="col-md-1 text-center">
    </div>
    <div class="col-md-9">
        <input type="text" class="form-control text-center" id="inputFinaleWedstr" name="inputFinaleWedstr" value="{{ $finalgame->name }}">
    </div>
    <div class="col-md-2 text-center">
    </div>
    {{--tweede rij--}}
    <div class="col-md-1 text-center">
    </div>
    <div class="col-md-2 text-center">
        <label for="inputHomePoule" class="form-label">Poule</label>
        <SELECT class="form-control" id="inputHomePoule" name="home_poule" onchange="getRankingOptions(0, {{ $poules }}); return false">
            <option value="">kies...</option>
            @foreach($poules as $poule)
                <option value="{{ $loop->index }}_{{ $poule->id }}" @if($poule->id == $finalgame->homepoule_id) selected @endif >{{ $poule->poule_name }}</option>
            @endforeach
        </SELECT>
    </div>
    <div class="col-md-2 text-center">
        <label for="inputHomeRanking" class="form-label">Plek</label>
        <SELECT class="form-control" id="inputHomeRanking" name="home_ranking" onchange="selectTeamByRanking(0); return false">
            <option value="">kies...</option>
            @for($i = 1; $i <= $finalgame->homepoule_teamsnmbr; $i++)
                <option value="{{ $i }}" @if($i == $finalgame->home_ranking) selected @endif >{{ $i }}</option>
            @endfor
        </SELECT>
    </div>
    {{--derde rij--}}
    <div class="col-md-1 text-center" style="padding-top: 35px;">-
    </div>
    <div class="col-md-2 text-center">
        <label for="inputAwayPoule" class="form-label">Poule</label>
        <SELECT class="form-control" id="inputAwayPoule" name="away_poule" onchange="getRankingOptions(1, {{ $poules }}); return false;">
            <option value="">kies...</option>
            @foreach($poules as $poule)
                <option value="{{ $loop->index }}_{{ $poule->id }}" @if($poule->id == $finalgame->awaypoule_id) selected @endif>{{ $poule->poule_name }}</option>
            @endforeach
        </SELECT>
    </div>
    <div class="col-md-2 text-center">
        <label for="inputAwayRanking" class="form-label">Plek</label>
        <SELECT class="form-control" id="inputAwayRanking" name="away_ranking" onchange="selectTeamByRanking(1); return false">
            <option value="">kies...</option>
            @for($i = 1; $i <= $finalgame->awaypoule_teamsnmbr; $i++)
                <option value="{{ $i }}" @if($i == $finalgame->away_ranking) selected @endif >{{ $i }}</option>
            @endfor
        </SELECT>
    </div>
    <div class="col-md-2 text-center">
    </div>
    <div class="col-md-1 text-center">
    </div>
    <div class="col-md-4 text-center">
        <label id="HomeTeamname" for="inputHomeScore" class="form-label">@if( $finalgame->hometeam_id == null)Thuis-team @else {{ $finalgame->hometeam->team_name }} @endif</label>
        <input type="text" class="form-control text-center" id="inputHomeScore" name="home_score" value="">
    </div>
    <div class="col-md-1 text-center" style="padding-top: 35px;">-
    </div>
    <div class="col-md-4 text-center">
        <label id="AwayTeamname" for="inputAwayLabel" class="form-label">@if( $finalgame->awayteam_id == null)Uiy-team @else {{ $finalgame->awayteam->team_name }} @endif</label>
        <input type="text" class="form-control text-center" id="inputAwayScore" name="away_score" value="">
    </div>
    <div class="col-md-2 text-center">
    </div>
</form>
<script>
    function getRankingOptions(homeoraway, pouleObj)
    {
        index = -1
        if(homeoraway == 0) {
            index = $("#inputHomePoule").val().split("_")[0]
            $("#inputHomeRanking").empty().append("<option value=''>kies...</option>")
        }
        else {
            index = $("#inputAwayPoule").val().split("_")[0]
            $("#inputAwayRanking").empty().append("<option value=''>kies...</option>")
        }
        for(i = 1; i <= pouleObj[index].teams_count; i++)
        {
            if(homeoraway == 0)
                $("#inputHomeRanking").append("<option value='" + i + "'>" + i + "</option>")
            else
                $("#inputAwayRanking").append("<option value='" + i + "'>" + i + "</option>")
        }
        //$("#test").empty().append(pouleObj[index].teams_count)
    }

    function selectTeamByRanking(homeoraway)
    {
        let pouleid = 0
        let ranking = 0
        if(homeoraway == 0) {
            pouleid = $("#inputHomePoule").val().split("_")[1]
            ranking = $("#inputHomeRanking").val()
        }
        else
        {
            pouleid = $("#inputAwayPoule").val().split("_")[1]
            ranking = $("#inputAwayRanking").val()
        }
        let url = "{{ route('poules.returnstand', ':id') }}";
        url = url.replace(':id', pouleid);
        $.ajax({
            url: url,
            data: {
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            type: 'get',
            success: function (data){
                if(homeoraway == 0) {
                    //$("#inputHomeTeam").val(data.ranking[ranking-1].team_name)
                    $("#inputHometeamId").val(data.ranking[ranking - 1].id)
                    $("#HomeTeamname").empty().append(data.ranking[ranking - 1].team_name)
                }
                else
                {
                    //$("#inputAwayTeam").val(data.ranking[ranking-1].team_name)
                    $("#inputAwayteamId").val(data.ranking[ranking - 1].id)
                    $("#AwayTeamname").empty().append(data.ranking[ranking - 1].team_name)
                }
            },
            error: function (){
                alert("niet goed gegaan");
            }
        })
    }
</script>
