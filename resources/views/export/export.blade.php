<table>
    <tr><td><b>{{ $tournement->tournement_name }} op {{ Carbon\Carbon::parse($tournement->tournement_date)->translatedFormat('l j F Y')  }}</b></td></tr>
</table>

 <table>
    <tr>
        <th></th>
        @foreach($poules as $poule)
        <th style="text-align: center"><i><b>Poule {{ $poule->poule_name }}</b></i></th>
        @endforeach
    </tr>
     <?php $teller = 1; ?>
     @while ($teller <= 6)
     <tr>
         <td></td>
         @foreach($poules as $poule)
            @foreach($poule->teams->sortBy(function($team) {return $team->team_nr;}) as $team)
                 @if($loop->iteration == $teller)
                    <td>{{ $team->team_nr }} = {{ $team->team_name }}</td>
                 @endif
            @endforeach
         @endforeach
     </tr>
         <?php $teller++; ?>
     @endwhile
 </table>

<table>
    <tr>
        <th style="text-align: right"><b>NR</b></th>
        <th colspan="2" style="text-align: center"><b>TIJD</b></th>
    @foreach($pitches as $pitch)
            <th style="text-align: center"><b>VELD {{ $pitch->pitch_nr }}</b></th>
    @endforeach
    </tr>
    @php
     $vorigeronde =0;
     $vorigerondetijd = "";
    @endphp
    @foreach($games as $wedstrijd)
        @if(count($dates) > 1)
            @if( date('d', strtotime($wedstrijd->round->start))  <> date('d', strtotime($vorigerondetijd)) )
                <tr>
                    <td>
                        <i>{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('l j F') }}</i>
                            @php($vorigerondetijd = $wedstrijd->round->start)
                    </td>
                </tr>
            @endif
        @endif
        @if($wedstrijd->round->round_nr <> $vorigeronde)
            @php($vorigeronde = $wedstrijd->round->round_nr)
            <tr>
                <td><b>{{ $wedstrijd->round->round_nr }}</b></td>
                <td>{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('H:i') }}</td>
                <td>{{ Carbon\Carbon::parse($wedstrijd->round->end)->translatedFormat('H:i') }}</td>
        @endif
                <td style="text-align: center">{{ $wedstrijd->hometeam->team_nr }} - {{ $wedstrijd->awayteam->team_nr }}</td>
        @if($wedstrijd->round->round_nr <> $vorigeronde && $wedstrijd->round->round_nr <> count($rounds))
                </tr>
        @endif
    @endforeach
</tr>
                @foreach($finalrounds as $finalround)
                    <tr><td><b>{{ $finalround->round_nr }}</b></td>
                        <td>{{ Carbon\Carbon::parse($finalround->start)->translatedFormat('H:i') }}
                            - {{ Carbon\Carbon::parse($finalround->end)->translatedFormat('H:i') }}</td>
                            <?php $veldteller = 1; ?>
                        @foreach($finalround->finalgames->sortBy(function($finalgame) {return $finalgame->pitch->pitch_nr;}) as $finalewedstrijd)
                            <td style="text-align: center">
                                @if(is_null($finalewedstrijd->homepoule_id) && is_null($finalewedstrijd->awaypoule_id))
                                    @if($finalewedstrijd->home_winnergame_id > 0 || $finalewedstrijd->away_winnergame_id > 0)
                                        {{ $finalewedstrijd->homeparent->name }} - {{ $finalewedstrijd->awayparent->name }}
                                    @endif
                                @elseif($finalewedstrijd->homepoule_id == 0 || $finalewedstrijd->awaypoule_id == 0)
                                    @if($finalewedstrijd->homepoule_id == 0 && $finalewedstrijd->awaypoule_id == 0)
                                        {{ $finalewedstrijd->home_ranking }}<sup>e</sup> - {{ $finalewedstrijd->away_ranking }}<sup>e</sup>
                                    @elseif($finalewedstrijd->homepoule_id == 0)
                                        {{ $finalewedstrijd->home_ranking }}<sup>e</sup> - {{ $finalewedstrijd->awaypoule->poule_name }}{{ $finalewedstrijd->away_ranking }}
                                    @else
                                        {{ $finalewedstrijd->homepoule->poule_name }}{{ $finalewedstrijd->home_ranking }} - {{ $finalewedstrijd->away_ranking }}<sup>e</sup>
                                    @endif
                                @else
                                    {{ $finalewedstrijd->homepoule->poule_name }}{{ $finalewedstrijd->home_ranking }} - {{ $finalewedstrijd->awaypoule->poule_name }}{{ $finalewedstrijd->away_ranking }}
                                @endif
                            </td>
                                <?php $veldteller++; ?>
                        @endforeach
                    </tr>
                @endforeach
</table>
