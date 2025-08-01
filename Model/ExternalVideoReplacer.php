<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

class ExternalVideoReplacer
{
    public function replaceIframeSources(string $content): string
    {
        $iframePatterns = [
            // YouTube patterns
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?(?:youtube\.com|youtube-nocookie\.com)\/embed\/[^"\']+)["\']([^>]*)>/i',
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?youtu\.be\/[^"\']+)["\']([^>]*)>/i',
            // Vimeo patterns
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?vimeo\.com\/[^"\']+)["\']([^>]*)>/i',
            '/<iframe([^>]*)\s+src=["\'](https?:\/\/(?:www\.)?player\.vimeo\.com\/[^"\']+)["\']([^>]*)>/i'
        ];

        foreach ($iframePatterns as $pattern) {
            $content = preg_replace_callback($pattern, function (array $matches) {
                $beforeSrc = $matches[1];
                $iframeUrl = $matches[2];
                $afterSrc  = $matches[3];

                // Check if data-cookieconsent already exists
                if (preg_match('/data-cookieconsent=["\'][^"\']*["\']/', $beforeSrc . $afterSrc)) {
                    // If data-cookieconsent already exists, just change src to data-cookieblock-src
                    return '<iframe' . $beforeSrc . ' data-cookieblock-src="' . $iframeUrl . '"' . $afterSrc . '>';
                }

                return '<iframe' . $beforeSrc . ' data-cookieblock-src="' . $iframeUrl
                    . '" data-cookieconsent="marketing"' . $afterSrc . '>';
            }, $content);
        }

        return $content;
    }
}
