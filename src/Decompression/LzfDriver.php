<?php

declare(strict_types=1);

namespace Pinimize\Lzf\Decompression;

use Exception;
use Pinimize\Decompression\AbstractDecompressionDriver;
use Pinimize\Exceptions\InvalidCompressedDataException;
use RuntimeException;

/**
 * @phpstan-type LzfConfigArray array{
 *     disk: string|null
 * }
 */
class LzfDriver extends AbstractDecompressionDriver
{
    public function getDefaultEncoding(): int
    {
        return 0; // LZF doesn't use encoding
    }

    protected function decompressString(string $string, array $options): string
    {
        if (! function_exists('lzf_decompress')) {
            throw new RuntimeException('LZF extension is not installed');
        }

        try {
            /** @var string|false $result */
            $result = lzf_decompress($string);
        } catch (Exception) {
            throw new InvalidCompressedDataException('Failed to decompress LZF data');
        }

        if ($result === false) {
            throw new InvalidCompressedDataException('Failed to decompress LZF data');
        }

        return $result;
    }

    protected function decompressStream($input, $output, array $options): void
    {
        if (! function_exists('lzf_decompress')) {
            throw new RuntimeException('LZF extension is not installed');
        }

        while (! feof($input)) {
            $chunk = fread($input, 8192);
            if ($chunk === false) {
                throw new RuntimeException('Failed to read from input stream');
            }

            /** @var string|false $decompressed */
            $decompressed = lzf_decompress($chunk);
            if ($decompressed === false) {
                throw new InvalidCompressedDataException('Failed to decompress LZF data');
            }

            fwrite($output, $decompressed);
        }
    }
}
