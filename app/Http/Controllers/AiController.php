<?php

namespace App\Http\Controllers;

use App\Services\AiService;
use Illuminate\Http\Request;

class AiController extends Controller
{
    protected $aiService;

    public function __construct(AiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function analyze(Request $request)
    {
        $content = $request->input('content', 'Analyze this content.');
        $data = $this->aiService->analyzeContent($content);

        return response()->json($data);
    }

    /**
     * Personalize the message template with lead data.
     *
     * @param string $messageTemplate
     * @param array $scrapedData
     * @return string
     */
    public function personalizeMessage($messageTemplate, $scrapedData)
    {
        $placeholders = ['{{lead_name}}', '{{company_name}}'];
        $values = [$scrapedData['name'], $scrapedData['company']];

        return str_replace($placeholders, $values, $messageTemplate);
    }
}
