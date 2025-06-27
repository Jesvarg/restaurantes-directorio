<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function getAuthToken(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
        
        // Generar token temporal para el chatbot
        $token = base64_encode($user->id . ':' . time());
        
        return response()->json([
            'chatbot_token' => $token,
            'user_id' => $user->id,
            'user_name' => $user->name
        ]);
    }
}