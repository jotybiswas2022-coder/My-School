<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertDigits
{
    /**
     * The single outer capture group makes preg_split return each whole match
     * (a tag, comment, or script/style block) as the odd delimiters so the
     * even segments are pure visible text.
     */
    private const PATTERN = '/((?:<!--.*?-->)|<(?:script|style)\b[^>]*>.*?<\/(?:script|style)\s*>|<\/?[a-zA-Z][^>]*>)/is';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->getLocale() !== 'bn' || $response->getStatusCode() >= 400) {
            return $response;
        }

        $contentType = $response->headers->get('Content-Type', 'text/html');
        if (! str_contains($contentType, 'html')) {
            return $response;
        }

        if (! method_exists($response, 'getContent')) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $parts = preg_split(self::PATTERN, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

        $converted = '';
        foreach ($parts as $i => $part) {
            // Odd indexes are tags, comments, or script/style blocks.
            $converted .= ($i % 2 === 1) ? $part : self::toBengali($part);
        }

        $response->setContent($converted);

        return $response;
    }

    private static function toBengali(string $text): string
    {
        return strtr($text, [
            '0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪',
            '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯',
        ]);
    }
}