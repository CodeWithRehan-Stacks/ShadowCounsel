<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Show the Finance Assistant page.
     */
    public function index()
    {
        return view('finance.index');
    }

    /**
     * Handle a Finance Assistant query (AJAX).
     */
    public function query(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'model'   => 'nullable|string',
            'history' => 'nullable|array',
        ]);

        $systemPrompt = <<<'PROMPT'
You are ShadowFinance, an elite AI Finance Assistant embedded in ShadowCounsel.
Your role is to:
1. Assist with personal and business finance questions clearly and accurately.
2. Explain financial concepts (budgeting, cash flow, investing, tax, valuation, etc.) in plain language.
3. Teach business finance principles step-by-step when asked.
4. Provide real-time search-like answers using your knowledge — cite approximate figures, formulas, and frameworks.
5. Always add a short actionable tip at the end of complex answers.
6. Use markdown formatting: headers, bullet lists, tables, and code blocks for formulas.
7. Keep responses focused, practical, and easy to understand for entrepreneurs and business owners.
If you cannot determine a specific real-time market price (stocks, forex), say so and suggest reliable sources.
PROMPT;

        $history = $request->input('history', []);
        $history[] = ['role' => 'user', 'content' => $request->input('message')];

        // Prepend the system prompt as the first message if history is fresh
        $fullHistory = array_merge(
            [['role' => 'system', 'content' => $systemPrompt]],
            $history
        );

        try {
            $model      = $request->input('model', 'poolside/laguna-xs-2.1:free');
            $aiResponse = $this->aiService->sendMessage($fullHistory, $model);

            return response()->json([
                'success' => true,
                'message' => $aiResponse,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
