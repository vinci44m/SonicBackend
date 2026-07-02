<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        // Validierung: Prüfen, ob die Daten vom Frontend kommen
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'required|array', // Prüft, ob es ein Array ist
        ]);

        // Daten in der Datenbank speichern
        // Wir nutzen die User-Relation, damit der Post dem User gehört
        $post = $request->user()->posts()->create([
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'tags'    => $validated['tags'],
            'votes'   => 0, // Startwert
        ]);

        // Den erstellten Post zurückgeben
        return response()->json($post, 201);
    }

    // Auch die index()-Methode muss jetzt echte Daten holen:
    public function index()
    {
        return \App\Models\Post::with('user')->latest()->get();
    }

    public function vote(Request $request, $postId)
    {
        $value = $request->input('value'); // 1 oder -1
        $user = $request->user();

        // 1. Vote finden oder neu erstellen (Update-or-Create)
        \App\Models\Vote::updateOrCreate(
            ['user_id' => $user->id, 'post_id' => $postId],
            ['value' => $value]
        );

        // 2. Votes neu berechnen (Summe aller Values für diesen Post)
        $totalVotes = \App\Models\Vote::where('post_id', $postId)->sum('value');

        // 3. Post aktualisieren
        $post = \App\Models\Post::find($postId);
        $post->update(['votes' => $totalVotes]);

        return response()->json(['votes' => $totalVotes]);
    }
}