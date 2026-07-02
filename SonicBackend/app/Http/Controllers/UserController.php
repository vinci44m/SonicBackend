<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        // Validierung
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'required|string|max:50',
        ]);

        // Update des aktuell eingeloggten Users
        $request->user()->update($validated);

        return response()->json(['message' => 'Profil aktualisiert!']);
    }
}