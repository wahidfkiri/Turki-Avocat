<?php

namespace Vendor\Webmail\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ProxyController extends Controller
{
    protected $baseUrl = 'http://localhost/';
    
    public function handle(Request $request, $path = '')
    {
        $query = $request->getQueryString();
        $url = $this->baseUrl . ($path ?: '') . ($query ? '?' . $query : '');
        
        // Special handling for Snappymail's URLs
        if (strpos($query, '?/') === 0) {
            $url = $this->baseUrl . $query;
        }
        
        $client = new Client([
            'timeout' => 30,
            'cookies' => true,
            'http_errors' => false,
        ]);
        
        try {
            $response = $client->request(
                $request->method(),
                $url,
                $this->getOptions($request)
            );
            
            return $this->handleResponse($response, $request);
            
        } catch (RequestException $e) {
            return $this->handleException($e, $request);
        }
    }
    
    protected function getOptions(Request $request)
    {
        $options = [
            'headers' => array_filter([
                'User-Agent' => $request->userAgent(),
                'Accept' => $request->header('Accept', '*/*'),
                'Accept-Language' => $request->header('Accept-Language'),
                'Cookie' => $request->header('Cookie'),
                'X-Requested-With' => $request->header('X-Requested-With'),
            ]),
        ];
        
        if ($request->isMethod('post')) {
            if ($request->isJson()) {
                $options['json'] = $request->json()->all();
            } else {
                $options['form_params'] = $request->except(['_token']);
            }
        }
        
        return $options;
    }
    
    protected function handleResponse($response, Request $request)
    {
        $content = $response->getBody()->getContents();
        $contentType = $response->getHeaderLine('Content-Type');
        
        // Handle JSON responses specifically
        if (str_contains($contentType, 'application/json')) {
            $data = json_decode($content, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                // Check for Snappymail's error format
                if (isset($data['ErrorCode']) && $data['ErrorCode'] === 401) {
                    // Handle authentication errors
                    return response()->json([
                        'ErrorCode' => 401,
                        'ErrorMessage' => 'Authentication required',
                    ], 401);
                }
            }
            
            return response($content, $response->getStatusCode())
                ->header('Content-Type', $contentType);
        }
        
        // Handle HTML responses
        if (str_contains($contentType, 'text/html')) {
            $content = $this->rewriteUrls($content, route('webmail.proxy', ['path' => '']));
        }
        
        return response($content, $response->getStatusCode())
            ->header('Content-Type', $contentType);
    }
    
    protected function rewriteUrls($html, $proxyBase)
    {
        // Similar rewrite logic as above
        $patterns = [
            '/href="\/?(\?[^"]*)"/' => 'href="' . $proxyBase . '$1"',
            '/src="\/([^"]*)"/' => 'src="' . $proxyBase . '$1"',
            '/url\(\s*["\']?\/([^"\')\s]+)["\']?\s*\)/' => 'url(' . $proxyBase . '$1)',
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $html = preg_replace($pattern, $replacement, $html);
        }
        
        return $html;
    }
    
    protected function handleException(RequestException $e, Request $request)
    {
        \Log::error('Proxy exception', [
            'url' => $request->fullUrl(),
            'error' => $e->getMessage(),
        ]);
        
        return response()->json([
            'error' => true,
            'message' => 'Service unavailable',
        ], 503);
    }
}