<?php

namespace App\Http\Controllers;

use App\Services\UrlShortenerService;
use Illuminate\Http\Request;

class ShortenUrlController extends Controller
{
    protected $urlService;

    public function __construct(UrlShortenerService $urlService)
    {
        $this->urlService = $urlService;
    }

    /**
     * Create a new shortened URL.
     * Accepts 'url' and optional 'custom' code.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'url'    => 'required|url|max:2048',
        ]);

        try {
            $result = $this->urlService->shortenUrl($data['url']);

            return response()->json([
                'success' => true,
                'data' => $result
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Redirect a short code to the original URL.
     */
    public function redirect($code)
    {
        try {
            $url = $this->urlService->getOriginalUrl($code);
            
            if (!$url) {
                return response()->json([
                    'success' => false,
                    'message' => 'Short URL not found'
                ], 404);
            }

            return redirect($url);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error redirecting'
            ], 500);
        }
    }
}