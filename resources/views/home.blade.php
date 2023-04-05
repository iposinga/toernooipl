@extends('../layouts.app')

@section('content')
<div class="container">
    <?php
        //echo "<pre>";
        //print_r($toernooien);
        //echo "</pre>";
        ?>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background-color: #29286d; color: white;">
                    <div class="row">
                        <div class="col" style="font-size: larger">{{ __('Dashboard') }}</div>
                        <div class="col text-end">
                            <a style="text-decoration: none;" type="button" data-bs-toggle="modal" data-bs-target="#edit-modal" onclick="showAddTournement()">
                                <i class="bi bi-plus-square"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (Session::get('success', false))
                        <?php $data = Session::get('success'); ?>
                        <div class="alert alert-success" role="alert">
                            {{ $data }}
                        </div>
                    @endif
                    <table class="table table-sm table-hover w-auto">
                @foreach($toernooien as $toernooi)
                            <tr style="font-size: larger">
                                <td><a style="text-decoration: none; color: #e2007c" href="{{ route('tournement.show', $toernooi->id) }}">{{ $toernooi->tournement_name }}</a></td>
                                <td>{{ Carbon\Carbon::parse($toernooi->tournement_date)->translatedFormat('j F Y')  }}</td>
                            </tr>
                @endforeach
                    </table>
                </div>
            </div>
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
    <script>
        function showAddTournement()
        {
            let form = `<form id="add_toernooi_form" class="row g-3" method="post" action="{{ route('storetournement') }}">
             {{ csrf_field() }}
            <div class="col-md-6">
                <label for="inputToernooinaam" class="form-label">Naam van het toernooi</label>
                <input type="text" class="form-control" id="inputToernooinaam" name="inputToernooinaam" required>
            </div>

            <div class="col-md-6">
                <label for="inputDatum" class="form-label">Datum en starttijd</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="inputDatum" name="inputDatum" data-date-language="nl" data-date-weekstart="1" data-date-autoclose="true" data-date-today-btn="true" data-date-format="dd-mm-yyyy HH:ii" onclick="$('#inputDatum').datetimepicker('show');" required>
                    <span class="input-group-text" onclick="$('#inputDatum').datetimepicker('show');"><i class="bi bi-calendar3"></i></span>
                </div>
            </div>

            <div class="col-md-2">
                <label for="inputTeams" class="form-label"># Teams</label>
                <input type="text" class="form-control" id="inputTeams" name="inputTeams" required>
            </div>
            <div class="col-md-2">
                <label for="inputPoules" class="form-label"># Poules</label>
                <input type="text" class="form-control" id="inputPoules" name="inputPoules" required>
            </div>
            <div class="col-md-2">
                <label for="inputVelden" class="form-label"># Velden</label>
                <input type="text" class="form-control" id="inputVelden" name="inputVelden" required>
            </div>
            <div class="col-md-3">
                <label for="inputDuur" class="form-label"><i class="bi bi-hourglass" style="font-size: 14px"></i> Wedstrijd</label>
                <input type="text" class="form-control" id="inputDuur" name="inputDuur" required>
                <span class="help-block text-muted">in minuten</span>
            </div>
            <div class="col-md-3">
                <label for="inputPauze" class="form-label"><i class="bi bi-hourglass-split" style="font-size: 14px"></i> Wisseltijd<sup> *</sup></label>
                <input type="text" class="form-control" id="inputPauze" name="inputPauze" value="0" required>
                <span class="help-block text-muted">in minuten</span>
            </div>
            <div class="col-md-12">
                <div class="form-check form-switch form-check-lg">
                    <input class="form-check-input" type="checkbox" role="switch" id="inputCompetitieType" name="inputCompetitieType" value="1">
                    <label class="form-check-label" for="inputCompetitieType">De teams spelen een hele (i.p.v. halve) competitie in de poule</label>
                </div>
            </div>
            <div class="col-md-12">
            <span class="help-block text-muted"><sup>* </sup>de duur van de pauzes tussen de speelrondes</span>
            </div>
            </form>`
            let buttons = `<button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Annuleer</button>
                <button type="submit" form="add_toernooi_form" class="btn btn-success">Bewaar</button>`
            $(".modal-title").empty().append("Voeg toernooi toe")
            $(".modal-body").empty().append(form)
            $(".modal-footer").empty().append(buttons)
        }

    </script>
@endsection
