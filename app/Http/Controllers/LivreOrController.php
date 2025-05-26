<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class LivreOrController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->get();
        return view('livreor', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        Message::create($request->only('nom', 'message'));

        return redirect()->route('livreor.index')->with('success', 'Merci pour votre message !');
    }
}
