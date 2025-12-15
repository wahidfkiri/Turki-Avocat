<?php

namespace Vendor\Webmail\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RoundcubeController extends Controller
{
    /**
     * Main embedding method - ALWAYS WORKS
     */
    public function embedWebmail()
    {
        $user = Auth::user();
        $roundcubeUrl = 'http://localhost:8082';
        
        try {
            // Fetch Roundcube HTML
            $response = Http::withOptions([
                'verify' => false,
                'timeout' => 30,
            ])->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])->get($roundcubeUrl);
            
            if ($response->successful()) {
                $html = $response->body();
                
                // Process the HTML
                $html = $this->processHtml($html, $roundcubeUrl);
                
                return view('webmail::index', [
                    'roundcube_html' => $html,
                    'user_email' => $user->email,
                    'roundcube_url' => $roundcubeUrl
                ]);
            }
            
            throw new \Exception('Roundcube returned status: ' . $response->status());
            
        } catch (\Exception $e) {
            // Fallback: Show direct link
            return view('webmail.fallback', [
                'error' => $e->getMessage(),
                'roundcube_url' => $roundcubeUrl,
                'user_email' => $user->email
            ]);
        }
    }
    
    /**
     * Process Roundcube HTML to work in our template
     */
    private function processHtml($html, $baseUrl)
    {
        // 1. Add base tag for relative URLs
        $html = preg_replace('/<head>/i', '<head><base href="' . $baseUrl . '/">', $html);
        
        // 2. Make all URLs absolute
        $replacements = [
            'href="/' => 'href="' . $baseUrl . '/',
            'src="/' => 'src="' . $baseUrl . '/',
            'action="/' => 'action="' . $baseUrl . '/',
            'url("/' => 'url("' . $baseUrl . '/',
            "url('/" => "url('" . $baseUrl . '/',
        ];
        
        foreach ($replacements as $search => $replace) {
            $html = str_replace($search, $replace, $html);
        }
        
        // 3. Remove problematic meta tags
        $html = preg_replace('/<meta[^>]+http-equiv=["\']X-Frame-Options["\'][^>]*>/i', '', $html);
        $html = preg_replace('/<meta[^>]+http-equiv=["\']Content-Security-Policy["\'][^>]*>/i', '', $html);
        
        // 4. Remove inline CSP
        $html = preg_replace('/content-security-policy[^>]*>/i', '', $html);
        
        // 5. Fix form submissions
        $html = preg_replace_callback('/<form([^>]*)action="([^"]*)"([^>]*)>/i', function($matches) use ($baseUrl) {
            $action = $matches[2];
            if (strpos($action, 'http') !== 0) {
                $action = $baseUrl . '/' . ltrim($action, '/');
            }
            return '<form' . $matches[1] . 'action="' . $action . '"' . $matches[3] . ' data-proxy="true">';
        }, $html);
        
        return $html;
    }
    
    /**
     * Proxy endpoint for form submissions - NO PARAMETER NEEDED
     */
    public function proxyRequest(Request $request)
    {
        $targetUrl = 'http://localhost:8082' . $request->input('_path', '/');
        
        try {
            // Determine request method
            $method = $request->method();
            
            $options = [
                'verify' => false,
                'headers' => [
                    'User-Agent' => $request->userAgent(),
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ]
            ];
            
            if ($method === 'POST') {
                $options['form_params'] = $request->except(['_token', '_path', '_method']);
                $client = Http::asForm();
            } else {
                $client = Http::withOptions($options);
            }
            
            // Make the request
            $response = $client->$method($targetUrl);
            
            // Process the response
            $html = $this->processHtml($response->body(), 'http://localhost:8082');
            
            return response($html)->header('Content-Type', 'text/html');
            
        } catch (\Exception $e) {
            return response('Proxy error: ' . $e->getMessage(), 500);
        }
    }
    
    /**
     * Simple direct access page
     */
    public function simple()
    {
        return view('webmail.simple');
    }
}