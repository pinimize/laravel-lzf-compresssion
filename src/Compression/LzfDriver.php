<?php

declare(strict_types=1);

namespace Pinimize\Lzf\Compression;

use Exception;
use Pinimize\Compression\AbstractCompressionDriver;
use RuntimeException;

/**
 * @phpstan-type LzfConfigArray array{
 *     disk: string|null
 * }
 */
class LzfDriver extends AbstractCompressionDriver
{
    public function getDefaultEncoding(): int
    {
        return 0; // LZF doesn't use encoding
    }

    protected function compressString(string $string, int $level, int $encoding): string
    {
        if (! function_exists('lzf_compress')) {
            throw new RuntimeException('LZF extension is not installed');
        }

        try {
            /** @var string|false $compressed */
            $compressed = lzf_compress($string);
        } catch (Exception) {
            throw new RuntimeException('Failed to compress string');
        }

        if ($compressed === '' || $compressed === '0' || $compressed === false) {
            throw new RuntimeException('Failed to compress string');
        }

        return $compressed;
    }

    public function getSupportedAlgorithms(): array
    {
        return [0]; // LZF only has one algorithm
    }

    public function getFileExtension(): string
    {
        return 'lzf';
    }

    protected function compressStream($input, $output, array $options): void
    {
        if (! function_exists('lzf_compress')) {
            throw new RuntimeException('LZF extension is not installed');
        }

        while (! feof($input)) {
            $chunk = fread($input, 8192);
            if ($chunk === false) {
                throw new RuntimeException('Failed to read from input stream');
            }

            $compressed = lzf_compress($chunk);
            fwrite($output, $compressed);
        }
    }
}
