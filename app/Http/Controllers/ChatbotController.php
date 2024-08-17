<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }
    public function index()
    {

        return view('chat');
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $response = $this->geminiService->sendMessage($message);

        return response()->json(['response' => $response]);
    }
}
