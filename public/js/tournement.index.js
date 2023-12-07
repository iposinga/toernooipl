$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function highLight(teamnr){
    if($(".team_" + teamnr).hasClass("alert alert-warning"))
    {
        $("tr").removeClass("alert alert-warning");
        $("td").removeClass("bg-warning");
    }
    else
    {
        $("tr").removeClass("alert alert-warning");
        $("td").removeClass("bg-warning");
        $(".wedstr_" + teamnr).addClass("bg-warning");
        $(".team_" + teamnr).addClass("alert alert-warning");
    }
}
function highLightClub(clubid){
    if($(".clubnaam_" + clubid).hasClass("alert alert-warning"))
    {
        $("tr").removeClass("alert alert-warning");
        $("td").removeClass("bg-warning");
    }
    else
    {
        $("tr").removeClass("alert alert-warning");
        $("td").removeClass("bg-warning");
        $(".club_" + clubid).addClass("bg-warning");
        $(".clubnaam_" + clubid).addClass("alert alert-warning");
    }
}
function showAddTournementusers(tournement_id)
{
    $.ajax({
        url: '/aj/tournement_users/add',
        data: {
            tournement_id: tournement_id
        },
        dataType: 'json',
        type: 'get',
        success: function (data){
            let buttons = `<button id="primaire-btn" type="submit" form="add_users_form" class="btn btn-success">Bewaar</button>`;
            $(".modal-title").empty().append("Voeg gebruiker(s) toe");
            $(".modal-body").empty().append(data.html);
            $(".modal-footer").empty().append(buttons);
        },
        error: function (){
            alert("Het is niet gelukt de users te tonen die je mogelijk toe zou kunnen voegen. $tournement_id = " + tournement_id);
        }
    })
}
function showEditUitslag(id, poule_id, thuisteam, uitteam, thuisscore, uitscore)
{
    let form = `<form id="edit_uitslag_form" class="row g-3" onsubmit="updateUitslag(); return false">
                    <div class="col-md-2">
                        <input type="hidden" id="inputId" value="${ id }">
                        <input type="hidden" id="inputPouleId" value="${ poule_id }">
                    </div>
                    <div class="col-md-4 text-center">
                        <label id="labelHomeScore" for="inputHomeScore" class="form-label">${ thuisteam }</label>
                        <input type="text" class="form-control text-center" id="inputHomeScore" value="${ thuisscore }">
                    </div>
                    <div class="col-md-4 text-center">
                        <label id="labelAwayScore" for="inputAwayScore" class="form-label">${ uitteam }</label>
                        <input type="text" class="form-control text-center" id="inputAwayScore" value="${ uitscore }">
                    </div>
                    </form>`;
    let buttons = `<button id="secondaire-btn" type="button" onclick="deleteUitslag(${ id }, ${ poule_id }, '${ thuisteam }', '${ uitteam }'); return false;" class="btn btn-danger">Verwijder</button>
                          <button id="secondaire-btn" type="reset" form="edit_uitslag_form" class="btn btn-secondary">Reset</button>
                          <button id="primaire-btn" type="submit" form="edit_uitslag_form" class="btn btn-success">Bewaar</button>`;
    $(".modal-title").empty().append("Edit uitslag")
    $(".modal-body").empty().append(form)
    $(".modal-footer").empty().append(buttons)
    $("#inputHomeScore").focus()
}
function deleteUitslag(id, poule_id, thuisteam, uitteam)
{
    confirm("Weet je het zeker?")
    {
        $.ajax({
            url: '/aj/games/deletescore',
            data: {
                id: id,
                poule_id: poule_id
            },
            type: 'get',
            success: function () {
                $("#uitslag_" + id).empty();
                $("#edit_game_btn_" + id).attr("onclick", "showEditUitslag(" + id + ", " + poule_id + ",'" + thuisteam + "','" + uitteam + "','','')");
                $('#edit-modal').modal('toggle');
            },
            error: function () {
                alert("Het is niet goed gegaan met het verwijderen van de uitslag; game_id = " + id);
            }
        })
    }
}
function updateUitslag()
{
    let id = $("#inputId").val()
    let poule_id = $("#inputPouleId").val()
    let homescore = parseInt($("#inputHomeScore").val());
    let awayscore = parseInt($("#inputAwayScore").val());
    //de waarden bij een gelijkspel
    let homepoints = 1
    let awaypoints = 1
    let homewin = 0
    let homedraw = 1
    let homeloss = 0
    let awaywin = 0
    let awaydraw = 1
    let awayloss = 0
    if(!homescore || !awayscore)
    {
        homescore = null
        awayscore = null
        alert("Minimaal 1 van de 2 scores is leeg en dat mag niet")
        return
    }
    else
    {
        if(homescore > awayscore)
        {
            homepoints = 3
            awaypoints = 0
            homewin = 1
            homedraw = 0
            homeloss = 0
            awaywin = 0
            awaydraw = 0
            awayloss = 1
        }
        else if (homescore < awayscore)
        {
            homepoints = 0
            awaypoints = 3
            homewin = 0
            homedraw = 0
            homeloss = 1
            awaywin = 1
            awaydraw = 0
            awayloss = 0
        }
    }
    let hometeam = $("#labelHomeScore").text();
    let awayteam = $("#labelAwayScore").text();
    $.ajax({
        url: '/aj/games/update',
        data: {
            id: id,
            poule_id: poule_id,
            home_score: homescore,
            away_score: awayscore,
            home_points: homepoints,
            away_points: awaypoints,
            home_win: homewin,
            away_win: awaywin,
            home_draw: homedraw,
            away_draw: awaydraw,
            home_loss: homeloss,
            away_loss: awayloss
        },
        type: 'put',
        success: function (){
            //alert("goed gegaan");
            $("#uitslag_" + id).empty()
            if(homescore != null && awayscore != null) {
                $("#uitslag_" + id).append("<span class='badge text-bg-success' style='background-color: #29286d; width: 100%'>" + homescore + " - " + awayscore)
                $("#edit_game_btn_" + id).attr("onclick", "showEditUitslag(" + id + ", " + poule_id + ", '" + hometeam + "', '" + awayteam + "', " + homescore + ", " + awayscore + ")");
            }
            else
                $("#edit_game_btn_" + id).attr("onclick", "showEditUitslag(" + id + ", " + poule_id + ", '" + hometeam + "', '" + awayteam + "', '', '')");
            //sluit de modal
            $('#edit-modal').modal('toggle');
        },
        error: function (){
            alert("Het updaten van de uitslag en de stand is niet goed gegaan; game_id = " + id);
        }
    })
}
function showEditPoule(id, tournement_id)
{
    $.ajax({
        url: '/aj/teams/edit',
        data: {
            id: id,
            tournement_id, tournement_id
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
            alert("Het is niet goed gegaan met het tonen van de teams uit deze poule; poule_id = " + id);
        }
    })
}

function showEditRound(id, tourn_id, round_nr, round_start, round_type, game_duration, change_duration)
{
    const typeoptions1 = {18:'achtste finales', 14: 'kwartfinales', 12: 'halve finales', 11: 'finale', 10: 'anders'}
    const typeoptions0 = {8: 'achtste finales', 4: 'kwartfinales', 2: 'halve finales', 1: 'finale', 0: 'anders'}
    let form = `<form id="edit_round_form" class="row g-3" onsubmit="updateRounds(); return false">
                    <input type="hidden" id="inputRoundId" value="${ id }">
                    <input type="hidden" id="inputTournementId" value="${ tourn_id }">
                    <input type="hidden" id="inputRoundNr" value="${ round_nr }">`
        if(round_type > -1) {
            form += `<div class="col-md-11 offset-md-1">
                        <label for="inputFinalRoundType" class="form-label">Type finaleronde</label>
                        <select id="inputFinalRoundType" class="form-select">
                            <option value="">kies...</option>
                            <optgroup label="teams kiezen op basis van ranking in poules">`
            for (const key in typeoptions1) {
                if(key == round_type)
                    form += `<option value="${key}" selected>${typeoptions1[key]} (o.b.v. ranking in poules)</option>`
                else
                    form += `<option value="${key}">${typeoptions1[key]} (o.b.v. ranking in poules)</option>`
            }
            form += `</optgroup>
                            <optgroup label="teams kiezen op basis van wedstrijduitslagen uit vorige ronde">`
            for (const key in typeoptions0) {
                if(key == round_type)
                    form += `<option value="${key}" selected>${typeoptions0[key]} (o.b.v. uitslagen uit vorige ronde)</option>`
                else
                    form += `<option value="${key}">${typeoptions0[key]} (o.b.v. uitslagen uit vorige ronde)</option>`
            }
            form += `</optgroup>
                        </select>
                     </div>`;
        }
        form += `<div class="col-md-6 offset-md-1">
                    <label for="inputDatum" class="form-label">Datum en starttijd ronde ${ round_nr }</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="inputDatum" name="inputDatum" value="${ round_start }" data-date-language="nl" data-date-weekstart="1" data-date-autoclose="true" data-date-format="dd-mm-yyyy hh:ii" onclick="$('#inputDatum').datetimepicker('show');" required>
                        <span class="input-group-text" onclick="$('#inputDatum').datetimepicker('show');"><i class="bi bi-calendar3"></i></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="inputDuur" class="form-label">Wedstrijdduur</label>
                    <input type="number" class="form-control" id="inputDuur" name="inputDuur" value="${ game_duration }" required>
                </div>
                <div class="col-md-2">
                    <label for="inputWissel" class="form-label">Wisseltijd</label>
                    <input type="number" class="form-control" id="inputWissel" name="inputWissel" value="${ change_duration }" required>
                </div>
                <div class="col-md-11 offset-md-1">
                    <div class="form-text">
                    de rondetijden <i>na</i> deze ronde worden automatisch ook aangepast;<br>
                    dit biedt de mogelijkheid om het toernooi uit te smeren over meerdere dagen
                    </div>
                </div>
                </form>`;
    let delbutton = "";
    if(round_type > -1){
        delbutton = `<button id="delete-btn" type="button" form="edit_round_form" class="btn btn-danger" onclick="deleteFinalRound(${ id }); return false;">Verwijder</button>`
    }
    let buttons = delbutton + `<button id="secondaire-btn" type="reset" form="edit_round_form" class="btn btn-secondary">Reset</button>
                    <button id="primaire-btn" type="submit" form="edit_round_form" class="btn btn-success">Bewaar</button>`;
    $(".modal-title").empty().append("Edit ronde-tijd");
    $(".modal-body").empty().append(form);
    $(".modal-footer").empty().append(buttons);
}

function updateRounds()
{
    let roundid = $("#inputRoundId").val()
    let tournementid = $("#inputTournementId").val()
    let roundnr = $("#inputRoundNr").val()
    let start = $("#inputDatum").val()
    let game_duration = $("#inputDuur").val()
    let change_duration = $("#inputWissel").val()
    let round_type = -1
    if($('#inputFinalRoundType').length)
        round_type = $("#inputFinalRoundType").val()
    $.ajax({
        url: '/aj/rounds/update',
        data: {
            id: roundid,
            inputTournementId: tournementid,
            inputRoundNr: roundnr,
            start: start,
            game_duration: game_duration,
            change_duration: change_duration,
            finalround: round_type
        },
        type: 'put',
        success: function (){
            location.reload();
        },
        error: function (){
            alert("Het is niet gelukt de rondetijden aan te passen; tournement_id = " + id);
        }
    })
}

function showAddFinalround(tournement_id, round_nr, start, duur)
{
    let form = `<form id="add_finalround_form" method="post" class="row g-3" action="/js/rounds/store">
                    <input type="hidden" name="tournement_id" value='${ tournement_id }'>
                    <input type="hidden" name="round_nr" value='${ round_nr }'>
                    <input type="hidden" name="_token" value="${ csrftoken }">
                    <div class="col-md-9 offset-md-1">
                        <label for="inputFinalGameType" class="form-label">Type finaleronde</label>
                        <select id="inputFinalGameType" name="finalround" class="form-select" required>
                            <option value="">kies...</option>
                            <optgroup label="teams kiezen op basis van ranking in poule">
                                <option value="18">achtste finales</option>
                                <option value="14">kwartfinales</option>
                                <option value="12">halve finale</option>
                                <option value="11">finale</option>
                                <option value="10">anders</option>
                            </optgroup>
                            <optgroup label="teams kiezen op basis van wedstrijduitslagen uit vorige ronde">
                                <option value="8">achtste finales</option>
                                <option value="4">kwartfinales</option>
                                <option value="2">halve finale</option>
                                <option value="1">finale</option>
                                <option value="0">anders</option>
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-6 offset-md-1">
                        <label for="inputStartDatum" class="form-label">Datum en starttijd ronde ${ round_nr }</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputStartDatum" name="start" value="${ start }" data-date-language="nl" data-date-weekstart="1" data-date-autoclose="true" data-date-today-btn="true" data-date-format="dd-mm-yyyy hh:ii" onclick="$('#inputDatum').datetimepicker('show');" required>
                            <span class="input-group-text" onclick="$('#inputStartDatum').datetimepicker('show');"><i class="bi bi-calendar3"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <label class="form-label">Wedstrijdduur</label>
                            <input type="text" class="form-control text-center" name="game_duration" value="${ duur }" required>
                    </div>
                        </form>`;
    let buttons = `<button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Annuleer</button>
                    <button type="submit" form="add_finalround_form" class="btn btn-success">Bewaar</button>`;
    $(".modal-title").empty().append("Voeg finaleronde toe");
    $(".modal-body").empty().append(form);
    $(".modal-footer").empty().append(buttons);
}

function showEditFinalGame(id)
{
    $.ajax({
        url: '/aj/finalgames/edit',
        data: {
            id: id
        },
        dataType: 'json',
        type: 'get',
        success: function (data){
            let buttons = `<button class="btn btn-danger" onclick="showDestroyAlert(${ id }); return false;">Verwijder</button>
                            <button type="reset" form="edit_finalewedstr_form" class="btn btn-secondary">Reset</button>
                                <button type="submit" form="edit_finalewedstr_form" class="btn btn-success">Bewaar</button>`;
            $(".modal-title").empty().append("Edit finalewedstrijd");
            $(".modal-body").empty().append(data.html);
            $(".modal-footer").empty().append(buttons);
        },
        error: function (){
            alert("Het is niet gelukt de finalewedstrijd te tonen; Finalgame_id = " + id);
        }
    })
}
function showStandPoule(id, poulenaam)
{
    $.ajax({
        url: '/aj/teams/show',
        data: {
            poule_id: id
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
function showDestroyAlert(tournementid)
{
    let form = `<form id="delete_tournement_form" method="post" class="row g-3" action="/js/tournements/destroy">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="${ csrftoken }">
                    <input type="hidden" name="tournement_id" value="${ tournementid }">
                    <div class="col-md-12">
                        <h3><i>Weet je zeker dat je dit toernooi wilt verwijderen?</i></h3>
                    </div>
                </form>`;
    let buttons = `<button type="submit" form="delete_tournement_form" class="btn btn-danger">Delete</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success">Annuleer</button>`;
    $(".modal-title").empty().append("Verwijder toernooi");
    $(".modal-body").empty().append(form);
    $(".modal-footer").empty().append(buttons);
}
function showDestroyTournementUserAlert(tournementid, userid)
{
    let form = `<form id="delete_tournement_user_form" method="post" class="row g-3" action="/js/tournement_user/destroy">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="${ csrftoken }">
                    <input type="hidden" name="tournement_id" value="${ tournementid }">
                    <input type="hidden" name="user_id" value="${ userid }">
                    <div class="col-md-12">
                        <h3><i>Weet je zeker dat je deze user bij dit toernooi wilt verwijderen?</i></h3>
                    </div>
                </form>`;
    let buttons = `<button type="submit" form="delete_tournement_user_form" class="btn btn-danger">Verwijder definitief</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success">Annuleer</button>`;
    $(".modal-title").empty().append("Verwijder user bij toernooi");
    $(".modal-body").empty().append(form);
    $(".modal-footer").empty().append(buttons);
}

function showEditVelden(id)
{
    $.ajax({
        url: '/aj/pitches/edit',
        data: {
            id: id
        },
        dataType: 'json',
        type: 'get',
        success: function (data){
            let buttons = `<button type="reset" form="edit_pitches_form" class="btn btn-secondary">Reset</button>
                                <button type="submit" form="edit_pitches_form" class="btn btn-success">Bewaar</button>`;
            $(".modal-title").empty().append("Edit velden");
            $(".modal-body").empty().append(data.html);
            $(".modal-footer").empty().append(buttons);
        },
        error: function (){
            alert("Het is niet goed gegaan met het tonen van de velden van dit toernooi; tournement_id = " + id);
        }
    })
}
function showEditClubs(id)
{
    $.ajax({
        url: '/aj/clubs/edit',
        data: {
            id: id
        },
        dataType: 'json',
        type: 'get',
        success: function (data){
            let buttons = `<button type="reset" form="edit_clubs_form" class="btn btn-secondary">Reset</button>
                                <button type="submit" form="edit_clubs_form" class="btn btn-success">Bewaar</button>`;
            $(".modal-title").empty().append("Edit clubs");
            $(".modal-body").empty().append(data.html);
            $(".modal-footer").empty().append(buttons);
        },
        error: function (){
            alert("Het is niet goed gegaan met het tonen van de clubs bij dit toernooi; tournement_id = " + id);
        }
    })
}

function showStandClubs(id)
{
    $.ajax({
        url: '/aj/clubs/show',
        data: {
            tournement_id: id
        },
        dataType: 'json',
        type: 'get',
        success: function (data){
            let buttons = `<button type="button" data-bs-dismiss="modal" class="btn btn-success">Sluit dit venster</button>`;
            $(".modal-title").empty().append("Stand clubs");
            $(".modal-body").empty().append(data.html);
            $(".modal-footer").empty().append(buttons);
        },
        error: function (){
            alert("niet goed gegaan");
        }
    })
}
function showEditClubcomp(id)
{
    $.ajax({
        url: '/aj/clubcomp/edit',
        data: {
            id: id
        },
        dataType: 'json',
        type: 'get',
        success: function (data){
            let buttons = `<button type="reset" form="edit_clubcomp_form" class="btn btn-secondary">Reset</button>
                                <button type="submit" form="edit_clubcomp_form" class="btn btn-success">Bewaar</button>`;
            $(".modal-title").empty().append("Edit naam en clubcompetitie");
            $(".modal-body").empty().append(data.html);
            $(".modal-footer").empty().append(buttons);
        },
        error: function (){
            alert("Het is niet goed gegaan met het tonen van de clubcompetitie-gegevens; tournement_id = " + id);
        }
    })
}
function deleteFinalRound(id)
{
    confirm("Weet je het zeker?")
    {
        $.ajax({
            url: '/aj/rounds/delete',
            data: {
                id: id
            },
            type: 'post',
            success: function () {
                location.reload();
            },
            error: function () {
                alert("Het is niet goed gegaan met het verwijderen van de finaleronde; finalround_id = " + id);
            }
        })
    }
}
