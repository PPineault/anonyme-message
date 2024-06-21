<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Broadcast;

use App\Events\NewMessageEvent;



class MessageController extends Controller
{
    /*
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')->get();
        return view('send-message', compact('messages'));
    }
        */

    public function index(Request $request)
    {
        $lastMessageId = $request->input('last_message_id', 0);
        $messages = Message::where('id', '>', $lastMessageId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('send-message', compact('messages'));
    }



    /*    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ]);

        $message = Message::create($validatedData);

        return redirect()->route('send-message')->with('success', 'Message envoyé avec succès !');
    }
        */

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

    public function getNewMessages(Request $request)
    {
        $lastMessageId = $request->input('last_message_id');
        $newMessages = Message::where('id', '>', $lastMessageId)->get();
        return response()->json($newMessages);
    }
    public function updateAjax(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $message->content = $request->input('content');
        $message->save();

        return response()->json($message);
    }
}
