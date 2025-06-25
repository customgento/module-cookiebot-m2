<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

class ExternalVideoReplacer
{
    /**
     * Replace iframe sources with cookie consent attributes for YouTube, Vimeo, and Google Maps
     *
     * @param string $content
     * @return string
     */
    public function replaceIframeSources(string $content): string
    {
        // Pattern to match iframe sources for various services
        $iframePatterns = [
            // YouTube patterns
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?(?:youtube\.com|youtube-nocookie\.com)\/embed\/[^"\']+)["\']([^>]*)>/i',
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?youtu\.be\/[^"\']+)["\']([^>]*)>/i',
            // Vimeo patterns
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?vimeo\.com\/[^"\']+)["\']([^>]*)>/i',
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?player\.vimeo\.com\/[^"\']+)["\']([^>]*)>/i'
        ];

        foreach ($iframePatterns as $pattern) {
            $content = preg_replace_callback($pattern, function ($matches) {
                $beforeSrc = $matches[1];
                $iframeUrl = $matches[2];
                $afterSrc = $matches[3];
                
                // Check if data-cookieconsent already exists
                if (preg_match('/data-cookieconsent=["\'][^"\']*["\']/', $beforeSrc . $afterSrc)) {
                    // If data-cookieconsent already exists, just change src to data-cookieblock-src
                    return '<iframe' . $beforeSrc . ' data-cookieblock-src="' . $iframeUrl . '"' . $afterSrc . '>';
                } else {
                    // Add data-cookieconsent="marketing" and change src to data-cookieblock-src
                    return '<iframe' . $beforeSrc . ' data-cookieblock-src="' . $iframeUrl . '" data-cookieconsent="marketing"' . $afterSrc . '>';
                }
            }, $content);
        }

        return $content;
    }
} 