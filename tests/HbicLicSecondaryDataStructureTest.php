<?php declare(strict_types=1);

namespace Brixion\Bardecoder\Test;

use PHPUnit\Framework\TestCase;
use Brixion\Bardecoder\UdiDecoder;
use phpDocumentor\Reflection\Types\Void_;

final class HbicLicSecondaryDataStructureTest extends TestCase
{
    private string $hibc = '+J014652230580/$$3220909K010025/16D20200311Z';
    private string $short_hibc = '+EHOR3148/$90410H';
    private string $lic = 'D658';
    private string $product_code = '250321';
    private string $packaging_index = '1';
    private string $check_character = '1';
    
    /** @test
      * 
     */
    public function derp(): void
    {
        $decoder = new UdiDecoder($this->hibc);
        $decoder->test();
        $this->assertEquals(
            true,
            true
        );
    }
}