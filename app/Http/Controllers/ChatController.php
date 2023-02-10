<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChatController extends Controller
{
    public function Register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Account created successfully',
            'user' => $user,
        ]);
    }
    public function login()
    {
    }
    public function sendMessage(Request $request)
    {
        $chat = new Chat();
        $chat->from_user_id = Auth::user()->id;
        $chat->to_user_id = $request->to_user_id;
        $chat->message = $request->message;
        $chat->save();
        return response()->json([
            'message' => 'Message sent successfully',
            'chat' => $chat,
        ]);
    }

    public function getMessages($userId)
    {
        $chats = Chat::where(function ($query) use ($userId) {
            $query->where('from_user_id', Auth::id());
            $query->where('to_user_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('from_user_id', $userId);
            $query->where('to_user_id', Auth::id());
        })->get();

        return response()->json([
            'chats' => $chats,
        ]);
    }
}
