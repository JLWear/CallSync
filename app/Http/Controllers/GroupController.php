<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ], [
            'name.required' => 'Le nom du groupe est requis.',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'owner_id' => auth()->id(),
        ]);

        return response()->json([
            'group' => $group
        ], 201);
    }

    public function addUserToGroup(Request $request, $groupId)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.exists' => 'Aucun utilisateur trouvé avec cet email.',
        ]);

        $group = Group::findOrFail($groupId);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($group->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'L\'utilisateur est déjà dans ce groupe.'], 400);
        }

        $group->users()->attach($user->id);

        return response()->json([
            'message' => 'Utilisateur ajouté au groupe avec succès.',
        ], 200);
    }
}
