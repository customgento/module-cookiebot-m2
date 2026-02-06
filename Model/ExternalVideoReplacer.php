<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Model;

class ExternalVideoReplacer
{
    public function replaceIframeSources(string $content): string
    {
        $content = $this->replaceIframeTags($content);
        $content = $this->replaceVideoBackgroundAttributes($content);

        return $content;
    }

    private function replaceIframeTags(string $content): string
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

    private function replaceVideoBackgroundAttributes(string $content): string
    {
        // Pattern to match elements with data-video-src attribute containing YouTube or Vimeo URLs
        $videoBackgroundPatterns = [
            // YouTube patterns in data-video-src
            '/(<[^>]+)data-video-src=["\'](https?:\/\/(?:www\.)?(?:youtube\.com|youtube-nocookie\.com)\/embed\/[^"\']+)["\']([^>]*>)/i',
            '/(<[^>]+)data-video-src=["\'](https?:\/\/(?:www\.)?youtu\.be\/[^"\']+)["\']([^>]*>)/i',
            // Vimeo patterns in data-video-src
            '/(<[^>]+)data-video-src=["\'](https?:\/\/(?:www\.)?vimeo\.com\/[^"\']+)["\']([^>]*>)/i',
            '/(<[^>]+)data-video-src=["\'](https?:\/\/(?:www\.)?player\.vimeo\.com\/[^"\']+)["\']([^>]*>)/i'
        ];

        foreach ($videoBackgroundPatterns as $pattern) {
            $content = preg_replace_callback($pattern, function (array $matches) {
                $beforeAttr = $matches[1];
                $videoUrl   = $matches[2];
                $afterAttr  = $matches[3];

                // Check if data-cookieconsent already exists
                if (preg_match('/data-cookieconsent=["\'][^"\']*["\']/', $beforeAttr . $afterAttr)) {
                    return $beforeAttr . 'data-cookieblock-src="' . $videoUrl . '"' . $afterAttr;
                }

                return $beforeAttr . 'data-cookieblock-src="' . $videoUrl
                    . '" data-cookieconsent="marketing"' . $afterAttr;
            }, $content);
        }

        return $content;
    }
}
