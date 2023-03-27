@extends('layouts.app')

@section('content')
    <div class="container">
        <?php
        //echo "<pre>";
        //print_r($pitches);
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
                            <a type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showAddTournementusers({{ $wedstrijden[0]->round->tournement->id }}); return false">
                                <i class="bi bi-plus-square"></i>
                            </a>
                        </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach($wedstrijden[0]->round->tournement->users as $user)
                                <li>{{ $user->name }} </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-header" style="background-color: #e2007c; color: white;">Instellingen</div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <tr><td><i class="bi bi-hash"></i> teams</td>
                                <td class="text-end">{{ $wedstrijden[0]->round->tournement->teams_nmbr }}</td>
                            </tr>
                            <tr><td><i class="bi bi-hash"></i> poules</td>
                                <td class="text-end">{{ $wedstrijden[0]->round->tournement->poules_nmbr }}</td>
                            </tr>
                            <tr><td><i class="bi bi-hash"></i> velden</td>
                                <td class="text-end">{{ $wedstrijden[0]->round->tournement->pitches_nmbr }}</td>
                            </tr>
                            <tr><td><i class="bi bi-clock"></i> aanvang</td>
                                <td class="text-end">{{ Carbon\Carbon::parse($wedstrijden[0]->round->tournement->tournement_date)->translatedFormat('G:i')  }} u</td>
                            </tr>
                            <tr><td><i class="bi bi-hourglass"></i> wedstrijd</td>
                                <td class="text-end">{{ $wedstrijden[0]->round->tournement->match_duration }} m</td>
                            </tr>
                            <tr><td><i class="bi bi-hourglass-split"></i> wisseltijd</td>
                                <td class="text-end">{{ $wedstrijden[0]->round->tournement->change_duration }} m</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card" style="margin-bottom: 10px;">
                    <div class="card-header" style="background-color: #e2007c; color: white;">
                        <div class="row">
                            <div class="col">Velden</div>
                            <div class="col text-end"><i class="bi bi-pencil-square"></i></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul>
                        @foreach($pitches  as $pitch)
                                <li>veld {{ $pitch->pitch_name }} ({{ $pitch->pitch_spot }})</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background-color: #29286d; color: white;">
                        <div class="row" style="font-size: larger">
                            <div class="col-4">
                                {{ $wedstrijden[0]->round->tournement->tournement_name }}
                            </div>
                            <div class="col-2 text-end">
                                <i class="bi bi-fullscreen"></i>
                            </div>
                            <div class="col-2">
                                <a type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showDestroyAlert({{ $wedstrijden[0]->round->tournement->id }}); return false">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </div>
                            <div class="col-4 text-end">
                                {{ Carbon\Carbon::parse($wedstrijden[0]->round->tournement->tournement_date)->translatedFormat('l j F Y')  }}
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
                            <thead><th colspan="2">ronde</th><th colspan="3">tijd</th><th>veld</th><th colspan="3">wedstrijd</th><th colspan="4">uitslag</th></thead>
                           @foreach($wedstrijden as $wedstrijd)
                                <tr class="wedstr_{{ $wedstrijd->hometeam->team_nr }} wedstr_{{ $wedstrijd->awayteam->team_nr }}">
                                    <td><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                            <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                                        </svg></td>
                                    <td class="text-end">{{ $wedstrijd->round->round_nr }}.</td>
                                    <td>{{ Carbon\Carbon::parse($wedstrijd->round->start)->translatedFormat('H:i') }} uur</td>
                                    <td>-</td>
                                    <td>{{ Carbon\Carbon::parse($wedstrijd->round->end)->translatedFormat('H:i') }} uur</td>
                                    <td class="text-center">{{ $wedstrijd->pitch->pitch_name }}</td>
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
                                    <td><a id="edit_game_btn_{{ $wedstrijd->id }}" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showEditUitslag({{ $wedstrijd->id }}, '{{ $wedstrijd->hometeam->team_name }}', '{{ $wedstrijd->awayteam->team_name }}', '{{ $wedstrijd->home_score }}', '{{ $wedstrijd->away_score }}'); return false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                        </svg>
                                        </a></td>
                                </tr>

                            @endforeach

                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                    <div class="accordion" id="accordionPoules">
                        @foreach($poules as $poule)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Poule {{ $poule->poule_name }}
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body" style="padding: 10px 8px; background-color: white">
                                    <table class="table table-sm w-auto">
                                        @foreach($poule->teams as $team)
                                            <tr class="team_{{ $team->team_nr }}">
                                                <td class="text-end"><a class="link-dark link-underlineless" href="#" onclick="highLight({{ $team->team_nr }})">{{ $team->team_nr }}.</a></td>
                                                <td><a class="link-dark link-underlineless" href="#" onclick="highLight({{ $team->team_nr }})">{{ $team->team_name }}</a></td>
                                            </tr>
                                        @endforeach
                                    </table>
                                    {{--<ol>
                                    @foreach($poule->teams as $team)
                                            <li class="team_{{ $team->team_nr }}"><a href="#" onclick="highLight({{ $team->team_nr }})">{{ $team->team_name }}</a> </li>
                                    @endforeach
                                    </ol>--}}
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
{{--                    <form id="edit_uitslag_form" class="row g-3">
                    </form>--}}
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
         <script>
            /*$(document).ready(function() {
                 $("#primaire-btn").click(function(event){
                     event.preventDefault();
                     updateUitslag();
                 });
             });*/

             function showEditUitslag(id, thuisteam, uitteam, thuisscore, uitscore)
             {
                 let form = `<form id="edit_uitslag_form" class="row g-3" onsubmit="updateUitslag(); return false">
                    {{--{{ method_field('PUT') }}
                    {{ csrf_field() }}--}}
                    <div class="col-md-2">
                        <input type="hidden" id="inputId" value="${ id }">
                    </div>
                    <div class="col-md-4">
                        <label id="labelHomeScore" for="inputHomeScore" class="form-label">${ thuisteam }</label>
                        <input type="text" class="form-control text-center" id="inputHomeScore" name="inputHomeScore" value="${ thuisscore }">
                    </div>
                    <div class="col-md-4">
                        <label id="labelAwayScore" for="inputAwayScore" class="form-label">${ uitteam }</label>
                        <input type="text" class="form-control text-center" id="inputAwayScore" name="inputAwayScore" value="${ uitscore }">
                    </div>
                    </form>`;
                 let buttons = `<button id="secondaire-btn" type="reset" form="edit_uitslag_form" class="btn btn-secondary">Reset</button>
                    <button id="primaire-btn" type="submit" form="edit_uitslag_form" class="btn btn-success">Bewaar</button>`;
                $(".modal-title").empty().append("Edit uitslag");
                $(".modal-body").empty().append(form);
                $(".modal-footer").empty().append(buttons);
            }

            function updateUitslag()
            {
                let id = $("#inputId").val();
                let homescore = $("#inputHomeScore").val();
                let awayscore = $("#inputAwayScore").val();
                if(!homescore || !awayscore)
                {
                    homescore = '';
                    awayscore = '';
                }
                let hometeam = $("#labelHomeScore").text();
                let awayteam = $("#labelAwayScore").text();
                let url = "{{ route('games.update', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    data: {
                        /*_method: 'put',*/
                        _token: '{{ csrf_token() }}',
                        inputHomeScore: homescore,
                        inputAwayScore: awayscore
                    },
                    type: 'put',
                    success: function (){
                        //alert("goed gegaan");
                        $("#homescore_" + id).empty().append(homescore);
                        $("#awayscore_" + id).empty().append(awayscore);
                        //$("#edit_game_btn_" + id).attr("onclick", changeLastTwoFunctionParam($("#edit_game_btn_" + id).attr("onclick"), homescore, awayscore));
                        $("#edit_game_btn_" + id).attr("onclick", "showEditUitslag(" + id + ", '" + hometeam + "', '" + awayteam + "', " + homescore + ", " + awayscore + ")");
                        //sluit de modal
                        $('#edit-modal').modal('toggle');
                    },
                    error: function (){
                        alert("niet goed gegaan" + id);
                    }
                })
            }

            function showEditPoule(id)
            {
               let url = "{{ route('poules.edit', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    type: 'get',
                    success: function (data){
                        let buttons = `<button type="reset" form="edit_poule_form" class="btn btn-secondary">Reset</button>
                                <button type="submit" form="edit_poule_form" class="btn btn-success">Bewaar</button>`;
                        $(".modal-title").empty().append("Edit poule");
                        $(".modal-body").empty().append(data.html);
                        $(".modal-footer").empty().append(buttons);
                },
            error: function (){
                    alert("niet goed gegaan");
                    }
                })
            }

            function showAddTournementusers(tournementid)
            {
                let url = "{{ route('adduserdata', ':id') }}";
                url = url.replace(':id', tournementid);
                $.ajax({
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    type: 'post',
                    success: function (data){
                        let buttons = `<button id="primaire-btn" type="submit" form="add_users_form" class="btn btn-success">Bewaar</button>`;
                        $(".modal-title").empty().append("Voeg gebruiker(s) toe");
                        $(".modal-body").empty().append(data.html);
                        $(".modal-footer").empty().append(buttons);
                    },
                    error: function (){
                        alert("niet goed gegaan");
                    }
                })
            }

            function showDeleteAlert(tournementid)
            {
                let url = "{{ route('tournements.destroy', ':id') }}";
                url = url.replace(':id', tournementid);
                let form = `<form id="delete_tournement_form" method="delete" class="row g-3" action="${url}">
                 {{ csrf_field() }}
                <div class="col-md-12">
                    <h3><i>Weet je zeker dat je dit toernooi wilt verwijderen?</i></h3>
                    </div>
                        </form>`;
                let buttons = `<button type="submit" form="delete_tournement_form" class="btn btn-danger">Verwijder</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success">Annuleer</button>`;
                $(".modal-title").empty().append("Verwijder toernooi");
                $(".modal-body").empty().append(form);
                $(".modal-footer").empty().append(buttons);
            }

            function showDestroyAlert(tournementid)
            {
                let url = "{{ route('tournements.destroy', ':id') }}";
                url = url.replace(':id', tournementid);
                let form = `<form id="delete_tournement_form" method="post" class="row g-3" action="${url}">
                    @method('delete')
                    @csrf
                <div class="col-md-12">
                    <h3><i>Weet je zeker dat je dit toernooi wilt verwijderen?</i></h3>
                    </div>
                        </form>`;
                let buttons = `<button type="submit" form="delete_tournement_form" class="btn btn-danger">Delete</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success">Annuleer</button>`;
                $(".modal-title").empty().append("Destroy toernooi");
                $(".modal-body").empty().append(form);
                $(".modal-footer").empty().append(buttons);
            }

            function showStandPoule(id, poulenaam)
            {
                let url = "{{ route('poules.show_stand', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    type: 'get',
                    success: function (data){
                        let buttons = `<button type="button" data-bs-dismiss="modal" class="btn btn-success">Sluit dit venster</button>`;
                        $(".modal-title").empty().append("Stand poule " + poulenaam);
                        $(".modal-body").empty().append(data.html);
                        $(".modal-footer").empty().append(buttons);
                    },
                    error: function (){
                        alert("niet goed gegaan");
                    }
                })
            }

            function highLight(teamnr){
                if($(".team_" + teamnr).hasClass("alert alert-primary"))
                {
                    $("tr").removeClass("alert alert-primary");
                    $("li").removeClass("alert alert-primary");
                }
                else
                {
                    $("tr").removeClass("alert alert-primary");
                    $("li").removeClass("alert alert-primary");
                    $(".wedstr_" + teamnr).addClass("alert alert-primary");
                    $(".team_" + teamnr).addClass("alert alert-primary");
                }
            }

         </script>
@endsection
