<?php

namespace App\Http\Controllers;

use App\Services\OpenAIService;
use Illuminate\Http\Request;

class TextGenerationController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function generateText(Request $request)
    {
        $prompt = $request->input('prompt');
        $response = $this->openAIService->generateText($prompt);

        // Process the OpenAI API response as needed
        // ...

        return response()->json($response);
    }
}
