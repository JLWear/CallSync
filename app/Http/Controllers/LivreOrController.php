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

        foreach ($messages as $message) {
            if ($message->image_path) {
                $message->image_url = Storage::disk('s3')->url($message->image_path);
            }
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
            $imagePath = Storage::disk('s3')->put('messages', $request->file('image'), 'public');
        }

        Message::create([
            'name' => $request->input('nom'),
            'content' => $request->input('message'),
            'image_path' => $imagePath,
        ]);

        return redirect()->route('livreor.index')->with('success', 'Merci pour votre message !');
    }
}
