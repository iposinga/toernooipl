@extends('layouts.print')

@section('content')
    <div class="container">
    <?php
    /*echo "<pre>";
    print_r($tournement);
    echo "</pre>";*/
    echo "<pre>";
    //print_r($games);
    echo "</pre>";
    /*echo "<pre>";
    print_r($finalgames);
    echo "</pre>";*/
    $teller = 0;
    ?>
    @foreach($games as $game)
        <?php
                if ($teller & 1)
                    $class = "a5rechts";
                else
                {
                    $class = "a5links";
                    echo "<div class=\"wrap\">\n";
                }
            ?>
        <div class="{{ $class }}">
        <h4 class="text-center">Wedstrijdformulier {{ $tournement->tournement_name }}</h4>
        <table>
        <tr>
            <td class="noborder">Veld</td>
            <td class="noborder">Poule</td>
            <td class="noborder">Ronde</td>
        </tr>
        <tr>
            <td class="groot">{{ $game->pitch->pitch_nr }}<h6>{{ $game->pitch->pitch_spot }}</h6></td>
            <td class="groot">{{ $game->poule->poule_name }}</td>
            <td class="groot">{{ $game->round->round_nr }}<h6>{{ Carbon\Carbon::parse($game->round->start)->translatedFormat('H:i') }} - {{ Carbon\Carbon::parse($game->round->end)->translatedFormat('H:i') }}</h6></td></tr>
        <tr>
            <td class="noborder">Thuis</td>
            <td class="noborder"></td>
            <td class="noborder">Uit</td></tr>
        <tr>
            <td class="middelgroot">{{ $game->hometeam->team_name }}<br>{{ $game->hometeam->team_nr }}</td>
            <td class="noborder"></td>
            <td class="middelgroot">{{ $game->awayteam->team_name }}<br>{{ $game->awayteam->team_nr }}</td>
        </tr>
        <tr>
            <td class="noborder">Turfvak</td>
            <td class="noborder"></td>
            <td class="noborder">Turfvak</td>
        </tr>
        <tr>
            <td class="groot"></td>
            <td class="noborder"><img src="{{ asset('/img/logo_plaatje_dtp.jpg') }}" alt="" height="45px"></td>
            <td class="groot"></td>
        </tr>
        <tr>
            <td class="noborder">Eindstand</td>
            <td class="noborder"></td>
            <td class="noborder">Eindstand</td>
        </tr>
        <tr>
            <td class="groot"></td>
            <td class="noborder"></td>
            <td class="groot"></td>
        </tr>
        <tr>
            <td class="noborder">Team {{ $game->hometeam->team_nr }}</td>
            <td class="noborder"></td>
            <td class="noborder">Team {{ $game->awayteam->team_nr }}</td>
        </tr>
        <tr>
            <td class="groot"></td>
            <td class="noborder">Handtekening<br>aanvoerders<br>voor akkoord</td>
            <td class="groot"></td>
        </tr>
{{--        <tr>
            <td colspan="3" class="noborder">Opmerkingen</td>
        </tr>
        <tr>
            <td colspan="3" class="groot"></td>
        </tr>--}}
        </table>
        </div>
        <?php
                $teller++;
                if ($class=='a5rechts')
                    echo "</div>\n";
            ?>
    @endforeach




    </div>
@endsection
