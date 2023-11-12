//toernid is nodig om de teamnaam bij het teamnr te vinden
// Get the modal
var modal = document.getElementById("myModal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];


// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
function voerUitslagIn(wedstrid,toernid)
{
    $.ajax({
        url: 'jsphp/wedstrijdjs.php',
        data: {
            action: 'uitslaginvoer',
            wedstr: wedstrid,
            toern: toernid
        },
        dataType: 'json',
        type: 'post',
        success: function(data) {
            //alert("gaat goed");
            modal.style.display = "block";
            $(".modal-title").empty().append("Voer uitslag in");
            $(".modal-body").empty().append(data);
            let footerbtns = `<button style='background-color: red; color: black; height: 40px; width: 100px' type='button' onclick="verwijderUitsl(${wedstrid}); return false;">Verwijder</button>
                <button style='background-color: lightgray; color: black; height: 40px; width: 100px' type='reset' form='uitslaginvoerForm'>Reset</button>
                <button style='background-color: lightgreen; color: black; height: 40px; width: 100px' type='button' onclick="bewaarUitsl(${wedstrid}, ${toernid}); return false;">Bewaar</button>`;
            $(".modal-footer").empty().html(footerbtns);
        },
        error: function() { alert("Het lukt op dit moment niet om de uitslag aan te passen!"); }
    });
}
function bewaarUitsl(wedstrid, toernid)
{
    let thuisscore=$("#homescore").val();
    let uitscore=$("#outscore").val();
    if(thuisscore!='' && uitscore!='')
    {
        $.ajax({
            url: 'jsphp/wedstrijdjs.php',
            data: {
                action: 'uitslagvastleggen',
                wedstr: wedstrid,
                toern: toernid,
                thuissc: thuisscore,
                uitsc: uitscore
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                modal.style.display = "none";
                //let link = `<a href='#' title='${data[0]} ${thuisscore} - ${uitscore} ${data[1]}' onclick="voerUitslagIn(${wedstrid}, ${data[2]}); return false;">${data[0]} - ${data[1]}</a>`;
                let link2 = `<a href='#' title='${data.thuisploeg} ${thuisscore} - ${uitscore} ${data.uitploeg}' onclick="voerUitslagIn(${wedstrid}, ${toernid}); return false;">${data.thuisnaam} - ${data.uitnaam}</a>&nbsp;<span style='color: green;'>&#10003;</span>`;
                console.dir(data);
                $("#wedstr_" + wedstrid).empty().html(link2);
            },
            error: function() { alert("Het lukt op dit moment niet om de uitslag aan te passen!"); }
        });
    }
    else
        alert("Je moet beide scores invullen!");
}

function verwijderUitsl(wedstrid)
{
    if(confirm("Weet je het zeker?")) {
        $.ajax({
            url: 'jsphp/wedstrijdjs.php',
            data: {
                action: 'uitslagverwijderen',
                wedstr: wedstrid
            },
            type: 'post',
            //dataType: 'json',
            success: function () {
                modal.style.display = "none";
                //$("#wedstr_" + wedstrid).empty().html(returndata);
            },
            error: function () {
                alert("Het lukt op dit moment niet om de uitslag te verwijderen!");
            }
        });
    }
}

function showTeamSchema(toernid,teamnr)
{
    $.ajax({
        url: 'jsphp/wedstrijdjs.php',
        data: {
            action: 'teamschema',
            toern: toernid,
            team: teamnr
        },
        dataType: 'json',
        type: 'post',
        success: function(data) {
            modal.style.display = "block";
            $(".modal-title").empty().append("Speelschema team " + teamnr);
            $(".modal-body").empty().append(data);
            $(".modal-footer").empty();
        },
        error: function() { alert("Het lukt op dit moment niet om het schema van dit team te tonen!"); }
    });
}

function showPouleSchema(toernid,poule)
{
    $.ajax({
        url: 'jsphp/wedstrijdjs.php',
        data: {
            action: 'pouleschema',
            toern: toernid,
            poule_id: poule
        },
        dataType: 'json',
        type: 'post',
        success: function(data) {
            modal.style.display = "block";
            //$("#myModal").modal("show");
            $(".modal-title").empty().append("Poule-overzicht poule " + poule);
            $(".modal-body").empty().append(data);
            $(".modal-footer").empty();
        },
        error: function() { alert("Het lukt op dit moment niet om de uitslag aan te passen!"); }
    });
}

function highlight(teamnr)
{
    $(".bg-success").removeClass("bg-success");
    $(".wedstr_" + teamnr).addClass("bg-success");
}
