<?php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post; // Sicherstellen, dass Post importiert ist
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'required|array',
        ]);

        $post = $request->user()->posts()->create([
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'tags'    => $validated['tags'],
            'votes'   => 0, 
        ]);

        return response()->json($post, 201);
    }

    public function index()
    {
        $userId = auth()->id();
        
        // Hole alle Posts inklusive der Kommentaranzahl
        $posts = \App\Models\Post::withCount('comments')->get();

        // Wandle die Collection in ein Array um und füge user_vote manuell hinzu
        $data = $posts->map(function ($post) use ($userId) {
            $item = $post->toArray();
            
            // Suche den Vote des Users
            $userVote = \App\Models\Vote::where('post_id', $post->id)
                ->where('user_id', $userId)
                ->value('value'); 
            
            $item['user_vote'] = $userVote ?? 0;
            return $item;
        });

        return response()->json($data);
    }

    public function vote(Request $request, $postId)
    {
        $val = $request->input('value');
        $userId = auth()->id();

        if ($val == 0) {
            \App\Models\Vote::where('user_id', $userId)
                ->where('post_id', $postId)
                ->delete();
        } else {
            \App\Models\Vote::updateOrCreate(
                ['user_id' => $userId, 'post_id' => $postId],
                ['value' => $val]
            );
        }

        $totalVotes = \App\Models\Vote::where('post_id', $postId)->sum('value');
        
        return response()->json(['votes' => $totalVotes]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $group = Group::findOrFail($post->group_id);

        // Darf löschen: Der Autor des Posts ODER der Ersteller der Gruppe
        if (auth()->id() === $post->user_id || auth()->id() === $group->user_id) {
            $post->delete();
            return response()->json(['message' => 'Post gelöscht']);
        }

        return response()->json(['message' => 'Nicht erlaubt'], 403);
    }
}