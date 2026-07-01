<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'E-Mail oder Passwort falsch.'], 401);
        }

        $token = $user->createToken('sonic-token')->plainTextToken;

        return response()->json([
            'message' => 'Login erfolgreich!',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Konto erfolgreich erstellt!'], 201);
    }

    // NEU: wird von routes/api.php unter /user/me erwartet, hat aber
    // bisher gefehlt -> jeder Aufruf von /user/me endete in einem 500er.
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

public function updateProfile(Request $request)
    {
        // 1. Validieren, ob die Daten vom Frontend okay sind
        $request->validate([
            'name' => 'required|string|max:255',
            'semester' => 'nullable|string|max:50',
        ]);

        // 2. Den aktuell eingeloggten User holen
        $user = $request->user();

        // 3. Daten in der Datenbank aktualisieren
        $user->update([
            'name' => $request->name,
            'semester' => $request->semester,
        ]);

        // 4. Erfolgsmeldung ans Frontend schicken
        return response()->json([
            'message' => 'Profil erfolgreich aktualisiert!',
            'user' => $user
        ], 200);
    }

}