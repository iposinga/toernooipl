function showAddTournement()
{
    let form = `<form id="add_toernooi_form" class="row g-3" method="post" action="/js/tournements/store">
            <input type="hidden" name="_token" value="${ csrftoken }">
            <div class="col-md-6">
                <label for="inputToernooinaam" class="form-label">Naam van het toernooi</label>
                <input type="text" class="form-control" id="inputToernooinaam" name="tournement_name" required>
            </div>
            <div class="col-md-6">
                <label for="inputDatum" class="form-label">Startdatum en -tijd<sup> *</sup></label>
                <div class="input-group">
                    <input type="text" class="form-control" id="inputDatum" name="tournement_date" data-date-language="nl" data-date-weekstart="1" data-date-autoclose="true" data-date-format="dd-mm-yyyy HH:ii" onclick="$('#inputDatum').datetimepicker('show');" required>
                    <span class="input-group-text" onclick="$('#inputDatum').datetimepicker('show');"><i class="bi bi-calendar3"></i></span>
                </div>
            </div>
            <div class="col-md-2">
                <label for="inputTeams" class="form-label"># Teams</label>
                <input type="text" class="form-control" id="inputTeams" name="teams_nmbr" required>
            </div>
            <div class="col-md-2">
                <label for="inputPoules" class="form-label"># Poules</label>
                <input type="text" class="form-control" id="inputPoules" name="poules_nmbr" required>
            </div>
            <div class="col-md-2">
                <label for="inputVelden" class="form-label"># Velden</label>
                <input type="text" class="form-control" id="inputVelden" name="pitches_nmbr" required>
            </div>
            <div class="col-md-3">
                <label for="inputDuur" class="form-label"><i class="bi bi-hourglass" style="font-size: 14px"></i> Wedstrijd</label>
                <input type="text" class="form-control" id="inputDuur" name="game_duration" required>
                <span class="help-block text-muted">in minuten</span>
            </div>
            <div class="col-md-3">
                <label for="inputPauze" class="form-label"><i class="bi bi-hourglass-split" style="font-size: 14px"></i> Wisseltijd<sup> **</sup></label>
                <input type="text" class="form-control" id="inputPauze" name="change_duration" value="0" required>
                <span class="help-block text-muted">in minuten</span>
            </div>
            <div class="col-md-12">
                <div class="form-check form-switch form-check-lg">
                    <input class="form-check-input" type="checkbox" role="switch" id="inputCompetitieType" name="is_entire_comp" value="1">
                    <label class="form-check-label" for="inputCompetitieType">De teams spelen een hele (i.p.v. halve) competitie in de poule</label>
                </div>
            </div>
            <div class="col-md-12">
            <span class="help-block text-muted"><sup>* </sup>een toernooi kan over meerdere dagen worden vesrpreid door de datum en tijd van speelrondes aan te passen na het aanmaken van het toernooi<br>
            <sup>** </sup>de duur van de pauzes tussen de speelrondes</span>
            </div>
            </form>`
    let buttons = `<button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Annuleer</button>
                <button type="submit" form="add_toernooi_form" class="btn btn-success">Bewaar</button>`
    $(".modal-title").empty().append("Voeg toernooi toe")
    $(".modal-body").empty().append(form)
    $(".modal-footer").empty().append(buttons)
}
