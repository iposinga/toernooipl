@extends('layouts.app')

@section('content')
    <nav class="floating-menu">
        <a type="button" href="{{ route('tournement.videowall', ['id' => $tournement->id]) }}" target="_blank">
            <i class="bi bi-cast"></i>
        </a>
        <a type="button" href="{{ route('tournement.program', ['id' => $tournement->id]) }}" target="_blank">
            <i class="bi bi-printer"></i>
        </a>
        <a type="button" href="{{ route('tournement.gamesheets', ['id' => $tournement->id]) }}" target="_blank">
            <i class="bi bi-files"></i>
        </a>
        <a type="button" href="{{ route('tournement.show', ['id' => $tournement->id, 'poule_id' => 0]) }}">
            <i class="bi bi-arrow-left-right"></i>
        </a>
        <a type="button" href="{{ route('tournement.gamesexport', ['id' => $tournement->id]) }}">
            <i class="bi bi-filetype-xlsx"></i>
        </a>
        <a type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showDestroyAlert({{ $tournement->id }}); return false">
            <i class="bi bi-trash3"></i>
        </a>
    </nav>
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
{{--                            <div class="col-1">
                                <a type="button" href="{{ route('tournement.show', ['id' => $tournement->id, 'poule_id' => 0]) }}" style="color: white">
                                    <i class="bi bi-chevron-double-left"></i>
                                </a>
                            </div>--}}
                            <div class="col-5">Wissel wedstrijden om door drag & drop</div>


                            <div class="col-3 text-end">
                                @if(count($dates) == 1)
                                {{ Carbon\Carbon::parse($tournement->tournement_date)->translatedFormat('D j M \'y')  }}
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
                                    <td class="droppable">
                                        <div id="grp_{{ $wedstrijd->id }}_{{ $wedstrijd->round_id }}_{{ $wedstrijd->pitch_id }}_silvia" class="draggable">{{ $wedstrijd->hometeam->team_nr }} - {{ $wedstrijd->awayteam->team_nr }}</div>
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
