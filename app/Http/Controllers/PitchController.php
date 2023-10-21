<?php

namespace App\Http\Controllers;

use App\Models\Pitch;
use Illuminate\Http\Request;

class PitchController extends Controller
{
    public function edit(Request $request)
    {
        $tournement_id = $request->input('id');
        $pitches = Pitch::where('tournement_id', $tournement_id)
            ->orderBy('pitch_nr')
            ->get();
        $html = view('pitch.edit')->with(compact(['pitches', 'tournement_id']))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }

    public function update(Request $request, $id)
    {
        $pitches = Pitch::where('tournement_id', $id)->get();
        foreach ($pitches as $pitch) {
            $pitch->update([
                'pitch_name' => $request->input('inputPitchname_'.$pitch->id),
                'pitch_spot' => $request->input('inputPitchspot_'.$pitch->id),
            ]);
        };
        return redirect()->back();
    }

}
