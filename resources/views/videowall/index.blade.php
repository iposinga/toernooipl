<?php
/*    echo "<pre>";
    print_r($rounds);
    echo "</pre>";*/
    ?>
@if($tournement->poules_nmbr <= 3)
    <?php $kolomverdeling = "50% 50%"; ?>
@elseif($tournement->poules_nmbr > 5)
    <?php $kolomverdeling = "25% 25% 25% 25%"; ?>
@else
    <?php $kolomverdeling = "33% 33% 33%"; ?>
@endif
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="refresh" content="60">
    <title>De Toernooiplanner</title>
    <link rel="stylesheet" href="{{ asset('css/toernooiplanner.css') }}">
    <!-- Scripts -->
    <style>
        body {
            font-size: 20px;
            margin: 40px;
        }
        .wrapper {
            display: grid;
            grid-template-columns: {{ $kolomverdeling }};
            grid-gap: 10px;
            background-color: #fff;
            color: #444;
        }
        .box {
            background-color: #eff;
            color: #444;
            border-radius: 5px;
            padding: 20px;
            /*font-size: 150%;*/
        }
    </style>
</head>
<body style="font-size: 20px;">
<h1>{{ $tournement->tournement_name }}</h1>
<div class="wrapper">
    @php($teller = 1)
{{--        $charteller = 65;--}}
    @foreach($poules as $poule)
        <div class="box">
        <table><tr>
<td class='zonder' style="vertical-align: top">
                <h2>Wedstrijdschema poule {{ $poule->poule_name }}</h2>
    <table>
        <thead>
            <tr>
                <th class="standleft" colspan="2">ronde</th>
                <th class="standleft" colspan="3">wedstrijden (veld)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
        @php($vorigeronde = 0)
        @foreach($poule->games->sortBy(function($game){return $game->round->round_nr;}) as $game)
            @if($game->round->round_nr <> $vorigeronde)
            </tr>
            <tr>
                <td class="rijkop">&nbsp;{{ $game->round->round_nr }}.&nbsp;</td>
                <td>{{ Carbon\Carbon::parse($game->round->start)->translatedFormat('H:i') }} - {{ Carbon\Carbon::parse($game->round->end)->translatedFormat('H:i') }}</td>
                @php($vorigeronde = $game->round->round_nr)
            @endif
                <td class="wedstr">{{ $game->hometeam->team_nr }} - {{ $game->awayteam->team_nr }}
                    @if($game->home_score <> "" && $game->away_score <> "")
                        <br><span style="color: red;  font-size: smaller">{{$game->home_score}} - {{ $game->away_score }}</span>
                    @else
                        ({{ $game->pitch->pitch_nr }})
                    @endif
                </td>
        @endforeach
            </tr>
    </table>
            </td>
            <td class="zonder" style="vertical-align: top">
                <h2 style="text-align: end;">Stand poule {{ $poule->poule_name }}</h2>
                <table>
                    <thead>
                    <tr>
                        <th></th>
                        <th class="standright">team</th>
                        <th class="standleft">naam</th>
                        <th class="standcenter">p</th>
                        <th class="standcenter">g</th>
                        <th class="standcenter">w</th>
                        <th class="standcenter">g</th>
                        <th class="standcenter">v</th>
                        <th colspan="3">doelsaldo</th>
                    </tr>
                    </thead>
                <tbody>
                    @foreach($poule->teams->sortBy(function($team){return $team->team_ranking;}) as $team)
                        <tr>
                            <td class="rijkop">&nbsp;{{ $loop -> iteration }}.&nbsp;</td>
                            <td class="standright">{{ $team->team_nr }}</td>
                            <td class="standleft">{{ $team->team_name }}</td>
                            <td class="standcenter" style="background: yellow;">{{ $team->points }}</td>
                            <td class="standcenter">{{ $team->played }}</td>
                            <td class="standcenter">{{ $team->win }}</td>
                            <td class="standcenter">{{ $team->draw }}</td>
                            <td class="standcenter">{{ $team->loss }}</td>
                            <td class="standright">+{{ $team->goal }}</td>
                            <td class="standright">-{{ $team->goalagainst }}</td>
                            <td class="standright" style="background: orange;">{{ $team->goaldifference }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </td>
            </tr>
        </table>
        </div>
        <?php $teller++; ?>
    @endforeach
    <div class="box">
        <table>
        <tr>
            <td class="zonder" style="vertical-align: top; border: solid 2px;">
{{--            $wedstrschema = $toernooi->displayWedstrschema($toernooiid);
            echo  $wedstrschema;--}}
                wedstrijdschema hele toernooi
            </td>
        </tr>
        </table>
    </div>
</div>
</body>
</html>
