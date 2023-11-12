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
      url: 'jsphp/uitslagjs.php',
      data: {
	     	action: 'uitslaginvoer',
		 	wedstr: wedstrid,
		 	toern: toernid
		 	},
	  dataType: 'json',
      type: 'post',
      success: function(data) {
	      //alert("gaat goed");
	      $("#exampleModalLong").modal("show");
	      $("#exampleModalLongTitle").empty().append("Voer uitslag in")
	      $(".modal-body").empty().append(data);
	      $(".modal-footer").empty().append("<button type='reset' class='btn btn-default' onclick=\"resetUitlsForm('uitslForm')\">Reset</button>");
	      var thuisscore=$("#homescore").val();
	      if(thuisscore!='')
	      	 	$(".modal-footer").append("<button type='button' class='btn btn-danger' onclick=\"verwijderUitlsForm('" + wedstrid + "')\">Verwijder</button>");
	      $(".modal-footer").append("<button type='button' class='btn btn-success' onclick=\"bewaarUitslForm(" + wedstrid + ")\">Bewaar</button>");
	      },
      error: function() { alert("Het lukt op dit moment niet om de uitslag aan te passen!"); }
    });
}

function resetUitlsForm(uitslForm)
{
	document.getElementById(uitslForm).reset();
}

function bewaarUitslForm(wedstrid)
{
	var thuisscore=$("#homescore").val();
	var uitscore=$("#outscore").val();
	if(thuisscore!='' && uitscore!='')
	{
	$.ajax({
      url: 'jsphp/uitslagjs.php',
      data: {
	     	action: 'uitslagvastleggen',
		 	wedstr: wedstrid,
		 	thuissc: thuisscore,
		 	uitsc: uitscore
		 	},
      type: 'post',
      dataType: 'json',
      success: function(data) {
	      $("#exampleModalLong").modal("hide");
	      $("#wedstr" + wedstrid).empty().html(data);
	      },
      error: function() { alert("Het lukt op dit moment niet om de uitslag aan te passen!"); }
    });
    }
    else
    	alert("Je moet beide scores invullen!");
}

function verwijderUitlsForm(wedstrid)
{

	$.ajax({
      url: 'jsphp/uitslagjs.php',
      data: {
	     	action: 'uitslagverwijderen',
		 	wedstr: wedstrid
		 	},
      type: 'post',
      dataType: 'json',
      success: function(returndata) {
	      $("#exampleModalLong").modal("hide");
	      $("#wedstr" + wedstrid).empty().html(returndata);
	      },
      error: function() { alert("Het lukt op dit moment niet om de uitslag te verwijderen!"); }
    });
}

function showTeamSchema(toernid,teamnr)
{
	$.ajax({
      url: 'jsphp/uitslagjs.php',
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
      url: 'jsphp/uitslagjs.php',
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
