<?php

declare(strict_types=1);

namespace CustomGento\Cookiebot\Test\Unit\Model;

use CustomGento\Cookiebot\Model\ExternalVideoReplacer;
use PHPUnit\Framework\TestCase;

class ExternalVideoReplacerTest extends TestCase
{
    /**
     * @var ExternalVideoReplacer
     */
    private $externalVideoReplacer;

    protected function setUp(): void
    {
        $this->externalVideoReplacer = new ExternalVideoReplacer();
    }

    /**
     * @dataProvider iframeDataProvider
     */
    public function testReplaceIframeSources(string $input, string $expected): void
    {
        $result = $this->externalVideoReplacer->replaceIframeSources($input);
        $this->assertEquals($expected, $result);
    }

    public function iframeDataProvider(): array
    {
        return [
            // YouTube test cases
            'youtube.com embed'                              => [
                '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],
            'youtube-nocookie.com embed'                     => [
                '<iframe src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],
            'youtu.be URL'                                   => [
                '<iframe src="https://youtu.be/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtu.be/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],
            'youtube.com without www'                        => [
                '<iframe src="https://youtube.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],
            'youtube-nocookie.com without www'               => [
                '<iframe src="https://youtube-nocookie.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtube-nocookie.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],
            'youtu.be without www'                           => [
                '<iframe src="https://youtu.be/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://youtu.be/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],
            'http instead of https for youtube'              => [
                '<iframe src="http://www.youtube.com/embed/dQw4w9WgXcQ" width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="http://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],

            // Vimeo test cases
            'vimeo.com embed'                                => [
                '<iframe src="https://vimeo.com/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://vimeo.com/123456789" data-cookieconsent="marketing" width="640" height="360"></iframe>'
            ],
            'player.vimeo.com embed'                         => [
                '<iframe src="https://player.vimeo.com/video/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://player.vimeo.com/video/123456789" data-cookieconsent="marketing" width="640" height="360"></iframe>'
            ],
            'www.vimeo.com embed'                            => [
                '<iframe src="https://www.vimeo.com/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://www.vimeo.com/123456789" data-cookieconsent="marketing" width="640" height="360"></iframe>'
            ],
            'www.player.vimeo.com embed'                     => [
                '<iframe src="https://www.player.vimeo.com/video/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="https://www.player.vimeo.com/video/123456789" data-cookieconsent="marketing" width="640" height="360"></iframe>'
            ],
            'http vimeo'                                     => [
                '<iframe src="http://vimeo.com/123456789" width="640" height="360"></iframe>',
                '<iframe data-cookieblock-src="http://vimeo.com/123456789" data-cookieconsent="marketing" width="640" height="360"></iframe>'
            ],
            // General test cases
            'single quotes'                                  => [
                '<iframe src=\'https://www.youtube.com/embed/dQw4w9WgXcQ\' width="560" height="315"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing" width="560" height="315"></iframe>'
            ],
            'multiple iframes different services'            => [
                '<iframe src="https://www.youtube.com/embed/video1"></iframe><iframe src="https://vimeo.com/video2"></iframe><iframe src="https://www.google.com/maps/embed?pb=test"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/video1" data-cookieconsent="marketing"></iframe><iframe data-cookieblock-src="https://vimeo.com/video2" data-cookieconsent="marketing"></iframe><iframe src="https://www.google.com/maps/embed?pb=test"></iframe>'
            ],
            'mixed content'                                  => [
                '<p>Some text</p><iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ"></iframe><p>More text</p><iframe src="https://vimeo.com/123456789"></iframe>',
                '<p>Some text</p><iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing"></iframe><p>More text</p><iframe data-cookieblock-src="https://vimeo.com/123456789" data-cookieconsent="marketing"></iframe>'
            ],
            'iframe with frameborder and allowfullscreen'    => [
                '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen=""></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing" frameborder="0" allowfullscreen=""></iframe>'
            ],
            'iframe with existing data-cookieconsent'        => [
                '<iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing"></iframe>',
                '<iframe data-cookieblock-src="https://www.youtube.com/embed/dQw4w9WgXcQ" data-cookieconsent="marketing"></iframe>'
            ],
            'iframe with different data-cookieconsent value' => [
                '<iframe src="https://vimeo.com/123456789" data-cookieconsent="statistics"></iframe>',
                '<iframe data-cookieblock-src="https://vimeo.com/123456789" data-cookieconsent="statistics"></iframe>'
            ],
            'no matching iframe'                             => [
                '<iframe src="https://example.com/video"></iframe>',
                '<iframe src="https://example.com/video"></iframe>'
            ],
            'no iframe at all'                               => [
                '<p>Just some text content</p>',
                '<p>Just some text content</p>'
            ],
            'empty content'                                  => [
                '',
                ''
            ]
        ];
    }
}
