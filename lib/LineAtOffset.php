<?php

namespace Phpactor\TextUtils;

use OutOfBoundsException;
use RuntimeException;

final class LineAtOffset
{
    /**
     * Return the full line at the given offset
     */
    public static function lineAtOffset(string $text, int $byteOffset): string
    {
        $lines = preg_split("{(\r\n|\n|\r)}", $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        if (false === $lines) {
            throw new RuntimeException(
                'Failed to preg-split text into lines'
            );
        }

        $start = 0;
        foreach ($lines as $line) {
            $end = $start + strlen($line);
            if ($byteOffset >= $start && $byteOffset < $end) {
                return $line;
            }
            $start = $end;
        }

        throw new OutOfBoundsException(sprintf(
            'Byte offset %s is larger than text length %s',
            $byteOffset,
            strlen($text)
        ));
    }
}
