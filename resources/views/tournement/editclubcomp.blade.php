<?php
/*echo "<pre>";
print_r($tournement);
echo "</pre>";
exit();*/
?>
<form id="edit_clubcomp_form" method="post" class="row g-3 mb-4" action="{{ route('tournement.updateclubcomp', $tournement->id) }}">
    {{ csrf_field() }}
    <div class="col">
        <label class="form-label">Naam van het toernooi</label>
        <input name="inputToernNaam" type="text" class="form-control" value="{{ $tournement->tournement_name }}">
    </div>
    <div class="col-12">
        <label class="form-label">De teams spelen voor een club (i.p.v. elk voor zich)</label>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">Aantal deelnemende clubs (0 bij geen clubcompetitie):</span>
            <input name="inputClubcomp" type="text" class="form-control text-center" aria-describedby="addon-wrapping" value="{{ $tournement->is_clubcompetition }}">
        </div>
    </div>
</form>

<p>
    De volgende instellingen kun je wijzigen door op de knop van een rondenummer (bijv. <button class="btn btn-sm btn-secondary py-0 px-2" type="button">1</button> ) te klikken:
</p>
<ul>
    <li>aanvang</li>
    <li>wedstrijdduur</li>
    <li>wisseltijd tussen wedstrijden</li>
</ul>
<p>De overige instellingen kunnen NIET aangepast worden omdat het hele wedstrijdschema dan anders wordt. Het is dan (technisch veel) eenvoudiger om een nieuw toernooi aan te maken.</p>

