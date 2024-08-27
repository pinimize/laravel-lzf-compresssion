<?php

declare(strict_types=1);

namespace Pinimize\Lzf\Tests\Decompression;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Pinimize\Exceptions\InvalidCompressedDataException;
use Pinimize\Lzf\Decompression\LzfDriver;
use Pinimize\Lzf\Tests\TestCase;
use RuntimeException;

class LzfDriverTest extends TestCase
{
    private LzfDriver $lzfDriver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lzfDriver = new LzfDriver([]);
    }

    #[Test]
    #[DataProvider('decompressionDataProvider')]
    public function it_can_decompress_string(string $input, string $expectedOutput): void
    {
        if (! function_exists('lzf_decompress')) {
            $this->markTestSkipped('LZF extension is not installed');
        }

        $decompressed = $this->lzfDriver->string($input);
        $this->assertEquals($expectedOutput, $decompressed);
        $this->assertNotEquals($input, $decompressed);
    }

    public static function decompressionDataProvider(): array
    {
        return [
            'simple string' => [lzf_compress('Hello, World!'), 'Hello, World!'],
            'long string' => [lzf_compress(str_repeat('a', 1000)), str_repeat('a', 1000)],
        ];
    }

    #[Test]
    public function it_throws_an_exception_if_it_fails_to_decompress_a_string(): void
    {
        if (! function_exists('lzf_decompress')) {
            $this->markTestSkipped('LZF extension is not installed');
        }

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Failed to decompress LZF data');
        $this->lzfDriver->string('');
    }

    #[Test]
    public function it_throws_exception_when_lzf_not_installed(): void
    {
        if (function_exists('lzf_decompress')) {
            $this->markTestSkipped('LZF extension is installed');
        }

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('LZF extension is not installed');

        $this->lzfDriver->string('Test');
    }

    #[Test]
    public function it_throws_exception_for_invalid_compressed_data(): void
    {
        if (! function_exists('lzf_decompress')) {
            $this->markTestSkipped('LZF extension is not installed');
        }

        $this->expectException(InvalidCompressedDataException::class);
        $this->expectExceptionMessage('Failed to decompress LZF data');

        $this->lzfDriver->string('Invalid compressed data');
    }

    #[Test]
    public function it_can_decompress_stream(): void
    {
        if (! function_exists('lzf_decompress')) {
            $this->markTestSkipped('LZF extension is not installed');
        }

        $compressed = lzf_compress('Hello, World!');
        $input = fopen('php://temp', 'r+');
        fwrite($input, $compressed);
        rewind($input);

        $resource = $this->lzfDriver->resource($input, []);

        $this->assertIsResource($resource);
        $this->assertEquals('Hello, World!', stream_get_contents($resource));

        fclose($input);
        fclose($resource);

    }
}
