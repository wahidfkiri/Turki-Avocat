<?php
// app/Helpers/MimeHelper.php

namespace App\Helpers;

class MimeHelper
{
    public static function decodeMimeHeader($header)
    {
        if (empty($header) || !is_string($header)) {
            return $header;
        }

        // If IMAP extension is available, use it
        if (function_exists('imap_utf8')) {
            $decoded = imap_utf8($header);
            if ($decoded !== false && $decoded !== $header) {
                return $decoded;
            }
        }

        // If mbstring function is available
        if (function_exists('mb_decode_mimeheader')) {
            return mb_decode_mimeheader($header);
        }

        // Manual decoding
        return preg_replace_callback('/=\?([^\?]+)\?([QB])\?([^\?]*)\?=/i', function($matches) {
            $charset = $matches[1];
            $encoding = strtoupper($matches[2]);
            $text = $matches[3];

            if ($encoding === 'Q') {
                $text = preg_replace_callback('/=([0-9A-F]{2})/i', function($hexMatches) {
                    return chr(hexdec($hexMatches[1]));
                }, $text);
                $text = str_replace('_', ' ', $text);
            } elseif ($encoding === 'B') {
                $text = base64_decode($text);
            }

            // Convert charset
            if (strtoupper($charset) !== 'UTF-8') {
                if (function_exists('mb_convert_encoding')) {
                    $text = mb_convert_encoding($text, 'UTF-8', $charset);
                } elseif (function_exists('iconv')) {
                    $text = iconv($charset, 'UTF-8//TRANSLIT', $text);
                }
            }

            return $text;
        }, $header);
    }
}