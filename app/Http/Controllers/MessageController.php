<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{

    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();
        return view('send-message', compact('messages'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ]);

        $message = Message::create($validatedData);

        return redirect()->route('send-message')->with('success', 'Message envoyé avec succès !');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return redirect()->route('send-message')->with('success', 'Le message a été supprimé avec succès !');
    }
    public function edit($id)
    {
        $message = Message::findOrFail($id);
        return view('edit-message', compact('message'));
    }
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ]);

        $message = Message::findOrFail($id);
        $message->update($validatedData);

        return redirect()->route('send-message')->with('success', 'Le message a été modifié avec succès !');
    }
}
