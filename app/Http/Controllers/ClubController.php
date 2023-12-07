<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    public function edit(Request $request)
    {
        $tournement_id = $request->input('id');
        $clubs = Club::where('tournement_id', $tournement_id)
            ->orderBy('club_nr')
            ->get();
        $html = view('club.edit')->with(compact(['clubs', 'tournement_id']))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }
    public function update(Request $request, $id)
    {
        $clubs = Club::where('tournement_id', $id)->get();
        foreach ($clubs as $club) {
            $club->update([
                'club_name' => $request->input('inputClubname_' . $club->id)
            ]);
        };
        return redirect()->route('tournement.show', ['id' => $id, 'poule_id' => 0]);
    }
    public function show(Request $request)
    {
        $clubs = Club::stand($request->input('tournement_id'));
        $html = view('tournement.stand')->with(compact('clubs'))->render();
        return response()->json(['success' => true, 'html' => $html]);
    }
}
