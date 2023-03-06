@extends('layouts.app_public')

@section('content')
<?php
/*    echo "<pre>";
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
        <div class="col-lg">
            <h2>Wedstrijdschema poule {{ $wedstrijden[0]->hometeam->poule->poule_name }}</h2>
            <table class="table table-hover w-auto">
                <thead><th></th><th colspan="3">tijd</th><th colspan="3">wedstrijd</th><th colspan="3">uitslag</th></thead>
                @foreach($wedstrijden as $wedstrijd)
                    <tr class="wedstr_{{ $wedstrijd->hometeam->team_nr }} wedstr_{{ $wedstrijd->awayteam->team_nr }}">
                        <td class="text-end"><b>{{ $wedstrijd->round->round_nr }}.</b></td>
                        <td>{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('H:i') }}</td>
                        <td>-</td>
                        <td>{{ Carbon\Carbon::parse($wedstrijd->round->end)->translatedFormat('H:i') }}</td>
                        <td>@if($wedstrijd->hometeam->team_name != null)
                                {{ $wedstrijd->hometeam->team_name }}
                            @else
                                {{ $wedstrijd->hometeam->team_nr }}
                            @endif
                        </td>
                        <td>-</td>
                        <td>@if($wedstrijd->awayteam->team_name != null)
                                {{ $wedstrijd->awayteam->team_name }}
                            @else
                                {{ $wedstrijd->awayteam->team_nr }}
                            @endif
                        </td>
                        <td id="homescore_{{ $wedstrijd->id }}" class="text-end">{{ $wedstrijd->home_score }}</td>
                        <td>-</td>
                        <td id="awayscore_{{ $wedstrijd->id }}">{{ $wedstrijd->away_score }}</td>
                    </tr>

                @endforeach

            </table>
        </div>
        <div class="col-lg">
            <h2>Stand poule {{ $wedstrijden[0]->hometeam->poule->poule_name }}</h2>
            <table class="table table-hover">
                <thead>
                <tr><th class="border-end"></th><th class="text-center border-end">team</th><th class="border-end">naam</th><th class="text-center">p</th><th class="text-center border-end">g</th><th class="text-center">w</th><th class="text-center">g</th><th class="text-center border-end">v</th><th colspan="3" class="text-center">doelsaldo</th></tr>
                </thead>
                <?php $plek = 1 ?>
                @foreach($sortedteams as $sortedteam)
                    <tr class="team_{{ $sortedteam->team_nr }}">
                        <td class="text-end border-end"><b>{{ $plek }}.</b></td>
                        <td class="text-end border-end" style="padding-right: 15px;">{{ $sortedteam->team_nr }}</td>
                        <td><a href="#" class="link link-underlineless" onclick="highLight({{ $sortedteam->team_nr }})">{{ $sortedteam->team_name }}</a></td>
                        <td class="text-end text-bg-primary">{{ $sortedteam->punten }}</td>
                        <td class="text-end border-end">{{ $sortedteam->gespeeld }}</td>
                        <td class="text-end">{{ $sortedteam->gewonnen }}</td>
                        <td class="text-end">{{ $sortedteam->gelijk }}</td>
                        <td class="text-end border-end">{{ $sortedteam->verloren }}</td>
                        <td class="text-end">+{{ $sortedteam->doelpvoor }}</td>
                        <td class="text-end">-{{ $sortedteam->doelptegen }}</td>
                        <td class="text-end text-bg-warning">{{ $sortedteam->doelsdaldo }}</td>
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
        function highLight(teamnr){
            $("tr").removeClass("alert alert-primary");
            $(".wedstr_" + teamnr).addClass("alert alert-primary");
            $(".team_" + teamnr).addClass("alert alert-primary");
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
