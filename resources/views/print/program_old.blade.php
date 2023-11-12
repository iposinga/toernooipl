@extends('layouts.print')

@section('content')
    <?php
        echo "<pre>";
        print_r($rounds);
        echo "</pre>";
        exit();
        ?>
    <div class="print" style="text-align: center;">
            @php
                use App\Models\Pitch;
                $veld = new Pitch();
                $plekveld1 = $veld->zoekPlekVeld($tournement->id, 1);
                $colspan1 = $veld->telVeldenPlekVeld($tournement->id, 1);
                $veld2 = $colspan1 + 1;
                $aantal_velden = count($pitches);
                if($veld2 <= $aantal_velden){
                    $plekveld2 = $veld->zoekPlekVeld($tournement->id, $veld2);
                    $colspan2 = $veld->telVeldenPlekVeld($tournement->id, $veld2);
                    $veld3 = $colspan1 + $colspan2 + 1;
                    if ($veld2 <= $aantal_velden){
                        $plekveld3 = $veld->zoekPlekVeld($tournement->id, $veld3);
                    }
                } else {
                    $colspan2 = 0;
                }
                if($aantal_velden - $colspan1 - $colspan2 > 0){
                    $colspan3 = $aantal_velden - $colspan1 - $colspan2;
                } else {
                    $colspan3 = 0;
                }
                $veldteller = 1;
            @endphp
            <table class="tableprint">
                <tr>
                    <th class='lijnonder' colspan='2'></th>
                    <th class='lijnonderenlinksvet' colspan='{{ $colspan1 }}'>{{ $plekveld1 }}</th>
                    @if($colspan2 > 0)
                        <th class='lijnonderenlinksvet' colspan='{{ $colspan2 }}'>{{ $plekveld2 }}</th>
                    @endif
                    @if($colspan3 > 0)
                        <th class='lijnonderenlinksvet' colspan='{{$colspan3}}'>{{$plekveld3}}</th>
                    @endif
                    </tr>
                <tr>
                    <th class='lijnonder'>NR</th>
                    <th class="lijnonderenlinks text-center">TIJD</th>
                @php $veldteller = 1; @endphp
                    @while ($veldteller <= $aantal_velden)
                        @if ($veldteller == 1 OR $veldteller == $colspan1 + 1 OR $veldteller == $colspan1 + $colspan2 + 1 OR $veldteller == $colspan1 + $colspan2 + $colspan3 + 1)
                            @php $class="lijnonderenlinksvet"; @endphp
                        @else
                            @php $class="lijnonderenlinks"; @endphp
                        @endif
                        <th class='{{$class}}'>VELD {{$veldteller}}</th>
                        @php $veldteller++; @endphp
                    @endwhile
                </tr>
                @php
                    $vorigeronde = 0;
                    $vorigerondetijd = "";
                    $colspan = 2 + count($pitches);
                    $rondeteller = 0;
                    $rondewedstrtijdteller = 0;
                @endphp
                @foreach($games as $wedstrijd)
                    @if(count($dates) > 1)
                        @if( date('d', strtotime($wedstrijd->round->start))  <> date('d', strtotime($vorigerondetijd)) )
                            <tr>
                                <td class="border-end" colspan="{{ $colspan }}">
                                    <i>{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('l j F') }}</i>
                                        @php($vorigerondetijd = $wedstrijd->round->start)
                                </td>
                            </tr>
                        @endif
                    @endif
                    <?php
                        $class="wedstrprint";
                        $class2="lijnzonder";
                    ?>
                    @if($wedstrijd->round->round_nr <> $vorigeronde)
                            <?php
                                $rondewedstrtijdteller = 1;
                                if ( ($rondeteller+1) % 5 == 0)
                                {
                                    $class="lijnonderenlinks";
                                    $class2="lijnonder";
                                }
/*                                if ($rondeteller+1 == 21)
                                {
                                    $class="lijnlinks";
                                    $class2="lijnzonder";
                                }*/
                                $rondeteller++;
                            ?>
                        @php($vorigeronde = $wedstrijd->round->round_nr)
                        <tr>
                            <td class="text-end {{ $class2 }}">{{ $wedstrijd->round->round_nr }}.</td>
                            <td class="text-center {{ $class }}">
                                {{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('H:i') }}
                                - {{ Carbon\Carbon::parse($wedstrijd->round->end)->translatedFormat('H:i') }}
                            </td>
                    @endif
                            <?php
                                if ( ($rondeteller) % 5 == 0)
                                {
                                    if ($wedstrijd->pitch->pitch_nr == 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + $colspan3 + 1)
                                        $class="lijnonderenlinksvet";
                                    else
                                        $class="lijnonderenlinks";
                                }
                                else
                                {
                                    if ($wedstrijd->pitch->pitch_nr == 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + $colspan3 + 1)
                                        $class="lijnlinksvet";
                                    else
                                        $class="lijnlinks";
                                }
/*                                if ($rondeteller == 21)
                                {
                                    if ($wedstrijd->pitch->pitch_nr == 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + 1 OR $wedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + $colspan3 + 1)
                                        $class="lijnlinksvet";
                                    else
                                        $class="lijnlinks";
                                }*/
                                ?>
                    <td class="text-center {{ $class }}">{{ $wedstrijd->hometeam->team_nr }} - {{ $wedstrijd->awayteam->team_nr }}</td>
                    @if($wedstrijd->round->round_nr <> $vorigeronde && $wedstrijd->round->round_nr <> count($rounds))
                        </tr>
                    @endif
                    @if ($loop->last)
                        <?php
                            $startnieuweronde = date('d-m-Y H:i', strtotime($wedstrijd->round->end . ' +' . $tournement->change_duration . ' minutes'));
                            $nieuwerondenr = $wedstrijd->round->round_nr + 1;
                        ?>
                    @endif
                    @php($rondewedstrtijdteller++)
                @endforeach
                @while($rondewedstrtijdteller <= count($pitches))
                    <td class="text-center {{ $class }}"></td>
                    @php($rondewedstrtijdteller++)
                @endwhile
            </tr>
{{--                <tr>
                    <td class="border-end" colspan="{{ $colspan }}">
                        @if(count($finalgames) == 0)
                            <a style="text-decoration: none;" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showAddFinalround({{ $tournement->id }}, {{ $nieuwerondenr }}, '{{ $startnieuweronde }}', {{ $tournement->game_duration }})">
                                <i class="bi bi-plus-square"></i>
                            </a>
                        @endif
                        <i>Finalerondes</i></td>
                </tr>--}}
                <?php
                $vorigeronde = 0;
                $vorigerondetijd = "";
                ?>
                @foreach($finalgames as $finalewedstrijd)
                    @if($finalewedstrijd->round->round_nr <> $vorigeronde)
                            <?php
                            $counter = 1;
                            if ( ($rondeteller+1) % 5 == 0)
                            {
                                $class="lijnonderenlinks";
                                $class2="lijnonder";
                            }
                            if ($rondeteller+1 == 14)
                            {
                                $class="lijnlinks";
                                $class2="lijnzonder";
                            }
                            $rondeteller++;
                            ?>
                            <?php $vorigeronde = $finalewedstrijd->round->round_nr; ?>
                        <tr>
                            <td class="text-end {{$class2}}">{{ $finalewedstrijd->round->round_nr }}.</td>
                            <td class="text-center {{$class}}">
                                {{ Carbon\Carbon::parse($finalewedstrijd->round->start)->translatedFormat('H:i') }}
                                - {{ Carbon\Carbon::parse($finalewedstrijd->round->end)->translatedFormat('H:i') }}
                            </td>
                    @endif
                                <?php
                                if ( ($rondeteller) % 5 == 0)
                                {
                                    if ($finalewedstrijd->pitch->pitch_nr == 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + $colspan3 + 1)
                                        $class="lijnonderenlinksvet";
                                    else
                                        $class="lijnonderenlinks";
                                }
                                else
                                {
                                    if ($finalewedstrijd->pitch->pitch_nr == 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + $colspan3 + 1)
                                        $class="lijnlinksvet";
                                    else
                                        $class="lijnlinks";
                                }
/*                                if ($rondeteller == 14)
                                {
                                    if ($finalewedstrijd->pitch->pitch_nr == 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + 1 OR $finalewedstrijd->pitch->pitch_nr == $colspan1 + $colspan2 + $colspan3 + 1)
                                        $class="lijnlinksvet";
                                    else
                                        $class="lijnlinks";
                                }*/
                                ?>
                            <td class="text-center {{ $class }}">
{{--                                    @if(!empty($finalewedstrijd->name))
                                        {{$finalewedstrijd->name}}<br>
                                    @endif--}}
                                    @if(is_null($finalewedstrijd->homepoule_id) && is_null($finalewedstrijd->awaypoule_id))
                                        @if($finalewedstrijd->home_winnergame_id > 0 || $finalewedstrijd->away_winnergame_id > 0)
                                            {{ $finalewedstrijd->homeparent->name }} - {{ $finalewedstrijd->awayparent->name }}
{{--                                        @else
              --p0                              <span style="font-size: large"><i class="bi bi-info-square"></i></span>--}}
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
                            @if($finalewedstrijd->round->round_nr <> $vorigeronde)
                        </tr>
                    @endif
                    @if ($loop->last)
                            <?php
                            $startnieuweronde = date('d-m-Y H:i', strtotime($finalewedstrijd->round->end . ' +' . $tournement->change_duration . ' minutes'));
                            $nieuwerondenr = $finalewedstrijd->round->round_nr + 1;
                            ?>
                    @endif
                @endforeach
            </table>
@endsection
