<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    // Zeigt alle Videos an (für deine Startseite/Video-Liste)
    public function index()
    {
        return response()->json(Video::all());
    }

    // Speichert ein neues Video (für deinen Upload-Prozess)
    public function store(Request $request)
    {
        // 1. Validierung: Prüft, ob Titel und Videodatei vorhanden sind
        $request->validate([
            'title' => 'required|string|max:255',
            'video' => 'required|file|mimes:mp4,mov,avi|max:204800', // Max 200MB
        ]);

        // 2. Datei speichern: Das Video landet in storage/app/public/videos
        $path = $request->file('video')->store('videos', 'public');

        // 3. Datenbank-Eintrag erstellen
        // NEU: user_id wird jetzt gesetzt (der Upload-Route ist durch
        // auth:sanctum geschützt, request()->user() ist also immer gesetzt).
        $video = Video::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'thumbnail_path' => '/storage/' . $path, // Pfad für das Vorschaubild (hier gleich Video-Pfad)
            'file_path' => '/storage/' . $path,      // Pfad zum Video
        ]);

        return response()->json(['message' => 'Video erfolgreich hochgeladen!', 'video' => $video], 201);
    }

    // Fügt einem Video einen Kommentar hinzu
    public function addComment(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        // Gibt den Kommentar direkt als Antwort zurück, damit Vue ihn sofort anzeigt
        return response()->json([
            'id' => rand(100, 999),
            'user' => 'Student',
            'time' => 'Gerade eben',
            'text' => $request->text
        ], 201);
    }
}
