<?php

declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;

final class UdiDecoderTest extends TestCase
{
    /** @test */
    public function silence_warning(): void
    {
        $this->assertTrue(true);
    }
    /**
     * @test
     * @dataProvider barcode_provider
     * Check if UdiDecoder can instantiate without exceptions using barcode_provider
     */
    public function test_all_barcodes($barcode): void
    {
        ini_set('memory_limit', '6000M');
        $decoder = new UdiDecoder($barcode);

        $this->assertInstanceOf(
            UdiDecoder::class,
            $decoder
        );
    }

    public function barcode_provider()
    {
        return include __DIR__ . "\data\LargeBarcodesArray.php";
    }
}
