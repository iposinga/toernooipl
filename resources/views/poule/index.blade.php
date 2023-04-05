@extends('layouts.app_public')

@section('content')
<?php
/*   echo "<pre>";
    print_r($wedstrijden);
    echo "</pre>";*/
?>


<div class="container">
    <div class="row">
        <div class="col-1"><form name="clockForm"><input class="btn btn-outline-secondary" type="button" name="clockButton" value="Loading..." style="margin-top: 25px"/></form></div>
        <div class="col-10 text-center">
            <h1 style="margin-top: 20px; margin-bottom: 40px;">{{ $wedstrijden[0]->round->tournement->tournement_name }} op {{ Carbon\Carbon::parse($wedstrijden[0]->round->tournement->tournement_date)->translatedFormat('l j F Y')  }}</h1>
        </div>
        <div class="col-1 text-end"><span class="btn btn-outline-secondary" id="timer" style="margin-top: 25px"></span></div>
    </div>
    <div class="row">
        <div class="col-lg-7">
            <h2>Wedstrijdschema poule {{ $wedstrijden[0]->hometeam->poule->poule_name }}</h2>
            <table class="table table-hover w-auto">
                <thead>
                    <th colspan="2">ronde en tijd</th>
                    <th colspan="5">@if($wedstrijden[0]->round->tournement->pitches_nmbr > 1)<span class="badge text-bg-secondary">veld</span>@endif wedstrijd</th>
                </thead>
                <?php $vorigeveld = 0  ?>
                @foreach($wedstrijden as $wedstrijd)
                    @if($wedstrijd->round->round_nr <> $vorigeveld)
                            <?php $vorigeveld = $wedstrijd->round->round_nr ?>
                        <tr>
                        <td class="text-end align-middle border-end"><b>{{ $wedstrijd->round->round_nr }}.</b></td>
                        <td class="border-end align-middle">{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('H:i') }}
                            - {{ Carbon\Carbon::parse($wedstrijd->round->end)->translatedFormat('H:i') }}
                        </td>
                    @endif
                    <?php
                    if(is_null($wedstrijd->hometeam->team_name))
                    {
                        $hometeamname = $wedstrijd->hometeam->team_nr;
                        $awayteamname = $wedstrijd->awayteam->team_nr;
                    }
                    else
                    {
                        $hometeamname = $wedstrijd->hometeam->team_name;
                        $awayteamname = $wedstrijd->awayteam->team_name;
                    }
                    ?>
                        <td class="wedstr_{{ $wedstrijd->hometeam->team_nr }} wedstr_{{ $wedstrijd->awayteam->team_nr }} text-center border-end align-middle border-top" style="padding-bottom: 0px">
                            <div class="d-grid">
                            <button class="btn btn-light position-relative">
                                @if($wedstrijden[0]->round->tournement->pitches_nmbr > 1)<span class="badge text-bg-secondary" style="margin-left: -10px;">{{ $wedstrijd->pitch->pitch_name }}</span>@endif
                                {{ $hometeamname }} - {{ $awayteamname }}
                                </button>
                        </div>
                                <h6 id="uitslag_{{ $wedstrijd->id }}">
                                    @if($wedstrijd->home_score != "")
                                        <span class="badge text-bg-success" style="background-color: #29286d; width: 100%">{{ $wedstrijd->home_score }} - {{ $wedstrijd->away_score }}</span>
                                    @endif
                                </h6>
                        </td>
                    @if($wedstrijd->round->round_nr <> $vorigeveld)
                        </tr>
                    @endif

                @endforeach

            </table>
        </div>
        <div class="col-lg-5">
            <h2>Stand poule {{ $wedstrijden[0]->hometeam->poule->poule_name }}</h2>
            <table class="table table-hover">
                <thead>
                <tr><th class="border-end"></th><th class="text-center border-end">team</th><th class="border-end">naam</th><th class="text-center">p</th><th class="text-center border-end">g</th><th class="text-center">w</th><th class="text-center">g</th><th class="text-center border-end">v</th><th colspan="3" class="text-center">doelsaldo</th></tr>
                </thead>
                <?php $plek = 1 ?>
                @foreach($sortedteams as $sortedteam)
                    <tr class="team_{{ $sortedteam->team_nr }}">
                        <td class="text-end border-end"><b>{{ $plek }}.</b></td>
                        <td class="text-end border-end" style="padding-right: 15px;"><a href="#" class="link link-underlineless" onclick="highLight({{ $sortedteam->team_nr }})">{{ $sortedteam->team_nr }}</a></td>
                        <td><a href="#" class="link link-underlineless" onclick="highLight({{ $sortedteam->team_nr }})">{{ $sortedteam->team_name }}</a></td>
                        <td class="text-end text-bg-primary">{{ $sortedteam->punten }}</td>
                        <td class="text-end border-end">{{ $sortedteam->gespeeld }}</td>
                        <td class="text-end">{{ $sortedteam->gewonnen }}</td>
                        <td class="text-end">{{ $sortedteam->gelijk }}</td>
                        <td class="text-end border-end">{{ $sortedteam->verloren }}</td>
                        <td class="text-end">+{{ $sortedteam->doelpvoor }}</td>
                        <td class="text-end">-{{ $sortedteam->doelptegen }}</td>
                        <td class="text-end text-bg-info">{{ $sortedteam->doelsdaldo }}</td>
                    </tr>
                        <?php $plek++; ?>
                    @endforeach
                    </tbody>
            </table>
            <p>Klik op een teamnaam om de bijbehorende wedstrijden te markeren.</p>
            <p>Deze pagina kun je bekijken via internet: scan de QR-code!</p>
            <p class="text-center"><img src="https://api.qrserver.com/v1/create-qr-code/?data=https://<?= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ?>&size=150x150" alt="" title="" /></p>

        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript">
/*        function highLight(teamnr){
            $("tr").removeClass("alert alert-primary");
            $(".wedstr_" + teamnr).addClass("alert alert-primary");
            $(".team_" + teamnr).addClass("alert alert-primary");
        }*/

        function highLight(teamnr){
            if($(".team_" + teamnr).hasClass("alert alert-warning"))
            {
                $("tr").removeClass("alert alert-warning");
                $("td").removeClass("bg-warning");
                //$("li").removeClass("alert alert-primary");
            }
            else
            {
                $("tr").removeClass("alert alert-warning");
                $("td").removeClass("bg-warning");
                //$("li").removeClass("alert alert-primary");
                $(".wedstr_" + teamnr).addClass("bg-warning");
                $(".team_" + teamnr).addClass("alert alert-warning");
            }
        }

        function clock(){
            let time = new Date()
            let hr = time.getHours()
            let min = time.getMinutes()
            let sec = time.getSeconds()
            if (hr < 10){
                hr = " " + hr
            }
            if (min < 10){
                min = "0" + min
            }
            if (sec < 10){
                sec = "0" + sec
            }
            document.clockForm.clockButton.value = hr + ":" + min
            setTimeout("clock()", 1000)
        }

        window.onload = clock;

        function checklength(i) {
            'use strict';
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
        let minutes, seconds, count, counter;
        count = 61; //seconds
        counter = setInterval(timer, 1000);

        function timer() {
            'use strict';
            count = count - 1;
            minutes = checklength(Math.floor(count / 60));
            seconds = checklength(count - minutes * 60);
            if (count < 0) {
                clearInterval(counter);
                return;
            }
            document.getElementById("timer").innerHTML = ' ' + minutes + ':' + seconds + ' ';
            if (count === 0) {
                location.reload();
            }
        }

        function pageloadEvery(t) {
            setTimeout('location.reload()', t);
        }
    </script>
@endsection
