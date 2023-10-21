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
                                @if($wedstrijden[0]->round->tournement->pitches_nmbr > 1)<span class="badge text-bg-secondary" style="margin-left: -10px;">{{ $wedstrijd->pitch->pitch_nr }}</span>@endif
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
                @foreach($teams as $team)
                    <tr class="team_{{ $team->team_nr }}">
                        <td class="text-end border-end"><b>{{ $plek }}.</b></td>
                        <td class="text-end border-end" style="padding-right: 15px;"><a href="#" class="link link-underlineless" onclick="highLight({{ $team->team_nr }})">{{ $team->team_nr }}</a></td>
                        <td><a href="#" class="link link-underlineless" onclick="highLight({{ $team->team_nr }})">{{ $team->team_name }}</a></td>
                        <td class="text-end text-bg-primary">{{ $team->points }}</td>
                        <td class="text-end border-end">{{ $team->played }}</td>
                        <td class="text-end">{{ $team->win }}</td>
                        <td class="text-end">{{ $team->draw }}</td>
                        <td class="text-end border-end">{{ $team->loss }}</td>
                        <td class="text-end">+{{ $team->goal }}</td>
                        <td class="text-end">-{{ $team->goalagainst }}</td>
                        <td class="text-end text-bg-info">{{ $team->goaldifference }}</td>
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
    <script src="{{ asset('js/poule.index.js') }}"></script>
@endsection
