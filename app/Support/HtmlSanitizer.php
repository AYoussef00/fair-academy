<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

final class HtmlSanitizer
{
    private const ALLOWED_TAGS = [
        'p',
        'br',
        'strong',
        'em',
        'b',
        'i',
        'u',
        'ul',
        'ol',
        'li',
        'h2',
        'h3',
        'h4',
        'span',
    ];

    public static function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $wrappedHtml = '<!DOCTYPE html><html><body>'.$html.'</body></html>';

        libxml_use_internal_errors(true);
        $loaded = $dom->loadHTML(
            mb_convert_encoding($wrappedHtml, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NODEFDTD | LIBXML_HTML_NOIMPLIED
        );
        libxml_clear_errors();

        if (! $loaded) {
            return strip_tags($html, '<p><br><strong><em><b><i><u><ul><ol><li><h2><h3><h4><span>');
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        if (! $body) {
            return strip_tags($html, '<p><br><strong><em><b><i><u><ul><ol><li><h2><h3><h4><span>');
        }

        self::sanitizeNode($body);

        $output = '';
        foreach (iterator_to_array($body->childNodes) as $child) {
            $output .= $dom->saveHTML($child);
        }

        return trim($output);
    }

    private static function sanitizeNode(DOMNode $node): void
    {
        $children = iterator_to_array($node->childNodes);
        foreach ($children as $child) {
            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);
                if (! in_array($tag, self::ALLOWED_TAGS, true)) {
                    self::sanitizeNode($child);
                    self::unwrapNode($child);
                    continue;
                }

                while ($child->attributes->length > 0) {
                    $child->removeAttributeNode($child->attributes->item(0));
                }
            }

            self::sanitizeNode($child);
        }
    }

    private static function unwrapNode(DOMNode $node): void
    {
        $parent = $node->parentNode;
        if (! $parent) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }

        $parent->removeChild($node);
    }
}
