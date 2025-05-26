<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Storage;

class LivreOrController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->get();

        // Générer l'URL publique S3 pour chaque image
        foreach ($messages as $message) {
            $message->image_url = $message->image_path
                ? Storage::disk('s3')->url($message->image_path)
                : null;
        }

        return view('livreor', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            // Stocker l'image dans le bucket S3, dossier 'messages'
            $imagePath = $request->file('image')->store('messages', 's3');

            // Rendre l'image publique
            Storage::disk('s3')->setVisibility($imagePath, 'public');
        }

        Message::create([
            'name' => $request->input('nom'),
            'content' => $request->input('message'),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('livreor.index')->with('success', 'Merci pour votre message !');
    }
}
