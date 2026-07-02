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
        $userId = auth()->id();
        
        // Hole alle Posts inklusive der Information, was der User gevotet hat
        $posts = \App\Models\Post::withCount('comments')
            ->get()
            ->map(function ($post) use ($userId) {
                // Prüfen, ob der User für diesen Post einen Vote hat
                $userVote = \App\Models\Vote::where('post_id', $post->id)
                    ->where('user_id', $userId)
                    ->value('value'); // Gibt 1, -1 oder null zurück
                
                $post->user_vote = $userVote ?? 0;
                return $post;
            });

        return response()->json($posts);
    }

    public function vote(Request $request, $postId)
    {
        $val = $request->input('value'); // 1, -1 oder 0
        $userId = auth()->id();

        // 1. Wenn val 0 ist, wollen wir den Vote entfernen (Toggle aus)
        if ($val == 0) {
            \App\Models\Vote::where('user_id', $userId)
                ->where('post_id', $postId)
                ->delete();
        } else {
            // 2. Sonst (1 oder -1) erstellen oder aktualisieren
            \App\Models\Vote::updateOrCreate(
                ['user_id' => $userId, 'post_id' => $postId],
                ['value' => $val]
            );
        }

        // 3. Ergebnis zurückgeben
        $totalVotes = \App\Models\Vote::where('post_id', $postId)->sum('value');
        
        return response()->json(['votes' => $totalVotes]);
    }
}