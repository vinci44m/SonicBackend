<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Discussion;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function store(Request $request, $groupId)
    {
        // 1. Validierung: Was darf der User schicken?
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // 2. Gruppe suchen
        $group = Group::findOrFail($groupId);

        // 3. Diskussion erstellen und mit Gruppe/User verknüpfen
        $discussion = $group->discussions()->create([
            'user_id' => auth()->id(), // Der aktuell eingeloggte User
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'message'    => 'Diskussion erfolgreich erstellt!',
            'discussion' => $discussion
        ], 201);
    }
}