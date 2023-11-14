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
            <div class="col-md-2">
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-header" style="background-color: #e2007c; color: white;">
                        <div class="row">
                        <div class="col">Users</div>
                        <div class="col text-end">
                            <a type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showAddTournementusers({{ $tournement->id }}); return false">
                                <i class="bi bi-plus-square"></i>
                            </a>
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            @foreach($tournement->users as $user)
                            <tr><td>{{ $user->name }}</td>
                                <td class="text-end">@if($user->id <> Auth::user()->id)
                                    <a type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showDestroyTournementUserAlert({{ $tournement->id }}, {{ $user->id }}); return false">
                                        <i class="bi bi-trash"></i>
                                    </a>@endif</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-header" style="background-color: #e2007c; color: white;">Instellingen</div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <tr><td><i class="bi bi-hash"></i> teams</td>
                                <td class="text-end">{{ $tournement->teams_nmbr }}</td>
                            </tr>
                            <tr><td><i class="bi bi-hash"></i> poules</td>
                                <td class="text-end">{{ $tournement->poules_nmbr }}</td>
                            </tr>
                            <tr><td><i class="bi bi-hash"></i> velden</td>
                                <td class="text-end">{{ $tournement->pitches_nmbr }}</td>
                            </tr>
                            <tr><td><i class="bi bi-clock"></i> aanvang</td>
                                <td class="text-end">{{ Carbon\Carbon::parse($tournement->tournement_date)->translatedFormat('G:i')  }} u</td>
                            </tr>
                            <tr><td><i class="bi bi-hourglass"></i> wedstrijd</td>
                                <td class="text-end">{{ $tournement->game_duration }} m</td>
                            </tr>
                            <tr><td><i class="bi bi-hourglass-split"></i> wisseltijd</td>
                                <td class="text-end">{{ $tournement->change_duration }} m</td>
                            </tr>
                            <tr><td colspan="2">
                                    @if($tournement->is_entire_comp == 1)
                                        <i class="bi bi-signpost-2"></i> hele
                                    @else
                                        <i class="bi bi-signpost"></i> halve
                                    @endif
                                    competitie
                                </td>
                            </tr>
{{--
                            <tr><td># dagen</td><td class="text-end"><?php echo count($dates) ?></td></tr>
--}}
                        </table>
                    </div>
                </div>
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-header" style="background-color: #e2007c; color: white;">
                        <div class="row">
                            <div class="col">Velden</div>
                            <div class="col text-end"><a type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showEditVelden({{ $tournement->id }}); return false">
                                    <i class="bi bi-pencil-square"></i>
                                </a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover w-auto">
                        @foreach($pitches  as $pitch)
                                <tr><td class="text-end">{{ $pitch->pitch_nr }}.</td><td>{{ $pitch->pitch_name }} @if($pitch->pitch_spot <> '')({{ $pitch->pitch_spot }})@endif</td></tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
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
                            <div class="col-1">
                                <a type="button" href="{{ route('tournement.videowall', ['id' => $tournement->id]) }}" style="color: white" target="_blank">
                                <i class="bi bi-fullscreen"></i>
                                </a>
                            </div>
                            <div class="col-1 text-center">
                                <a type="button" href="{{ route('tournement.program', ['id' => $tournement->id]) }}" style="color: white" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                            </div>
                            <div class="col-1 text-center">
                                <a type="button" href="{{ route('tournement.gamesheets', ['id' => $tournement->id]) }}" style="color: white" target="_blank">
                                    <i class="bi bi-files"></i>
                                </a>
                            </div>
                            <div class="col-1 text-end">
                                <a type="button" href="{{ route('tournement.gamesexport', ['id' => $tournement->id]) }}" style="color: white">
                                    <i class="bi bi-filetype-xlsx"></i>
                                </a>
                            </div>
                            <div class="col-4 text-end">
                                @if(count($dates) == 1)
                                {{ Carbon\Carbon::parse($tournement->tournement_date)->translatedFormat('l j M \'y')  }}
                                @endif
                                    &nbsp;&nbsp;<a type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showDestroyAlert({{ $tournement->id }}); return false">
                                        <i class="bi bi-trash3"></i>
                                    </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table table-hover w-auto">

                            <thead><th colspan="2">ronde en tijd</th>
                            @foreach($pitches as $pitch)
                                <th>{{ $pitch->pitch_name }}</th>
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
                                    <td class="border-end align-middle"><span class="d-grid">
                                        <button class="btn btn-sm btn-secondary py-0 px-1" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showEditRound( {{ $wedstrijd->round->id }}, {{$tournement->id}}, {{ $wedstrijd->round->round_nr }}, '{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('j-m-Y H:i') }}', {{ $wedstrijd->round->finalround }}); return false;">
                                            {{ $wedstrijd->round->round_nr }}
                                        </button></span>
                                    </td>
                                    <td class="border-end align-middle">
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
                                    <td class="wedstr_{{ $wedstrijd->hometeam->team_nr }} wedstr_{{ $wedstrijd->awayteam->team_nr }} text-center border-end align-middle" style="padding-bottom: 0px">
                                    <a id="edit_game_btn_{{ $wedstrijd->id }}" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showEditUitslag({{ $wedstrijd->id }}, {{ $wedstrijd->hometeam->poule_id }}, '{{ $wedstrijd->hometeam->team_nr }}: {{ $wedstrijd->hometeam->team_name }}', '{{ $wedstrijd->awayteam->team_nr }}: {{ $wedstrijd->awayteam->team_name }}', '{{ $wedstrijd->home_score }}', '{{ $wedstrijd->away_score }}'); return false">
                                        {{ $hometeamname }} - {{ $awayteamname }}
                                        <h6 id="uitslag_{{ $wedstrijd->id }}">
                                            @if($wedstrijd->home_score != "")
                                                <span class="badge text-bg-success" style="background-color: #29286d; width: 100%">{{ $wedstrijd->home_score }} - {{ $wedstrijd->away_score }}</span>
                                            @endif
                                        </h6>
                                    </a>
                                    </td>
                                @if($wedstrijd->round->round_nr <> $vorigeronde)
                                    </tr>
                                @endif
                                @if ($loop->last)
                                    <?php
                                          $startnieuweronde = date('d-m-Y H:i', strtotime($wedstrijd->round->end . ' +' . $tournement->change_duration . ' minutes'));
                                          $nieuwerondenr = $wedstrijd->round->round_nr + 1;
                                    ?>
                                @endif
                            @endforeach

                                <tr>
                                    <td class="border-end" colspan="{{ $colspan }}">
                                    @if(count($finalgames) == 0)
                                    <a style="text-decoration: none;" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showAddFinalround({{ $tournement->id }}, {{ $nieuwerondenr }}, '{{ $startnieuweronde }}', {{ $tournement->game_duration }})">
                                        <i class="bi bi-plus-square"></i>
                                    </a>
                                    @endif
                                    <i>Finalerondes</i></td>
                                </tr>
                            <?php
                                $vorigeronde = 0;
                                $vorigerondetijd = "";
                                $colspan = 2 + count($pitches);
                            ?>
                            @foreach($finalgames as $finalewedstrijd)
                                @if($finalewedstrijd->round->round_nr <> $vorigeronde)
                                        <?php $vorigeronde = $finalewedstrijd->round->round_nr; ?>
                                    <tr>
                                        <td class="border-end align-middle"><span class="d-grid">
                                            <button class="btn btn-sm btn-secondary py-0 px-1" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showEditRound( {{ $finalewedstrijd->round->id }}, {{$tournement->id}}, {{ $finalewedstrijd->round->round_nr }}, '{{ Carbon\Carbon::parse($finalewedstrijd->round->start)->translatedFormat('j-m-Y H:i') }}', {{ $finalewedstrijd->round->finalround }}); return false;">
                                                {{ $finalewedstrijd->round->round_nr }}
                                            </button></span>
                                        </td>
                                        <td class="border-end align-middle">
                                            {{ Carbon\Carbon::parse($finalewedstrijd->round->start)->translatedFormat('H:i') }}
                                            - {{ Carbon\Carbon::parse($finalewedstrijd->round->end)->translatedFormat('H:i') }}
                                        </td>
                                @endif
                                        <td class="wedstr_{{ $finalewedstrijd->hometeam_label }} wedstr_{{ $finalewedstrijd->awayteam_label }} text-center border-end align-middle" style="padding-bottom: 0px; font-size: smaller">
                                            <a id="edit_game_btn_{{ $finalewedstrijd->id }}" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showEditFinalGame({{ $finalewedstrijd->id }}); return false">
                                                @if(!empty($finalewedstrijd->name))
                                                    {{$finalewedstrijd->name}}<br>
                                                @endif
                                                @if(is_null($finalewedstrijd->homepoule_id) && is_null($finalewedstrijd->awaypoule_id))
                                                    @if($finalewedstrijd->home_winnergame_id > 0 || $finalewedstrijd->away_winnergame_id > 0)
                                                            {{ $finalewedstrijd->homeparent->name }} - {{ $finalewedstrijd->awayparent->name }}
                                                    @else
                                                            <span style="font-size: large"><i class="bi bi-info-square"></i></span>
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
                                                <h6 id="uitslag_{{ $finalewedstrijd->id }}">
                                                    @if($finalewedstrijd->home_score != "")
                                                        <span class="badge text-bg-success" style="background-color: #29286d; width: 100%">{{ $finalewedstrijd->home_score }} - {{ $finalewedstrijd->away_score }}</span>
                                                    @endif
                                                </h6>
                                            </a>
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
                            @if(count($finalgames) > 0)
                                <tr>
                                    <td class="border-end" colspan="{{ $colspan }}">
                                        <a style="text-decoration: none;" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showAddFinalround({{ $tournement->id }}, {{ $nieuwerondenr }}, '{{ $startnieuweronde }}', {{ $tournement->game_duration }})">
                                            <i class="bi bi-plus-square"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif


                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                    <div class="accordion" id="accordionPoules">
                        @foreach($poules as $poule)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading_{{ $poule->poule_name }}">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $poule->poule_name }}" aria-expanded="true" aria-controls="collapse_{{ $poule->poule_name }}">
                                    Poule {{ $poule->poule_name }}
                                </button>
                            </h2>
                            <div id="collapse_{{ $poule->poule_name }}" class="accordion-collapse collapse @if ($focuspoule == $poule->id) show @endif" aria-labelledby="heading_{{ $poule->poule_name }}" data-bs-parent="#accordionPoules">
                                <div class="accordion-body" style="padding: 10px 8px; background-color: white">
                                    <table class="table table-sm w-auto">
                                        @foreach($poule->teams as $team)
                                            <tr class="team_{{ $team->team_nr }}">
                                                <td class="text-end"><a class="link-dark link-underlineless" href="#" onclick="highLight({{ $team->team_nr }})">{{ $team->team_nr }}.</a></td>
                                                <td><a class="link-dark link-underlineless" href="#" onclick="highLight({{ $team->team_nr }})">{{ $team->team_name }}</a></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    <div class="row">
                                    <div class="d-grid col-4 mx-auto">
                                    <button class="btn btn-sm" style="background-color: #9ec4d5;" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showEditPoule({{ $poule->id }}); return false">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    </div>
                                    <div class="d-grid col-4 mx-auto">
                                        <a href="{{ route('poules.show', $poule->id) }}" target="_blank" class="btn btn-sm" style="background-color: #9ec4d5;">
                                            <i class="bi bi-window-fullscreen"></i>
                                        </a>
                                    </div>
                                    <div class="d-grid col-4 mx-auto">
                                        <a class="btn btn-sm" style="background-color: #9ec4d5;" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showStandPoule({{ $poule->id }}, '{{ $poule->poule_name }}'); return false">
                                            <i class="bi bi-info-square"></i>
                                        </a>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
            </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="edit-modal" tabindex="-1" aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal-label"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/tournement.index.js') }}"></script>
@endsection
