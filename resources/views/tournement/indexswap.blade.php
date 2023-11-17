@extends('layouts.app')

@section('content')
    <div class="container">
        <?php
        //echo "<pre>";
        //print_r($dates);
        //echo "</pre>";
        //echo "<pre>";
        //print_r($wedstrijden);
        //echo "</pre>";
        ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if (Session::get('success', false))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <div class="card-header" style="background-color: #29286d; color: white;">
                        <div class="row" style="font-size: larger">
                            <div class="col-4">
                                {{ $tournement->tournement_name }}
                            </div>
                            <div class="col-4">Wissel wedstrijden om</div>
                            <div class="col-1">
                                <a type="button" href="{{ route('tournement.show', ['id' => $tournement->id, 'poule_id' => 0]) }}" style="color: white">
                                    <i class="bi bi-chevron-double-left"></i>
                                </a>
                            </div>

                            <div class="col-3 text-end">
                                @if(count($dates) == 1)
                                {{ Carbon\Carbon::parse($tournement->tournement_date)->translatedFormat('l j M \'y')  }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table>

                            <thead><th colspan="2">ronde en tijd</th>
                            @foreach($pitches as $pitch)
                                <th class="text-center">{{ $pitch->pitch_name }}</th>
                            @endforeach
                            </thead>

                            <?php
                                $vorigeronde = 0;
                                $vorigerondetijd = "";
                                $colspan = 2 + count($pitches);
                            ?>
                            @foreach($games as $wedstrijd)
                                @if(count($dates) > 1)
                                        @if( date('d', strtotime($wedstrijd->round->start))  <> date('d', strtotime($vorigerondetijd)) )
                                        <tr>
                                            <td class="border-end" colspan="{{ $colspan }}">
                                                <i>{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('l j F') }}</i>
                                                    <?php
                                                    $vorigerondetijd = $wedstrijd->round->start;
                                                    ?>
                                            </td>
                                        </tr>
                                        @endif
                                @endif
                                @if($wedstrijd->round->round_nr <> $vorigeronde)
                                    @php($vorigeronde = $wedstrijd->round->round_nr)
                                <tr>
                                    <td class="border-end align-middle p-2"><span class="d-grid">
                                        <button class="btn btn-sm btn-secondary py-0 px-1">
                                            {{ $wedstrijd->round->round_nr }}
                                        </button></span>
                                    </td>
                                    <td class="border-end align-middle p-2">
                                        {{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('H:i') }}
                                    - {{ Carbon\Carbon::parse($wedstrijd->round->end)->translatedFormat('H:i') }}
                                    </td>

                                @endif
                                    <?php
                                        if(count($pitches) > 4 || is_null($wedstrijd->hometeam->team_name))
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
                                    <td class="droppable">
                                        <div id="grp_{{ $wedstrijd->id }}_{{ $wedstrijd->round_id }}_{{ $wedstrijd->pitch_id }}_silvia" class="draggable">{{ $hometeamname }} - {{ $awayteamname }}</div>
                                    </td>
                                @if($wedstrijd->round->round_nr <> $vorigeronde)
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection

@section('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
            <script src="{{ asset('js/swap.js') }}"></script>
@endsection
