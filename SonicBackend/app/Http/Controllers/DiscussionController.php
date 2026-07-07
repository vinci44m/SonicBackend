<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Discussion;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    // Alle Diskussionen einer Gruppe anzeigen
    public function index($groupId)
    {
        $group = Group::findOrFail($groupId);
        // Wir laden die Diskussionen mit dem User (Autor)
        return response()->json($group->discussions()->with('user')->latest()->get());
    }

    // Neue Diskussion erstellen
    public function store(Request $request, $groupId)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $group = Group::findOrFail($groupId);
        
        $discussion = $group->discussions()->create([
            'user_id' => auth()->id(),
            'title'   => $request->title,
            'content' => $request->content,
        ]);

        return response()->json($discussion, 201);
    }
}