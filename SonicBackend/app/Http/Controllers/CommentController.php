<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Alle Kommentare für einen bestimmten Post laden.
     * Aufruf: GET /api/posts/{id}/comments
     */
    public function index($postId)
    {
        $post = Post::findOrFail($postId);

        $comments = $post->comments()
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($comment) {
                return [
                    'id'        => $comment->id,
                    'author'    => $comment->user->name ?? 'Anonym',
                    'content'   => $comment->content,
                    'parent_id' => $comment->parent_id,
                    'date'      => $comment->created_at->diffForHumans(),
                ];
            });

        return response()->json($comments);
    }

    /**
     * Neuen Kommentar zu einem Post speichern (nur eingeloggte Nutzer).
     * Aufruf: POST /api/posts/{id}/comments
     * Body: { "content": "Mein Kommentar...", "parent_id": null }
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'content'   => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'user_id'   => $request->user()->id,
            'content'   => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'id'        => $comment->id,
            'author'    => $request->user()->name,
            'content'   => $comment->content,
            'parent_id' => $comment->parent_id,
            'date'      => 'Gerade eben',
        ], 201);
    }
}