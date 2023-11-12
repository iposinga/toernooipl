@extends('layouts.print')

@section('content')
    <?php
/*       echo "<pre>";
        print_r($finalrounds);
        echo "</pre>";*/
        ?>
    <div class="print" style="text-align: center;">
        <h3 style="text-align: center; font-weight: bold; margin-top: 20px;">{{ $tournement->tournement_name }} op {{ Carbon\Carbon::parse($tournement->tournement_date)->translatedFormat('l j F Y')  }}
        </h3>
            @foreach($poules as $poule)
                <div class='poule' style="display: inline-block; vertical-align: top; margin-top: 5px; ">
            <table class="tableprint">
                <tr><th colspan="3"><i>Poule {{ $poule->poule_name }}</i></th></tr>
                @foreach($poule->teams->sortBy(function($team) {return $team->team_nr;}) as $team)
                <tr>
                    <td class="text-end" style="padding: 0px 5px">{{ $team->team_nr }}</td>
                    <td>=</td>
                    <td class="text-start" style="padding: 0px 5px">{{ $team->team_name }}</td>
                </tr>
                @endforeach
            </table>
        </div>
            @endforeach
            @php
                use App\Models\Pitch;
                $veld = new Pitch();
                $aantal_velden = count($pitches);
                $veldplekken=[];
                $colspans=[];
                $actiefzoekveld = 1;
                $index = 0;
                while(array_sum($colspans) < $aantal_velden):
                    $veldplekken[$index] = $veld->zoekPlekVeld($tournement->id, $actiefzoekveld);
                    $colspans[$index] = $veld->telVeldenPlekVeld($tournement->id, $actiefzoekveld);
                    $actiefzoekveld = array_sum($colspans) + 1;
                    $index++;
                endwhile
            @endphp
            <div class="programma" style="text-align: center; margin-top: 20px;">
                <div class="programmatabel" style="display: inline-block;">
            <table class="tableprint">
                <tr>
                    <th class='programma_cel lijnondervet' colspan='2'></th>
                    @foreach($colspans as $colspan)
                        <th class='programma_cel lijnondervet lijnlinksvet' colspan='{{ $colspan }}'>{{ $veldplekken[$loop->index] }}</th>
                    @endforeach
                    </tr>
                <tr>
                    <th class='programma_cel lijnondervet'>NR</th>
                    <th class="programma_cel lijnondervet lijnlinks text-center">TIJD</th>
                @php
                    $veldteller = 1;
                @endphp
                    @while ($veldteller <= $aantal_velden)
                        <?php
                            $i=0;
                            $linksvet = false;
                            $colspan_tot = 0;
                            while($i < count($colspans)):
                                $colspan_tot += $colspans[$i];
                                if($veldteller == $colspan_tot + 1)
                                    $linksvet = true;
                                $i++;
                            endwhile;
                        ?>
                        @if($veldteller == 1 || $linksvet)
                            <?php $class_links = "lijnlinksvet"; ?>
                        @else
                            <?php $class_links = "lijnlinks"; ?>
                        @endif
                        <th class="programma_cel {{ $class_links }} lijnondervet">VELD {{$veldteller}}</th>
                        @php $veldteller++; @endphp
                    @endwhile
                </tr>
                @php
                    $vorigeronde = 0;
                    $vorigerondetijd = "";
                    $colspan = 2 + count($pitches);
                    $rondeteller = 1;
                    $rondewedstrtijdteller = 0;
                @endphp
                @foreach($rounds as $round)
                    @if(count($dates) > 1)
                        @if( date('d', strtotime($round->start))  <> date('d', strtotime($vorigerondetijd)) )
                            <tr>
                                <td class="text-start lijnondervet lijnbovenvet" colspan="{{ $colspan }}">
                                    <i>{{ Carbon\Carbon::parse($round->start)->translatedFormat('l j F') }}</i>
                                    @php
                                        $vorigerondetijd = $round->start;
                                        $rondeteller = 1;
                                    @endphp
                                </td>
                            </tr>
                        @endif
                    @endif
                @if( $rondeteller % 5 == 0)
                   {{-- @if ( ($loop->iteration) % 5 == 0)--}}
                            <?php $class_onder="lijnondervet";?>
                    @else
                            <?php $class_onder="lijnonderniet";?>
                    @endif
                    <tr><td class="programma_cel text-end {{ $class_onder }}"><b>{{ $round->round_nr }}.</b></td>
                        <td class="programma_cel {{ $class_onder }} lijnlinks">{{ Carbon\Carbon::parse($round->start)->translatedFormat('H:i') }}
                            - {{ Carbon\Carbon::parse($round->end)->translatedFormat('H:i') }}</td>
                        <?php $veldteller = 1; ?>
                        @foreach($round->games->sortBy(function($game) {return $game->pitch->pitch_nr;}) as $wedstrijd)
                                <?php
                                $i=0;
                                $linksvet = false;
                                $colspan_tot = 0;
                                while($i < count($colspans)):
                                    $colspan_tot += $colspans[$i];
                                    if($veldteller == $colspan_tot + 1)
                                        $linksvet = true;
                                    $i++;
                                endwhile;
                                ?>
                            @if ( $loop->iteration == 1 || $linksvet)
                                <?php $class_links = "lijnlinksvet" ?>
                            @else
                                <?php $class_links = "lijnlinks" ?>
                            @endif
                            <td class="programma_cel {{ $class_onder }} {{ $class_links }}">{{ $wedstrijd->hometeam->team_nr }} - {{ $wedstrijd->awayteam->team_nr }}{{--{{ $wedstrijd->pitch->pitch_nr }}|{{ $round->round_nr }}--}}</td>
                            <?php $veldteller++; ?>
                        @endforeach
                        @while ($veldteller <= $aantal_velden)
                                <?php
                                $i=0;
                                $linksvet = false;
                                $colspan_tot = 0;
                                while($i < count($colspans)):
                                    $colspan_tot += $colspans[$i];
                                    if($veldteller == $colspan_tot + 1)
                                        $linksvet = true;
                                    $i++;
                                endwhile;
                                ?>
                            @if ( $loop->iteration == 1 || $linksvet)
                                    <?php $class_links = "lijnlinksvet" ?>
                            @else
                                    <?php $class_links = "lijnlinks" ?>
                            @endif
                            <td class="programma_cel {{ $class_onder }} {{ $class_links }}"></td>
                            @php($veldteller++)
                        @endwhile
                    </tr>
                    @php($rondeteller++)
                @endforeach
                @foreach($finalrounds as $finalround)
                    @if ( $finalround->round_nr % 5 == 0)
                            <?php $class_onder="lijnondervet";?>
                    @else
                            <?php $class_onder="";?>
                    @endif
                    <tr><td class="programma_cel text-end {{ $class_onder }}"><b>{{ $finalround->round_nr }}.</b></td>
                        <td class="programma_cel {{ $class_onder }} lijnlinks">{{ Carbon\Carbon::parse($finalround->start)->translatedFormat('H:i') }}
                            - {{ Carbon\Carbon::parse($finalround->end)->translatedFormat('H:i') }}</td>
                            <?php $veldteller = 1; ?>
                        @foreach($finalround->finalgames->sortBy(function($finalgame) {return $finalgame->pitch->pitch_nr;}) as $finalewedstrijd)
                                <?php
                                $i=0;
                                $linksvet = false;
                                $colspan_tot = 0;
                                while($i < count($colspans)):
                                    $colspan_tot += $colspans[$i];
                                    if($veldteller == $colspan_tot + 1)
                                        $linksvet = true;
                                    $i++;
                                endwhile;
                                ?>
                            @if ( $loop->iteration == 1 || $linksvet)
                                    <?php $class_links = "lijnlinksvet" ?>
                            @else
                                    <?php $class_links = "lijnlinks" ?>
                            @endif
                            <td class="programma_cel {{ $class_onder }} {{ $class_links }}">
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
            </div>
            </div>
@endsection
