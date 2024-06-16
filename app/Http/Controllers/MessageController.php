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
}
