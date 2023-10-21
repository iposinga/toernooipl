<?php

namespace App\Http\Controllers;

use App\Models\Finalgame;
use App\Models\Pitch;
use App\Models\Round;
use App\Models\Tournement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RoundController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->merge([
            'start' => Carbon::createFromFormat('d-m-Y H:i', $request->get('start'))->toDateTimeString(),
            'end' => Carbon::createFromFormat('d-m-Y H:i', $request->get('start'))->addMinutes($request->game_duration)->toDateTimeString()
        ]);
        $newround = Round::create($request->except(['_token']));
        Finalgame::makeFinalgame($request->tournement_id, $newround->id);
        return redirect()->back();
    }
    public function update(Request $request): void
    {
        Round::where('id', $request->input('id'))->update($request->except(['_token', 'inputTournementId', 'inputRoundNr', 'start']));
        Round::updateRounds($request->inputTournementId, $request->inputRoundNr, $request->start);
    }
}
