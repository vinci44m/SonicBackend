<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Post;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Alle Gruppen laden (für eingeloggten Nutzer mit is_member / is_admin Status).
     */
    public function index(Request $request)
    {
        $userId = optional($request->user())->id;

        $groups = Group::withCount('members')->get()->map(function ($group) use ($userId) {
            $isMember = $userId ? $group->members()->where('user_id', $userId)->exists() : false;
            $isAdmin  = $userId && $group->created_by === $userId;

            return [
                'id'          => $group->id,
                'name'        => $group->name,
                'description' => $group->description,
                'memberCount' => $group->members_count,
                'isMember'    => $isMember,
                'isAdmin'     => $isAdmin,
                'members'     => $group->members()->with('user')->get()->pluck('user.name'),
            ];
        });

        return response()->json($groups);
    }

    /**
     * Neue Gruppe gründen (nur eingeloggte Nutzer).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $group = Group::create([
            'name'        => $request->name,
            'description' => $request->description ?? '',
            'created_by'  => $request->user()->id,
        ]);

        // Gründer direkt als Mitglied eintragen
        $group->members()->create(['user_id' => $request->user()->id]);

        return response()->json([
            'id'          => $group->id,
            'name'        => $group->name,
            'description' => $group->description,
            'memberCount' => 1,
            'isMember'    => true,
            'isAdmin'     => true,
            'members'     => [$request->user()->name],
        ], 201);
    }

    /**
     * Gruppe auflösen (nur Admin/Gründer).
     */
    public function destroy($id)
    {
        // Finde die Gruppe
        $group = Group::findOrFail($id);

        // Prüfe, ob der User der Ersteller ist (entsprechend deiner index() Logik heißt das Feld 'created_by')
        if (auth()->id() !== $group->created_by) {
            return response()->json(['message' => 'Du hast keine Berechtigung, diese Gruppe zu löschen.'], 403);
        }

        // Gruppe löschen
        $group->delete();
        
        return response()->json(['message' => 'Gruppe erfolgreich gelöscht']);
    }

    /**
     * Einer Gruppe beitreten.
     */
    public function join(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $userId = $request->user()->id;

        // Verhindert doppelten Eintrag
        if (!$group->members()->where('user_id', $userId)->exists()) {
            $group->members()->create(['user_id' => $userId]);
        }

        return response()->json(['message' => 'Erfolgreich beigetreten.']);
    }

    /**
     * Eine Gruppe verlassen.
     */
    public function leave(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        $group->members()->where('user_id', $request->user()->id)->delete();

        return response()->json(['message' => 'Gruppe verlassen.']);
    }

    /**
     * Alle Posts einer Gruppe laden.
     */
    public function posts($id)
    {
        $group = Group::findOrFail($id);

        $posts = Post::where('group_id', $id)
            ->with('user')
            ->withCount('comments')
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id'       => $post->id,
                    'title'    => $post->title,
                    'content'  => $post->content,
                    'author'   => $post->user->name ?? 'Unbekannt',
                    'votes'    => $post->votes,
                    'comments' => $post->comments_count,
                    'date'     => $post->created_at->diffForHumans(),
                ];
            });

        return response()->json($posts);
    }

    /**
     * Neue Diskussion innerhalb einer Gruppe erstellen.
     */
    public function storePost(Request $request, $id)
    {
        $group = Group::findOrFail($id);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $post = Post::create([
            'user_id'  => $request->user()->id,
            'group_id' => $id,
            'title'    => $request->title,
            'content'  => $request->content ?? '',
            'tags'     => [],
            'votes'    => 0,
        ]);

        return response()->json([
            'id'       => $post->id,
            'title'    => $post->title,
            'author'   => $request->user()->name,
            'votes'    => 0,
            'comments' => 0,
            'date'     => 'Gerade eben',
        ], 201);
    }
}
