<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Test\Unit\Model;

use CustomGento\Cookiebot\Model\YouTubeReplacer;
use PHPUnit\Framework\TestCase;

class YouTubeReplacerTest extends TestCase
{
    /**
     * @var YouTubeReplacer
     */
    private $youTubeReplacer;

    protected function setUp(): void
    {
        $this->youTubeReplacer = new YouTubeReplacer();
    }

    /**
     * @dataProvider iframeDataProvider
     */
    public function testReplaceIframeSources(string $input, string $expected): void
    {
        $result = $this->youTubeReplacer->replaceIframeSources($input);
        $this->assertEquals($expected, $result);
    }

    public function iframeDataProvider(): array
    {
        return [
            // YouTube test cases
            'youtube.com embed' => [
                '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            'youtube-nocookie.com embed' => [
                '<iframe src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            'youtu.be URL' => [
                '<iframe src="https://youtu.be/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtu.be/dQw4w9WgXcQ" width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            'youtube.com without www' => [
                '<iframe src="https://youtube.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtube.com/embed/dQw4w9WgXcQ" width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            'youtube-nocookie.com without www' => [
                '<iframe src="https://youtube-nocookie.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtube-nocookie.com/embed/dQw4w9WgXcQ" width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            'youtu.be without www' => [
                '<iframe src="https://youtu.be/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtu.be/dQw4w9WgXcQ" width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            'http instead of https for youtube' => [
                '<iframe src="http://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="http://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            
            // Vimeo test cases
            'vimeo.com embed' => [
                '<iframe src="https://vimeo.com/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://vimeo.com/123456789" width="640" height="360" data-cookieconsent="marketing"></iframe>'
            ],
            'player.vimeo.com embed' => [
                '<iframe src="https://player.vimeo.com/video/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://player.vimeo.com/video/123456789" width="640" height="360" data-cookieconsent="marketing"></iframe>'
            ],
            'www.vimeo.com embed' => [
                '<iframe src="https://www.vimeo.com/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://www.vimeo.com/123456789" width="640" height="360" data-cookieconsent="marketing"></iframe>'
            ],
            'www.player.vimeo.com embed' => [
                '<iframe src="https://www.player.vimeo.com/video/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://www.player.vimeo.com/video/123456789" width="640" height="360" data-cookieconsent="marketing"></iframe>'
            ],
            'http vimeo' => [
                '<iframe src="http://vimeo.com/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="http://vimeo.com/123456789" width="640" height="360" data-cookieconsent="marketing"></iframe>'
            ],
            
            // Google Maps test cases
            'google.com/maps embed' => [
                '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.2219901290355!2d-74.00369368400567!3d40.71312937933185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a23e28c1191%3A0x49f75d3281df052a!2s150%20Park%20Row%2C%20New%20York%2C%20NY%2010007!5e0!3m2!1sen!2sus!4v1640995200000!5m2!1sen!2sus" width="600" height="450"></iframe>',
                '<iframe data-cookieblock-src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.2219901290355!2d-74.00369368400567!3d40.71312937933185!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a23e28c1191%3A0x49f75d3281df052a!2s150%20Park%20Row%2C%20New%20York%2C%20NY%2010007!5e0!3m2!1sen!2sus!4v1640995200000!5m2!1sen!2sus" width="600" height="450" data-cookieconsent="marketing"></iframe>'
            ],
            'maps.google.com embed' => [
                '<iframe src="https://maps.google.com/maps?q=New+York&t=&z=13&ie=UTF8&iwloc=&output=embed" width="600" height="450"></iframe>',
                '<iframe data-cookieblock-src="https://maps.google.com/maps?q=New+York&t=&z=13&ie=UTF8&iwloc=&output=embed" width="600" height="450" data-cookieconsent="marketing"></iframe>'
            ],
            'google.com/maps without www' => [
                '<iframe src="https://google.com/maps/embed?pb=test" width="600" height="450"></iframe>',
                '<iframe data-cookieblock-src="https://google.com/maps/embed?pb=test" width="600" height="450" data-cookieconsent="marketing"></iframe>'
            ],
            'maps.google.com without www' => [
                '<iframe src="https://maps.google.com/maps?q=test" width="600" height="450"></iframe>',
                '<iframe data-cookieblock-src="https://maps.google.com/maps?q=test" width="600" height="450" data-cookieconsent="marketing"></iframe>'
            ],
            'http google maps' => [
                '<iframe src="http://www.google.com/maps/embed?pb=test" width="600" height="450"></iframe>',
                '<iframe data-cookieblock-src="http://www.google.com/maps/embed?pb=test" width="600" height="450" data-cookieconsent="marketing"></iframe>'
            ],
            
            // General test cases
            'single quotes' => [
                '<iframe src=\'https://www.youtube.com/embed/dQw4w9WgXcQ\' width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src=\'https://www.youtube.com/embed/dQw4w9WgXcQ\' width="560" height="315" data-cookieconsent="marketing"></iframe>'
            ],
            'multiple iframes different services' => [
                '<iframe src="https://www.youtube.com/embed/video1"></iframe><iframe src="https://vimeo.com/video2"></iframe><iframe src="https://www.google.com/maps/embed?pb=test"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/video1" data-cookieconsent="marketing"></iframe><iframe data-cookieblock-src="https://vimeo.com/video2" data-cookieconsent="marketing"></iframe><iframe data-cookieblock-src="https://www.google.com/maps/embed?pb=test" data-cookieconsent="marketing"></iframe>'
            ],
            'mixed content' => [
                '<p>Some text</p><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe><p>More text</p><iframe src="https://vimeo.com/123456789"></iframe>',
                '<p>Some text</p><iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing"></iframe><p>More text</p><iframe data-cookieblock-src="https://vimeo.com/123456789" data-cookieconsent="marketing"></iframe>'
            ],
            'iframe with frameborder and allowfullscreen' => [
                '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen=""></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen="" data-cookieconsent="marketing"></iframe>'
            ],
            'iframe with existing data-cookieconsent' => [
                '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing"></iframe>'
            ],
            'iframe with different data-cookieconsent value' => [
                '<iframe src="https://vimeo.com/123456789" data-cookieconsent="statistics"></iframe>',
                '<iframe data-cookieblock-src="https://vimeo.com/123456789" data-cookieconsent="statistics"></iframe>'
            ],
            'no matching iframe' => [
                '<iframe src="https://example.com/video"></iframe>',
                '<iframe src="https://example.com/video"></iframe>'
            ],
            'no iframe at all' => [
                '<p>Just some text content</p>',
                '<p>Just some text content</p>'
            ],
            'empty content' => [
                '',
                ''
            ]
        ];
    }
} 