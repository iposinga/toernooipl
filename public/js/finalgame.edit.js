teams = {};
function getTeams(tournement_id)
{
    $.ajax({
        url: '/aj/teams/showinjs',
        data: {
            id: tournement_id
        },
        dataType: 'json',
        type: 'get'
    }).done(function(response){
        teams = response.teams;
    })
}
function getRankingOptions(homeoraway, pouleObj)
{
    let index = -1
    let aatalteams = 0
    if(homeoraway == 0) {
        $("#inputHomeRanking").empty().append("<option value=''>kies...</option>")
        if($("#inputHomePoule").val() != 0) {
            index = $("#inputHomePoule").val().split("_")[0]
            $("#inputHomepoule_Teamsnmbr").val(pouleObj[index].teams_nmbr)
            aantalteams = pouleObj[index].teams_nmbr
        }
        else {
            aantalteams = 5
        }
    }
    else {
        $("#inputAwayRanking").empty().append("<option value=''>kies...</option>")
        if($("#inputAwayPoule").val() != 0) {
            index = $("#inputAwayPoule").val().split("_")[0]
            $("#inputAwaypoule_Teamsnmbr").val(pouleObj[index].teams_nmbr)
            aantalteams = pouleObj[index].teams_nmbr
        }
        else {
            aantalteams = 5
        }
    }
    for(i = 1; i <= aantalteams; i++)
    {
        if(homeoraway == 0)
            $("#inputHomeRanking").append("<option value='" + i + "'>" + i + "</option>")
        else
            $("#inputAwayRanking").append("<option value='" + i + "'>" + i + "</option>")
    }
}

function selectTeamByRanking(homeoraway)
{
    let pouleid = 0
    let ranking = 0
    if(homeoraway == 0) {
        pouleid = $("#inputHomePoule").val().split("_")[1]
        ranking = $("#inputHomeRanking").val()
    }
    else
    {
        pouleid = $("#inputAwayPoule").val().split("_")[1]
        ranking = $("#inputAwayRanking").val()
    }
    $.ajax({
        url: '/aj/teams/stand',
        data: {
            poule_id: pouleid
        },
        dataType: 'json',
        type: 'get',
        success: function (data){
            if(homeoraway == 0) {
                //$("#inputHometeamId").val(data.ranking[ranking - 1].id)
                $("#HomeTeamname").empty().append(data.ranking[ranking - 1].team_name)
                if($(".AwayTeamLableInput").is(":visible"))
                    $(".HomeTeamLableInput").hide()
                else
                    $(".hiddenrow").hide()
            }
            else
            {
                //$("#inputAwayteamId").val(data.ranking[ranking - 1].id)
                $("#AwayTeamname").empty().append(data.ranking[ranking - 1].team_name)
                if($(".HomeTeamLableInput").is(":visible"))
                    $(".AwayTeamLableInput").hide()
                else
                    $(".hiddenrow").hide()
            }
        },
        error: function (){
            //alert("Het is niet gelukt het team bij deze combinatie van poule en stand te vinden.")
            if(homeoraway == 0) {
                if($(".hiddenrow").is(":hidden")) {
                    $(".hiddenrow").show()
                    $(".AwayTeamLableInput").hide()
                }
                $(".HomeTeamLableInput").show()
                $("#HomeTeamname").empty().append("Thuis-team")
                let options = `<option value=''>kies...</option>`
                let i = 0
                while(i < Object.keys(teams).length)
                {
                    if(ranking == teams[i].team_ranking)
                        options += `<option value="${teams[i].id}">${teams[i].poule.poule_name}: ${teams[i].team_name} - ${teams[i].points} | ${teams[i].goaldifference}</option>`
                    i++
                }
                $("#inputHomeTeam").empty().append(options)
            }
            else
            {
                if($(".hiddenrow").is(":hidden"))
                {
                    $(".hiddenrow").show()
                    $(".HomeTeamLableInput").hide()
                }
                $(".AwayTeamLableInput").show()
                $("#AwayTeamname").empty().append("Uit-team")
                let options = `<option value=''>kies...</option>`
                let i = 0
                while(i < Object.keys(teams).length)
                {
                    if(ranking == teams[i].team_ranking)
                        options += `<option value="${teams[i].id}">${teams[i].poule.poule_name}: ${teams[i].team_name} - ${teams[i].points} | ${teams[i].goaldifference}</option>`
                    i++
                }
                $("#inputAwayTeam").empty().append(options)
            }
        }
    })
}

function showDestroyAlert(finalgameid)
{
    let form = `<form id="delete_finalgame_form" method="post" class="row g-3" action="/js/finalgames/destroy">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="${ csrftoken }">
                    <input type="hidden" name="finalgame_id" value="${ finalgameid }">
                    <div class="col-md-12">
                        <h3><i>Weet je zeker dat je deze finalewedstrijd wilt verwijderen?</i></h3>
                    </div>
                </form>`;
    let buttons = `<button type="submit" form="delete_finalgame_form" class="btn btn-danger">Verwijder definitief</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-success">Annuleer</button>`;
    $(".modal-title").empty().append("Verwijder finalewedstrijd");
    $(".modal-body").empty().append(form);
    $(".modal-footer").empty().append(buttons);
}
