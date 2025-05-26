<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::get('/livreor', function (Request $request) {
    $messages = session()->get('messages', []);
    return view('livredor', compact('messages'));
})->name('livreor.index');

Route::post('/livreor', function (Request $request) {
    $request->validate([
        'nom' => 'required|string|max:255',
        'message' => 'required|string|max:1000',
        'image' => 'nullable|image|max:2048', // max 2MB
    ]);

    $path = null;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('uploads', 'public');
    }

    $entry = [
        'nom' => $request->input('nom'),
        'message' => $request->input('message'),
        'image' => $path,
        'date' => now()->format('d/m/Y H:i'),
    ];

    $messages = session()->get('messages', []);
    array_unshift($messages, $entry); // ajoute en haut
    session()->put('messages', $messages);

    return redirect()->route('livreor.index')->with('success', 'Merci pour votre message !');
})->name('livreor.store');
