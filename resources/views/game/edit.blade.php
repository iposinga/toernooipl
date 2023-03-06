@extends('layouts.app')
<?php
echo "<pre>";
print_r($wedstrijd);
echo "</pre>";
?>
@section('modal-title')
    Edit uitslag
@endsection

@section('modal-body')
    @auth
        <form id="edit_uitslag" class="row g-3" method="post" action="{{ route('game.update', ['id' => $wedstrijd->id]) }}">
            @csrf
            <div class="col-md-2"></div>
            <div class="col-md-4">
                <label for="inputHomeScore" class="form-label">{{ $wedstrijd->hometeam->team_name }}</label>
                <input type="text" class="form-control text-center" id="inputHomeScore" name="inputHomeScore" value="{{ $wedstrijd->home_score }}">
            </div>
            <div class="col-md-4">
                <label for="inputAwayScore" class="form-label">{{ $wedstrijd->awayteam->team_name }}</label>
                <input type="text" class="form-control text-center" id="inputAwayScore" name="inputAwayScore" value="{{ $wedstrijd->away_score }}">
            </div>
            <div class="col-md-2"></div>
        </form>
    @endauth
@endsection
